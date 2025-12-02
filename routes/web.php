<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JanjiKonselingController;
use App\Http\Controllers\Koordinator\LaporanController;
use App\Http\Controllers\CatatanController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


// =================================================================
// ROUTES UNTUK LANDING PAGE DAN HALAMAN PUBLIK
// =================================================================
Route::get('/', function () {
    return view('welcome');
});

// =================================================================
// ROUTES UNTUK AUTHENTIKASI (LOGIN, REGISTER, LOGOUT)
// =================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// =================================================================
// ROUTES UNTUK EMAIL VERIFICATION (Code-based)
// =================================================================
Route::middleware('auth')->group(function () {
    // Show verification code form (juga sebagai notice page)
    Route::get('/email/verify-code', [AuthController::class, 'showVerificationCodeForm'])
        ->name('verification.code');
    
    // Alias untuk verification.notice (used by EnsureEmailIsVerified middleware)
    Route::get('/email/verify', [AuthController::class, 'showVerificationCodeForm'])
        ->name('verification.notice');
    
    // Submit verification code
    Route::post('/email/verify-code', [AuthController::class, 'verifyCode'])
        ->name('verification.submit');
    
    // Resend verification code
    Route::post('/email/resend-code', [AuthController::class, 'resendVerificationCode'])
        ->middleware(['throttle:3,1'])
        ->name('verification.resend');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard Redirect (accessible right after verification)
    Route::get('/dashboard/redirect', function (Request $request) {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
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
                return redirect('/login')->with('error', 'Role tidak valid: ' . $user->role);
        }
    })->name('dashboard.redirect');
});

