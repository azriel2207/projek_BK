<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JanjiKonselingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Routes untuk KOORDINATOR BK
Route::middleware(['auth'])->prefix('koordinator')->name('koordinator.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [KoordinatorController::class, 'dashboard'])->name('dashboard');
    
    // Tambahkan route yang baru
    Route::get('/guru', [KoordinatorController::class, 'kelolaGuru'])->name('guru');
    Route::get('/siswa', [KoordinatorController::class, 'dataSiswa'])->name('siswa');
    Route::get('/laporan', [KoordinatorController::class, 'laporan'])->name('laporan');
    Route::get('/pengaturan', [KoordinatorController::class, 'pengaturan'])->name('pengaturan');
});

// GURU BK
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

// SISWA
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
            return redirect('/')->with('error', 'Role tidak dikenali.');
    }
})->name('dashboard');