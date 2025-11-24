<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JanjiKonselingController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes - TANPA MIDDLEWARE
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile routes (untuk semua role yang login)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Routes untuk Koordinator BK
Route::middleware(['auth'])->prefix('koordinator')->name('koordinator.')->group(function () {
    Route::get('/dashboard', [KoordinatorController::class, 'dashboard'])->name('dashboard');
    
    // Guru routes
    Route::get('/guru', [KoordinatorController::class, 'indexGuru'])->name('guru.index');
    Route::get('/guru/create', [KoordinatorController::class, 'createGuru'])->name('guru.create');
    Route::post('/guru', [KoordinatorController::class, 'storeGuru'])->name('guru.store');
    Route::get('/guru/{id}', [KoordinatorController::class, 'showGuru'])->name('guru.show');
    Route::get('/guru/{id}/edit', [KoordinatorController::class, 'editGuru'])->name('guru.edit');
    Route::put('/guru/{id}', [KoordinatorController::class, 'updateGuru'])->name('guru.update');
    Route::delete('/guru/{id}', [KoordinatorController::class, 'destroyGuru'])->name('guru.destroy');
    
    // Siswa routes
    Route::get('/siswa', [KoordinatorController::class, 'indexSiswa'])->name('siswa.index');
    Route::get('/siswa/create', [KoordinatorController::class, 'createSiswa'])->name('siswa.create');
    Route::post('/siswa', [KoordinatorController::class, 'storeSiswa'])->name('siswa.store');
    Route::get('/siswa/{id}', [KoordinatorController::class, 'showSiswa'])->name('siswa.show');
    Route::get('/siswa/{id}/edit', [KoordinatorController::class, 'editSiswa'])->name('siswa.edit');
    Route::put('/siswa/{id}', [KoordinatorController::class, 'updateSiswa'])->name('siswa.update');
    Route::delete('/siswa/{id}', [KoordinatorController::class, 'destroySiswa'])->name('siswa.destroy');
    
    // Upgrade siswa ke guru BK
    Route::get('/siswa/{id}/upgrade', [KoordinatorController::class, 'showUpgradeForm'])->name('siswa.upgrade-form');
    Route::post('/siswa/{id}/upgrade', [KoordinatorController::class, 'upgradeToGuru'])->name('siswa.upgrade');
    
    // Lainnya
    Route::get('/laporan', [KoordinatorController::class, 'laporan'])->name('laporan');
    Route::get('/pengaturan', [KoordinatorController::class, 'pengaturan'])->name('pengaturan');
});

// Routes untuk Guru BK - HANYA AUTH
Route::middleware(['auth'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [GuruController::class, 'jadwalKonseling'])->name('jadwal');
    Route::get('/jadwal/tambah', [GuruController::class, 'tambahJadwal'])->name('jadwal.tambah');
    Route::post('/jadwal/simpan', [GuruController::class, 'simpanJadwal'])->name('jadwal.simpan');
    Route::get('/permintaan', [GuruController::class, 'semuaPermintaan'])->name('permintaan');
    Route::post('/permintaan/{id}/konfirmasi', [GuruController::class, 'konfirmasiJanji'])->name('permintaan.konfirmasi');
    Route::put('/permintaan/{id}/tolak', [GuruController::class, 'tolakJanji'])->name('permintaan.tolak');
    Route::put('/permintaan/{id}/reschedule', [GuruController::class, 'reschedule'])->name('permintaan.reschedule');
    Route::get('/siswa', [GuruController::class, 'daftarSiswa'])->name('siswa');
    Route::get('/siswa/{id}', [GuruController::class, 'detailSiswa'])->name('siswa.detail');
    Route::get('/catatan', [GuruController::class, 'daftarCatatan'])->name('catatan');
    Route::post('/catatan/{id}', [GuruController::class, 'tambahCatatan'])->name('catatan.store');
    Route::get('/catatan/{id}/detail', [GuruController::class, 'detailCatatan'])->name('catatan.detail');
    Route::get('/laporan', [GuruController::class, 'laporan'])->name('laporan');
    Route::get('/statistik', [GuruController::class, 'statistik'])->name('statistik');
});

// Routes untuk Siswa - HANYA AUTH
Route::middleware(['auth'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/janji-konseling', [JanjiKonselingController::class, 'index'])->name('janji-konseling');
    Route::post('/janji-konseling', [JanjiKonselingController::class, 'store'])->name('janji-konseling.store');
    Route::put('/janji-konseling/{id}', [JanjiKonselingController::class, 'update'])->name('janji-konseling.update');
    Route::delete('/janji-konseling/{id}', [JanjiKonselingController::class, 'destroy'])->name('janji-konseling.destroy');
    Route::get('/riwayat-konseling', [SiswaController::class, 'riwayatKonseling'])->name('riwayat-konseling');
    Route::get('/bimbingan-belajar', [SiswaController::class, 'bimbinganBelajar'])->name('bimbingan-belajar');
    Route::get('/bimbingan-karir', [SiswaController::class, 'bimbinganKarir'])->name('bimbingan-karir');
});

// Fallback dashboard redirect
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    
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
            auth()->logout();
            return redirect('/login')->with('error', 'Role tidak valid: ' . $user->role);
    }
})->name('dashboard');