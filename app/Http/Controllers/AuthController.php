<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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
            'role' => $validated['role'], // Ambil dari input form
            'email_verified_at' => now(),
        ]);

        // Auto login
        Auth::login($user);
        $request->session()->regenerate();

        // Log registration
        Log::info('New user registered', [
            'email' => $user->email,
            'role' => $user->role,
            'user_id' => $user->id
        ]);

        // Redirect sesuai role
        if ($user->role === 'guru_bk') {
            return redirect()->route('guru.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang di Dashboard Guru BK.');
        } else {
            return redirect()->route('siswa.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang di Sistem BK.');
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
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password jika diisi
        if ($request->filled('current_password')) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);
                
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