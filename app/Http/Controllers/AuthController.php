<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\EmailVerificationCode;
use App\Jobs\SendVerificationEmail;
use App\Notifications\SendVerificationCodeEmail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
       $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6'
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        // Ensure $user is an instance of App\Models\User
        if (!$user instanceof \App\Models\User) {
            $user = User::find($user->id);
        }
        
        // Log untuk debug
        Log::info('Login success', [
            'email' => $user->email,
            'role' => $user->role,
            'user_id' => $user->id
        ]);
        
        // Redirect berdasarkan role
        switch($user->role) {
            case 'koordinator_bk':
            case 'koordinator':
                return redirect()->route('koordinator.dashboard');
                
            case 'guru_bk':
            case 'guru':
                return redirect()->route('guru.dashboard');
                
            case 'siswa':
                return redirect()->route('siswa.dashboard');
                
            default:
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Role tidak valid: ' . $user->role
                ])->withInput($request->only('email'));
        }
    }

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
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:siswa,guru_bk',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Create verification code
        $verificationCode = EmailVerificationCode::getOrCreateForUser($user);

        // Send verification code via email
        try {
            $user->notify(new SendVerificationCodeEmail($verificationCode));
            $successMessage = 'Registrasi berhasil! Kode verifikasi telah dikirim ke ' . $user->email . '. Silakan cek email Anda dalam beberapa detik.';
        } catch (\Exception $e) {
            \Log::error('Error sending verification code: ' . $e->getMessage());
            $successMessage = 'Registrasi berhasil! Silakan cek email Anda untuk kode verifikasi.';
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

        // Redirect ke halaman verify code
        return redirect()->route('verification.code')
            ->with('success', $successMessage);
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
    public function showVerificationCodeForm()
    {
        if (!Auth::check() || Auth::user()->email_verified_at) {
            return redirect()->route('login');
        }

        return view('auth.verify-code');
    }

    /**
     * Verify code submitted by user
     */
    public function verifyCode(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->email_verified_at) {
            return redirect()->route('login');
        }

        // Validasi input
        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => 'Kode verifikasi diperlukan',
            'code.size' => 'Kode verifikasi harus 6 digit',
        ]);

        // Find verification code
        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->first();

        // Check if code exists and is valid
        if (!$verificationCode) {
            return back()->withErrors([
                'code' => 'Kode verifikasi tidak valid'
            ])->withInput();
        }

        if (!$verificationCode->isValid()) {
            if ($verificationCode->isExpired()) {
                $verificationCode->delete();
                return back()->withErrors([
                    'code' => 'Kode verifikasi telah expired. Silakan minta kode baru.'
                ]);
            }

            if ($verificationCode->attempts >= 5) {
                $verificationCode->delete();
                return back()->withErrors([
                    'code' => 'Terlalu banyak percobaan salah. Silakan minta kode baru.'
                ]);
            }
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();
        $verificationCode->markAsVerified();

        Log::info('Email verified', [
            'user_id' => $user->id,
            'email' => $user->email,
            'verified_at' => $user->email_verified_at
        ]);

        // Refresh user instance and update auth state
        $user->refresh();
        Auth::setUser($user);
        $request->session()->regenerate();

        // Redirect to dashboard redirect route (no verified middleware)
        return redirect()->route('dashboard.redirect')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    }

    /**
     * Resend verification code
     */
    public function resendVerificationCode(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->email_verified_at) {
            return redirect()->route('login');
        }

        // Create new code
        $verificationCode = EmailVerificationCode::getOrCreateForUser($user);

        try {
            $user->notify(new SendVerificationCodeEmail($verificationCode));
            return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            Log::error('Error resending verification code: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim kode verifikasi. Silakan coba lagi.']);
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
}