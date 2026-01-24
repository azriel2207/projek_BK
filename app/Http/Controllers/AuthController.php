<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\EmailVerificationCode;
use App\Models\PasswordResetCode;
use App\Jobs\SendVerificationEmail;
use App\Notifications\SendVerificationCodeEmail;
use App\Mail\PasswordResetCodeMail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return redirect('/');
    }

    public function login(Request $request)
    {
       $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
        'nis' => 'nullable|string'
    ]);

    $credentials = $request->only('email', 'password');
    $nis = $request->input('nis');
    
    Log::info('ATTEMPTING LOGIN', [
        'email' => $credentials['email'],
        'nis' => $nis,
        'timestamp' => now()
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        Log::info('AUTH ATTEMPT SUCCESSFUL', ['user_id' => $user->id, 'email' => $user->email]);
        
        // Ensure $user is an instance of App\Models\User
        if (!$user instanceof \App\Models\User) {
            $user = User::find($user->id);
            Log::info('USER REFRESHED FROM DB', ['user_id' => $user->id, 'role' => $user->role]);
        }
        
        Log::info('Login success', [
            'email' => $user->email,
            'role' => $user->role,
            'user_id' => $user->id
        ]);
        
        // Jika siswa, verifikasi NIS
        if ($user->role === 'siswa') {
            if (!$nis) {
                Auth::logout();
                return back()->withErrors([
                    'nis' => 'NIS harus diisi untuk siswa',
                ])->withInput($request->only('email'));
            }
            
            // Check apakah NIS cocok dengan student record
            $student = \App\Models\Student::where('user_id', $user->id)
                ->where('nis', $nis)
                ->first();
            
            if (!$student) {
                Auth::logout();
                return back()->withErrors([
                    'nis' => 'NIS tidak cocok dengan data siswa',
                ])->withInput($request->only('email'));
            }
            
            Log::info('NIS Verified for siswa', [
                'user_id' => $user->id,
                'nis' => $nis,
                'student_id' => $student->id
            ]);
        }
        
        // Redirect berdasarkan role
        switch($user->role) {
            case 'koordinator_bk':
            case 'koordinator':
                Log::info('SWITCH MATCHED: koordinator_bk, redirecting to koordinator.dashboard');
                return redirect()->route('koordinator.dashboard');
                
            case 'guru_bk':
            case 'guru':
                Log::info('SWITCH MATCHED: guru_bk, redirecting to guru.dashboard');
                return redirect()->route('guru.dashboard');
                
            case 'siswa':
                Log::info('SWITCH MATCHED: siswa, redirecting to siswa.dashboard');
                return redirect()->route('siswa.dashboard');

            case 'wali_kelas':
                Log::info('SWITCH MATCHED: wali_kelas, redirecting to wali_kelas.dashboard');
                return redirect()->route('wali_kelas.dashboard');
                
            default:
                Log::error('SWITCH DEFAULT CASE', ['role' => $user->role, 'role_hex' => bin2hex($user->role)]);
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Role tidak valid: ' . $user->role
                ])->withInput($request->only('email'));
        }
    }

    Log::warning('LOGIN FAILED - AUTH ATTEMPT RETURNED FALSE', [
        'email' => $credentials['email'],
        'timestamp' => now()
    ]);
    
    // Login gagal
    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->withInput($request->only('email'));
}

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'nis' => 'required_if:role,siswa|string|max:20|unique:students,nis',
            'kelas' => 'required_if:role,siswa|string|max:50',
            'role' => 'required|in:siswa,guru_bk',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'class' => $validated['kelas'] ?? null,
            'email_verified_at' => now(), // Otomatis mark sebagai verified (tidak perlu email verification)
        ]);

        // Create student record if role is siswa
        if ($user->role === 'siswa') {
            try {
                \App\Models\Student::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $user->name,
                    'nis' => $validated['nis'],
                    'tgl_lahir' => null,
                    'alamat' => null,
                    'no_hp' => null,
                    'kelas' => $validated['kelas'],
                    'nis_verified' => false, // Belum verifikasi NIS
                ]);
                Log::info('Student record created for new siswa user', [
                    'user_id' => $user->id,
                    'nis' => $validated['nis'],
                    'kelas' => $validated['kelas']
                ]);
            } catch (\Exception $e) {
                Log::error('Error creating student record during registration', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Log registration
        Log::info('New user registered', [
            'email' => $user->email,
            'role' => $user->role,
            'user_id' => $user->id
        ]);

        // Auto login
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect berdasarkan role
        if ($user->role === 'siswa') {
            // Siswa harus verifikasi NIS terlebih dahulu
            return redirect()->route('verification.nis')
                ->with('success', 'Registrasi berhasil! Silakan verifikasi NIS Anda untuk melanjutkan.');
        } else {
            // Guru/Wali kelas langsung bisa akses dashboard
            return redirect()->route('guru.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang ' . $user->name);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log logout
        if ($user) {
            Log::info('User logged out', [
                'email' => $user->email,
                'user_id' => $user->id
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Anda telah logout.');
    }

    /**
     * Show verification code form
     */
    /**
     * Show NIS verification form for siswa
     */
    public function showVerifyNISForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'siswa') {
            return redirect()->route('login');
        }

        if ($user->nis_verified) {
            return redirect()->route('siswa.dashboard');
        }

        $student = $user->student;
        return view('auth.verify-nis', compact('student'));
    }

    /**
     * Verify NIS submitted by siswa
     */
    public function verifyNIS(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'siswa') {
            return redirect()->route('login');
        }

        if ($user->nis_verified) {
            return redirect()->route('siswa.dashboard');
        }

        $request->validate([
            'nis' => 'required|string',
        ], [
            'nis.required' => 'NIS tidak boleh kosong',
        ]);

        $student = $user->student;
        if (!$student) {
            Log::error('Student record not found for user', ['user_id' => $user->id]);
            return back()->withErrors(['nis' => 'Data siswa tidak ditemukan']);
        }

        // Check if NIS matches
        if ($request->nis === $student->nis) {
            $user->nis_verified = true;
            $user->save();

            // Update student record as well
            $student->nis_verified = true;
            $student->save();

            Log::info('NIS verified successfully', [
                'user_id' => $user->id,
                'nis' => $student->nis
            ]);

            return redirect()->route('dashboard.redirect')
                ->with('success', 'NIS berhasil diverifikasi! Selamat datang.');
        } else {
            Log::warning('NIS verification failed - incorrect NIS', [
                'user_id' => $user->id,
                'provided_nis' => $request->nis,
                'actual_nis' => $student->nis
            ]);
            
            return back()->withErrors(['nis' => 'NIS tidak sesuai. Silakan cek kembali.'])
                ->withInput();
        }
    }

    public function showProfile()
    {
        $user = Auth::user();
        
        // Pastikan user sudah login
        if (!$user) {
            return redirect()->route('login');
        }
        
        return view('profile.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Update name dan email
        if (!$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }
        if ($user) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
        }

        // Update password jika diisi
        if ($request->filled('current_password')) {
            // Pastikan password lama sesuai
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();

                Log::info('Password updated', ['user_id' => $user->id]);
            } else {
                return back()->withErrors([
                    'current_password' => 'Password lama tidak sesuai'
                ]);
            }
        }

        Log::info('Profile updated', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset code to email
     */
    public function sendPasswordResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Delete old reset codes
        PasswordResetCode::where('user_id', $user->id)->delete();

        // Generate new code
        $code = PasswordResetCode::generateCode();

        // Create password reset code record
        $resetCode = PasswordResetCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Send email
        try {
            Mail::send(new PasswordResetCodeMail($user, $code));
            
            Log::info('Password reset code sent', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->route('password.verify-code')
                ->with('success', 'Kode reset password telah dikirim ke email Anda. Silakan cek email Anda.')
                ->with('email', $user->email);
        } catch (\Exception $e) {
            Log::error('Error sending password reset code: ' . $e->getMessage());
            $resetCode->delete();
            return back()->withErrors(['email' => 'Gagal mengirim kode reset password. Silakan coba lagi.']);
        }
    }

    /**
     * Show verify password reset code form
     */
    public function showVerifyPasswordResetForm()
    {
        $email = session('email');
        return view('auth.verify-password-reset', compact('email'));
    }

    /**
     * Verify password reset code
     */
    public function verifyPasswordResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ], [
            'email.exists' => 'Email tidak ditemukan.',
            'code.size' => 'Kode harus 6 digit.',
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Find reset code
        $resetCode = PasswordResetCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->first();

        if (!$resetCode) {
            return back()->withErrors(['code' => 'Kode reset password tidak valid.']);
        }

        // Check if code is valid
        if (!$resetCode->isValid()) {
            if ($resetCode->isExpired()) {
                $resetCode->delete();
                return back()->withErrors(['code' => 'Kode reset password telah kadaluarsa. Silakan minta kode baru.']);
            }

            if ($resetCode->attempts >= 3) {
                $resetCode->delete();
                return back()->withErrors(['code' => 'Terlalu banyak percobaan salah. Silakan minta kode baru.']);
            }
        }

        // Store in session for use in reset password form
        $request->session()->put([
            'password_reset_user_id' => $user->id,
            'password_reset_code' => $resetCode->id,
            'password_reset_email' => $user->email,
        ]);

        return redirect()->route('password.reset-form')
            ->with('success', 'Kode terverifikasi. Silakan masukkan password baru Anda.');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request)
    {
        if (!$request->session()->has('password_reset_user_id')) {
            return redirect()->route('password.forgot')
                ->withErrors(['code' => 'Sesi reset password tidak valid. Silakan ulangi proses.']);
        }

        $email = $request->session()->get('password_reset_email');
        return view('auth.reset-password', compact('email'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        if (!$request->session()->has('password_reset_user_id')) {
            return redirect('/login')
                ->withErrors(['code' => 'Sesi reset password tidak valid.']);
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $userId = $request->session()->get('password_reset_user_id');
        $resetCodeId = $request->session()->get('password_reset_code');

        $user = User::find($userId);
        $resetCode = PasswordResetCode::find($resetCodeId);

        if (!$user || !$resetCode) {
            return redirect('/login')
                ->withErrors(['code' => 'Data reset password tidak valid.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete reset code
        $resetCode->delete();

        // Clear all session data
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Password reset successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return redirect('/login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}