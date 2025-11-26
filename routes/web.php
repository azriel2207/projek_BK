<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JanjiKonselingController;
use App\Http\Controllers\Koordinator\LaporanController;
use App\Http\Controllers\Koordinator\PengaturanController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
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

// =================================================================
// ROUTES UNTUK KOORDINATOR BK - MENGGUNAKAN FULL CLASS PATH
// =================================================================
Route::middleware(['auth', CheckRole::class.':koordinator,koordinator_bk'])
    ->prefix('koordinator')
    ->name('koordinator.')
    ->group(function () {
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
        
        // Laporan routes
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::post('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export');
        Route::get('/laporan/statistik-trend', [LaporanController::class, 'statistikTrend'])->name('laporan.trend');
        Route::get('/laporan/performa-guru', [LaporanController::class, 'performaGuru'])->name('laporan.performa');
        Route::get('/laporan/kasus-prioritas', [LaporanController::class, 'kasusPrioritas'])->name('laporan.prioritas');
        Route::post('/laporan/update-periode', [LaporanController::class, 'updatePeriode'])->name('laporan.update-periode');
        
        // Laporan generate
        Route::post('/laporan/generate-bulanan', [LaporanController::class, 'generateLaporanBulanan'])->name('laporan.generate-bulanan');
        Route::get('/laporan/generate-trend', [LaporanController::class, 'generateStatistikTrend'])->name('laporan.generate-trend');
        Route::get('/laporan/generate-performa', [LaporanController::class, 'generatePerformaGuru'])->name('laporan.generate-performa');
        Route::get('/laporan/generate-prioritas', [LaporanController::class, 'generateKasusPrioritas'])->name('laporan.generate-prioritas');
        
        // PENGATURAN SISTEM
        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            Route::get('/', [PengaturanController::class, 'index'])->name('index');
            Route::get('/umum', [PengaturanController::class, 'general'])->name('general');
            Route::post('/umum', [PengaturanController::class, 'updateGeneral'])->name('general.update');
            Route::post('/reset', [PengaturanController::class, 'resetSettings'])->name('reset');
        });
        
        // Legacy route
        Route::get('/pengaturan', [KoordinatorController::class, 'pengaturan'])->name('pengaturan');
    });

// =================================================================
// ROUTES UNTUK GURU BK - MENGGUNAKAN FULL CLASS PATH
// =================================================================
Route::middleware(['auth', CheckRole::class.':guru_bk,guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
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

// =================================================================
// ROUTES UNTUK SISWA - MENGGUNAKAN FULL CLASS PATH
// =================================================================
Route::middleware(['auth', CheckRole::class.':siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
        Route::get('/janji-konseling', [JanjiKonselingController::class, 'index'])->name('janji-konseling');
        Route::post('/janji-konseling', [JanjiKonselingController::class, 'store'])->name('janji-konseling.store');
        Route::put('/janji-konseling/{id}', [JanjiKonselingController::class, 'update'])->name('janji-konseling.update');
        Route::delete('/janji-konseling/{id}', [JanjiKonselingController::class, 'destroy'])->name('janji-konseling.destroy');
        Route::get('/riwayat-konseling', [SiswaController::class, 'riwayatKonseling'])->name('riwayat-konseling');
        Route::get('/bimbingan-belajar', [SiswaController::class, 'bimbinganBelajar'])->name('bimbingan-belajar');
        Route::get('/bimbingan-karir', [SiswaController::class, 'bimbinganKarir'])->name('bimbingan-karir');
    });

// =================================================================
// FALLBACK DASHBOARD REDIRECT
// =================================================================
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = Auth::user();
    
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
            return redirect('/login')->with('error', 'Role tidak valid: ' . $user->role);
    }
})->name('dashboard');