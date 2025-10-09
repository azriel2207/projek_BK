<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Coba login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $user = Auth::user();
            if ($user->role === 'koordinator_bk') {
                return redirect()->route('koordinator.dashboard');
            } elseif ($user->role === 'guru_bk') {
                return redirect()->route('guru.dashboard');
            } else {
                return redirect()->route('siswa.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan form registrasi
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi
     */
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa', // Default role untuk registrasi baru
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        return redirect()->route('siswa.dashboard')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}