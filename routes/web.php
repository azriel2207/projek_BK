<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JanjiKonselingController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\Koordinator\LaporanController;
use App\Http\Controllers\CatatanController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureNISIsVerified;
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
    
    // Forgot Password Routes
    Route::get('/password/forgot', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
    Route::post('/password/send-code', [AuthController::class, 'sendPasswordResetCode'])->name('password.send-code');
    Route::get('/password/verify-code', [AuthController::class, 'showVerifyPasswordResetForm'])->name('password.verify-code');
    Route::post('/password/verify-code', [AuthController::class, 'verifyPasswordResetCode'])->name('password.verify-submit');
    Route::get('/password/reset', [AuthController::class, 'showResetPasswordForm'])->name('password.reset-form');
    Route::post('/password/reset', [AuthController::class, 'updatePassword'])->name('password.update');
});

// =================================================================
// ROUTES UNTUK AUTHENTICATED USERS
// =================================================================
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard Redirect
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
                
            case 'wali_kelas':
                return redirect()->route('wali_kelas.dashboard');
                
            case 'siswa':
                return redirect()->route('siswa.dashboard');
                
            default:
                Auth::logout();
                $request->session()->invalidate();
                return redirect('/login')->with('error', 'Role tidak valid: ' . $user->role);
        }
    })->name('dashboard.redirect');
});

// Routes untuk NIS Verification (untuk siswa yang sudah login tapi belum verifikasi NIS)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify-nis', [AuthController::class, 'showVerifyNISForm'])->name('verification.nis');
    Route::post('/email/verify-nis', [AuthController::class, 'verifyNIS'])->name('verification.nis.submit');
});

