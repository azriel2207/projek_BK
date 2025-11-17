<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JanjiKonselingController;
use Illuminate\Support\Facades\Route;

// Landing Page & Authentication
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile Settings (untuk semua role yang sudah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Routes untuk KOORDINATOR BK
Route::middleware(['auth'])->prefix('koordinator')->name('koordinator.')->group(function () {
    Route::get('/dashboard', [KoordinatorController::class, 'dashboard'])->name('dashboard');
    // Tambahkan routes koordinator lainnya di sini
});

// Routes untuk GURU BK
Route::middleware(['auth'])->prefix('guru')->name('guru.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
    
    // Kelola Permintaan Konseling
    Route::get('/permintaan', [GuruController::class, 'semuaPermintaan'])->name('permintaan');
    Route::put('/permintaan/{id}/konfirmasi', [GuruController::class, 'konfirmasiJanji'])->name('permintaan.konfirmasi');
    Route::put('/permintaan/{id}/tolak', [GuruController::class, 'tolakJanji'])->name('permintaan.tolak');
    Route::put('/permintaan/{id}/reschedule', [GuruController::class, 'reschedule'])->name('permintaan.reschedule');
    
    // Kelola Jadwal
    Route::get('/jadwal', [GuruController::class, 'jadwalKonseling'])->name('jadwal');
    
    // Kelola Siswa
    Route::get('/siswa', [GuruController::class, 'daftarSiswa'])->name('siswa');
    Route::get('/siswa/{id}', [GuruController::class, 'detailSiswa'])->name('siswa.detail');
    
    // Catatan Konseling
    Route::post('/catatan/{id}', [GuruController::class, 'tambahCatatan'])->name('catatan.store');
});

// Routes untuk SISWA
Route::middleware(['auth'])->prefix('siswa')->name('siswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    
    // Janji Konseling
    Route::get('/janji-konseling', [JanjiKonselingController::class, 'index'])->name('janji-konseling');
    Route::post('/janji-konseling', [JanjiKonselingController::class, 'store'])->name('janji-konseling.store');
    Route::put('/janji-konseling/{id}', [JanjiKonselingController::class, 'update'])->name('janji-konseling.update');
    Route::delete('/janji-konseling/{id}', [JanjiKonselingController::class, 'destroy'])->name('janji-konseling.destroy');
    
    // Riwayat Konseling
    Route::get('/riwayat-konseling', [SiswaController::class, 'riwayatKonseling'])->name('riwayat-konseling');
    
    // Bimbingan Belajar
    Route::get('/bimbingan-belajar', [SiswaController::class, 'bimbinganBelajar'])->name('bimbingan-belajar');
    
    // Bimbingan Karir
    Route::get('/bimbingan-karir', [SiswaController::class, 'bimbinganKarir'])->name('bimbingan-karir');
});

// Fallback route - Auto redirect berdasarkan role
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    
    switch($user->role) {
        case 'koordinator_bk':
            return redirect()->route('koordinator.dashboard');
        case 'guru_bk':
            return redirect()->route('guru.dashboard');
        case 'siswa':
            return redirect()->route('siswa.dashboard');
        default:
            auth()->logout();
            return redirect('/')->with('error', 'Role tidak dikenali. Silakan hubungi administrator.');
    }
})->name('dashboard');