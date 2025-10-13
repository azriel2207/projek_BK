<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JanjiKonselingController;
use Illuminate\Support\Facades\Route;

// Route test sederhana
Route::get('/test', function () {
    return "TEST BERHASIL! Framework berjalan.";
});

Route::get('/test-controller', function () {
    try {
        $controller = new App\Http\Controllers\JanjiKonselingController();
        return $controller->yourMethod();
    } catch (Exception $e) {
        return "Controller Error: " . $e->getMessage();
    }
});

// Route utama
Route::get('/', function () {
    return view('welcome');
});

// Route Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Routes Dashboard (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return "Dashboard - Under Development";
    })->name('dashboard');
    
    // Routes Siswa
    Route::get('/siswa/dashboard', function () {
        return view('dashboard.siswa');
    })->name('siswa.dashboard');
    
    Route::get('/siswa/riwayat-konseling', function () {
        return view('siswa.riwayat-konseling');
    })->name('siswa.riwayat-konseling'); // ✅ PERBAIKI TYPO DI SINI
    
    Route::get('/siswa/bimbingan-belajar', function () {
        return view('siswa.bimbingan-belajar');
    })->name('siswa.bimbingan-belajar');
    
    Route::get('/siswa/bimbingan-karir', function () {
        return view('siswa.bimbingan-karir');
    })->name('siswa.bimbingan-karir');
    
    // Routes Janji Konseling
    Route::get('/siswa/janji-konseling', [JanjiKonselingController::class, 'index'])->name('siswa.janji-konseling');
    Route::post('/siswa/janji-konseling', [JanjiKonselingController::class, 'store'])->name('siswa.janji-konseling.store');
    Route::put('/siswa/janji-konseling/{id}', [JanjiKonselingController::class, 'update'])->name('siswa.janji-konseling.update');
    Route::delete('/siswa/janji-konseling/{id}', [JanjiKonselingController::class, 'destroy'])->name('siswa.janji-konseling.destroy');
});

// Route logout (bisa di luar auth)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');