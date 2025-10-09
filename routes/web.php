<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Routes Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes Dashboard (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard berdasarkan role
    Route::get('/koordinator/dashboard', function () {
        return view('dashboard.koordinator');
    })->name('koordinator.dashboard');
    
    Route::get('/guru/dashboard', function () {
        return view('dashboard.guru');
    })->name('guru.dashboard');
    
    Route::get('/siswa/dashboard', function () {
        return view('dashboard.siswa');
    })->name('siswa.dashboard');
    
    // Default dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'koordinator_bk') {
            return redirect()->route('koordinator.dashboard');
        } elseif ($user->role === 'guru_bk') {
            return redirect()->route('guru.dashboard');
        } else {
            return redirect()->route('siswa.dashboard');
        }
    })->name('dashboard');
});