// Route untuk verifikasi email (HARUS di luar auth middleware karena user belum login)
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    // Find user by ID
    $user = \App\Models\User::find($id);
    
    if (!$user) {
        return redirect('/login')->with('error', 'User tidak ditemukan.');
    }
    
    // Verify hash matches email
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect('/login')->with('error', 'Link verifikasi tidak valid.');
    }
    
    // Check if already verified
    if ($user->hasVerifiedEmail()) {
        return redirect('/login')->with('success', 'Email Anda sudah terverifikasi.');
    }
    
    // Mark email as verified
    $user->markEmailAsVerified();
    
    // Login user
    Auth::login($user, true);
    
    // Redirect ke dashboard sesuai role setelah verifikasi
    if ($user->isKoordinatorBK()) {
        return redirect()->route('koordinator.dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    } elseif ($user->isGuruBK()) {
        return redirect()->route('guru.dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    } else {
        return redirect()->route('siswa.dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    }
})->middleware(['signed'])->name('verification.verify');

// =================================================================
// PROTECTED ROUTES - HARUS LOGIN DAN EMAIL TERVERIFIKASI
// =================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile routes
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Dashboard Redirect Berdasarkan Role
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
        
     
    });

    // =================================================================
    // ROUTES UNTUK GURU BK - VERSI SEDERHANA
    // =================================================================
    Route::middleware(['auth', CheckRole::class.':guru_bk,guru'])
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
        
        // DASHBOARD
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
        
        // JADWAL KONSELING
        Route::get('/jadwal', [GuruController::class, 'jadwalKonseling'])->name('jadwal');
        Route::get('/jadwal/tambah', [GuruController::class, 'tambahJadwal'])->name('jadwal.tambah');
        Route::post('/jadwal/simpan', [GuruController::class, 'simpanJadwal'])->name('jadwal.simpan');
        Route::get('/jadwal/{id}/detail', [GuruController::class, 'detailJadwal'])->name('jadwal.detail');
        Route::get('/jadwal/{id}/edit', [GuruController::class, 'editJadwal'])->name('jadwal.edit');
        Route::put('/jadwal/{id}/update', [GuruController::class, 'updateJadwal'])->name('jadwal.update');
        Route::delete('/jadwal/{id}/hapus', [GuruController::class, 'hapusJadwal'])->name('jadwal.hapus');
        
        // PERMINTAAN KONSELING
        Route::get('/permintaan', [GuruController::class, 'semuaPermintaan'])->name('permintaan');
        Route::post('/permintaan/{id}/konfirmasi', [GuruController::class, 'konfirmasiJanji'])->name('permintaan.konfirmasi');
        Route::put('/permintaan/{id}/tolak', [GuruController::class, 'tolakJanji'])->name('permintaan.tolak');
        Route::put('/permintaan/{id}/reschedule', [GuruController::class, 'reschedule'])->name('permintaan.reschedule');
        Route::post('/permintaan/{id}/selesai', [GuruController::class, 'selesaiJanji'])->name('permintaan.selesai');
        
        // MANAJEMEN SISWA & GURU
        Route::get('/siswa', [GuruController::class, 'daftarSiswa'])->name('siswa');
        Route::get('/siswa/{id}', [GuruController::class, 'detailSiswa'])->name('siswa.detail');
        Route::get('/siswa/{id}/riwayat', [GuruController::class, 'riwayatSiswa'])->name('siswa.riwayat');
        Route::get('/siswa/{id}/konseling', [GuruController::class, 'tambahJadwalForSiswa'])->name('siswa.konseling.create');
        Route::post('/siswa/{id}/konseling', [GuruController::class, 'simpanJadwalForSiswa'])->name('siswa.konseling.store');
        Route::get('/siswa/{id}/kelas', [GuruController::class, 'editKelas'])->name('siswa.kelas.edit');
        Route::put('/siswa/{id}/kelas', [GuruController::class, 'updateKelas'])->name('siswa.kelas.update');
        Route::get('/siswa/{id}/edit', [GuruController::class, 'editSiswa'])->name('siswa.edit');
        Route::put('/siswa/{id}', [GuruController::class, 'updateSiswa'])->name('siswa.update');
        
        // MANAJEMEN GURU BK
        Route::get('/guru', [GuruController::class, 'daftarGuru'])->name('guru');
        Route::get('/guru/{id}', [GuruController::class, 'detailGuru'])->name('guru.detail');
        

        // PROFIL GURU BK
        Route::get('/profile/edit', [GuruController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [GuruController::class, 'updateProfile'])->name('profile.update');
        
        // ========== ROUTES UNTUK CATATAN KONSELING ==========
        Route::prefix('riwayat')->name('riwayat.')->group(function () {
            // Daftar & Pencarian
            Route::get('/', [GuruController::class, 'daftarCatatan'])->name('index');
            Route::get('/buat', [GuruController::class, 'buatCatatan'])->name('buat');
            // Form tambah catatan untuk janji tertentu
            Route::get('/{id}/tambah', [GuruController::class, 'tambahCatatanForm'])->name('tambah');
            Route::get('/template', [GuruController::class, 'templateCatatan'])->name('template');
            Route::get('/{id}', [GuruController::class, 'detailCatatan'])->name('detail');
            Route::get('/{id}/edit', [GuruController::class, 'editCatatan'])->name('edit');
            Route::post('/', [GuruController::class, 'simpanCatatan'])->name('simpan');
            Route::put('/{id}', [GuruController::class, 'updateCatatan'])->name('update');
            Route::delete('/{id}', [GuruController::class, 'hapusCatatan'])->name('hapus');
        });
        
        // LAPORAN & STATISTIK
        Route::get('/laporan', [GuruController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/export-pdf', [GuruController::class, 'exportPdf'])->name('laporan.export_pdf');
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
        Route::get('/janji-konseling/{id}/edit', [JanjiKonselingController::class, 'edit'])->name('janji-konseling.edit');
        Route::put('/janji-konseling/{id}', [JanjiKonselingController::class, 'update'])->name('janji-konseling.update');
        Route::delete('/janji-konseling/{id}', [JanjiKonselingController::class, 'destroy'])->name('janji-konseling.destroy');
        Route::get('/riwayat-konseling', [SiswaController::class, 'riwayatKonseling'])->name('riwayat-konseling');
        Route::get('/riwayat-konseling/{id}', [SiswaController::class, 'detailRiwayatKonseling'])->name('riwayat-konseling-detail');
        Route::get('/bimbingan-belajar', [SiswaController::class, 'bimbinganBelajar'])->name('bimbingan-belajar');
        Route::get('/bimbingan-karir', [SiswaController::class, 'bimbinganKarir'])->name('bimbingan-karir');
        Route::get('/riwayat-karir/{id}', [SiswaController::class, 'detailRiwayatKarir'])->name('riwayat-karir-detail');
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
});