// =================================================================
// PROTECTED ROUTES - HARUS LOGIN
// =================================================================
Route::middleware(['auth'])->group(function () {
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
        
        // Daftar Siswa (view/cek siswa)
        Route::get('/siswa-list', [KoordinatorController::class, 'daftarSiswa'])->name('siswa-list');
        Route::get('/siswa-list/{id}', [KoordinatorController::class, 'detailSiswa'])->name('siswa-list.detail');
        
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
        
        // Wali Kelas routes
        Route::get('/wali-kelas', [KoordinatorController::class, 'indexWaliKelas'])->name('wali-kelas.index');
        Route::get('/wali-kelas/create', [KoordinatorController::class, 'createWaliKelas'])->name('wali-kelas.create');
        Route::post('/wali-kelas', [KoordinatorController::class, 'storeWaliKelas'])->name('wali-kelas.store');
        Route::get('/wali-kelas/{id}/edit', [KoordinatorController::class, 'editWaliKelas'])->name('wali-kelas.edit');
        Route::put('/wali-kelas/{id}', [KoordinatorController::class, 'updateWaliKelas'])->name('wali-kelas.update');
        Route::delete('/wali-kelas/{id}', [KoordinatorController::class, 'destroyWaliKelas'])->name('wali-kelas.destroy');
        
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
        
        // DAFTAR SISWA (view/cek siswa)
        Route::get('/siswa-list', [GuruController::class, 'daftarSiswa'])->name('siswa-list');
        Route::get('/siswa-list/{id}', [GuruController::class, 'detailSiswa'])->name('siswa-list.detail');
        
        // JADWAL KONSELING
        Route::get('/jadwal', [GuruController::class, 'jadwalKonseling'])->name('jadwal');
        Route::get('/jadwal/tambah', [GuruController::class, 'tambahJadwal'])->name('jadwal.tambah');
        Route::post('/jadwal/simpan', [GuruController::class, 'simpanJadwal'])->name('jadwal.simpan');
        Route::get('/jadwal/{id}/detail', [GuruController::class, 'detailJadwal'])->name('jadwal.detail');
        Route::get('/jadwal/{id}/edit', [GuruController::class, 'editJadwal'])->name('jadwal.edit');
        Route::get('/jadwal/{id}/selesai', [GuruController::class, 'selesaiJadwal'])->name('jadwal.selesai');
        Route::put('/jadwal/{id}/update', [GuruController::class, 'updateJadwal'])->name('jadwal.update');
        Route::delete('/jadwal/{id}/hapus', [GuruController::class, 'hapusJadwal'])->name('jadwal.hapus');
        
        // PERMINTAAN KONSELING
        Route::get('/permintaan', [GuruController::class, 'semuaPermintaan'])->name('permintaan');
        Route::post('/permintaan/{id}/konfirmasi', [GuruController::class, 'konfirmasiJanji'])->name('permintaan.konfirmasi');
        Route::put('/permintaan/{id}/tolak', [GuruController::class, 'tolakJanji'])->name('permintaan.tolak');
        
        // INPUT CATATAN KONSELING
        Route::get('/catatan/{id}/input', [GuruController::class, 'inputCatatan'])->name('catatan.input');
        Route::post('/catatan/{id}/save', [GuruController::class, 'saveCatatan'])->name('catatan.save');
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
    // ROUTES UNTUK WALI KELAS
    // =================================================================
    Route::middleware(['auth', CheckRole::class.':wali_kelas'])
        ->prefix('wali-kelas')
        ->name('wali_kelas.')
        ->group(function () {
        Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('dashboard');
        Route::get('/siswa', [WaliKelasController::class, 'daftarSiswa'])->name('daftar-siswa');
        Route::post('/siswa', [WaliKelasController::class, 'storeTambahSiswa'])->name('tambah-siswa.store');
        Route::get('/siswa/tambah', [WaliKelasController::class, 'tambahSiswa'])->name('tambah-siswa');
        Route::get('/siswa/create/baru', [WaliKelasController::class, 'createSiswa'])->name('create-siswa');
        Route::post('/siswa/create/baru', [WaliKelasController::class, 'storeSiswaBaru'])->name('create-siswa.store');
        Route::get('/siswa/{id}', [WaliKelasController::class, 'detailSiswa'])->name('detail-siswa');
        Route::post('/siswa/{id}/catatan', [WaliKelasController::class, 'tambahCatatan'])->name('catatan.tambah');
        Route::put('/siswa/catatan/{id}', [WaliKelasController::class, 'editCatatan'])->name('catatan.edit');
        Route::delete('/siswa/catatan/{id}', [WaliKelasController::class, 'hapusCatatan'])->name('catatan.hapus');
        Route::get('/siswa/{id}/data-diri', [WaliKelasController::class, 'kelolaDataDiri'])->name('data-diri');
        Route::put('/siswa/{id}/data-diri', [WaliKelasController::class, 'updateDataDiri'])->name('data-diri.update');
    });

    // =================================================================
    // ROUTES UNTUK GURU BK - CATAT DATA SISWA (PERILAKU/RIWAYAT)
    // =================================================================
    Route::middleware(['auth', CheckRole::class.':guru_bk,guru'])
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
        // Data siswa - catat riwayat/perilaku
        Route::get('/siswa/{id}/catat-data', [GuruController::class, 'catatDataSiswaForm'])->name('siswa.catat-data');
        Route::post('/siswa/{id}/catat-data', [GuruController::class, 'simpanDataSiswa'])->name('siswa.catat-data.store');
        Route::get('/siswa/{id}/riwayat-detail', [GuruController::class, 'lihatRiwayatSiswa'])->name('siswa.riwayat-detail');
        Route::get('/siswa/riwayat/{id}/edit', [GuruController::class, 'editRiwayatSiswa'])->name('siswa.riwayat.edit');
        Route::put('/siswa/riwayat/{id}', [GuruController::class, 'updateRiwayatSiswa'])->name('siswa.riwayat.update');
        Route::delete('/siswa/riwayat/{id}', [GuruController::class, 'hapusRiwayatSiswa'])->name('siswa.riwayat.delete');
    });

    // =================================================================
    Route::middleware(['auth', CheckRole::class.':siswa', EnsureNISIsVerified::class])
        ->prefix('siswa')
        ->name('siswa.')
        ->group(function () {
        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
        Route::get('/janji-konseling', [JanjiKonselingController::class, 'index'])->name('janji-konseling');
        Route::post('/janji-konseling', [JanjiKonselingController::class, 'store'])->name('janji-konseling.store');
        Route::get('/janji-konseling/{id}/edit', [JanjiKonselingController::class, 'edit'])->name('janji-konseling.edit');
        Route::put('/janji-konseling/{id}', [JanjiKonselingController::class, 'update'])->name('janji-konseling.update');
        Route::delete('/janji-konseling/{id}', [JanjiKonselingController::class, 'destroy'])->name('janji-konseling.destroy');
        Route::post('/janji-konseling/{id}/archive', [JanjiKonselingController::class, 'archive'])->name('janji-konseling.archive');
        Route::get('/riwayat-konseling', [SiswaController::class, 'riwayatKonseling'])->name('riwayat-konseling');
        Route::get('/riwayat-konseling/{id}', [SiswaController::class, 'detailRiwayatKonseling'])->name('riwayat-konseling-detail');
        Route::get('/bimbingan-belajar', [SiswaController::class, 'bimbinganBelajar'])->name('bimbingan-belajar');
        Route::get('/bimbingan-karir', [SiswaController::class, 'bimbinganKarir'])->name('bimbingan-karir');
        Route::get('/riwayat-karir/{id}', [SiswaController::class, 'detailRiwayatKarir'])->name('riwayat-karir-detail');
        // Routes untuk Catatan dari Guru BK
        Route::get('/catatan', [SiswaController::class, 'daftarCatatan'])->name('catatan.index');
        Route::get('/catatan/{id}', [SiswaController::class, 'detailCatatan'])->name('catatan.detail');
        Route::get('/debug-janji', function() {
            return view('siswa.debug-janji');
        })->name('debug-janji');
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
                
            case 'wali_kelas':
                return redirect()->route('wali_kelas.dashboard');
                
            case 'siswa':
                return redirect()->route('siswa.dashboard');
                
            default:
                Auth::logout();
                return redirect('/login')->with('error', 'Role tidak valid: ' . $user->role);
        }
    })->name('dashboard');
});
