<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\JanjiKonselingController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\GuruController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ...existing code...

// Route utama
Route::get('/', function () {
    return view('welcome');
});

// Route test
Route::get('/test', function () {
    return "TEST BERHASIL! Framework berjalan.";
});

Route::get('/test-controller', function () {
    try {
        $controller = new App\Http\Controllers\JanjiKonselingController();
        return $controller->yourMethod();
    } catch (\Exception $e) {
        return "Controller Error: " . $e->getMessage();
    }
});

// Route Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Routes Siswa (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');

    // TAMBAHKAN ROUTE INI UNTUK GURU BK
    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');

    // TAMBAHKAN ROUTE INI UNTUK KOORDINATOR BK
    Route::get('/koordinator/dashboard', [KoordinatorController::class, 'dashboard'])->name('koordinator.dashboard');

    // Riwayat Konseling
    Route::get('/siswa/riwayat-konseling', [SiswaController::class, 'riwayatKonseling'])->name('siswa.riwayat-konseling');
    
    // Bimbingan Belajar
    Route::get('/siswa/bimbingan-belajar', [SiswaController::class, 'bimbinganBelajar'])->name('siswa.bimbingan-belajar');
    
    // Bimbingan Karir
    Route::get('/siswa/bimbingan-karir', [SiswaController::class, 'bimbinganKarir'])->name('siswa.bimbingan-karir');
    
    // Janji Konseling
    Route::get('/siswa/janji-konseling', [JanjiKonselingController::class, 'index'])->name('siswa.janji-konseling');
    Route::post('/siswa/janji-konseling', [JanjiKonselingController::class, 'store'])->name('siswa.janji-konseling.store');
    Route::put('/siswa/janji-konseling/{id}', [JanjiKonselingController::class, 'update'])->name('siswa.janji-konseling.update');
    Route::delete('/siswa/janji-konseling/{id}', [JanjiKonselingController::class, 'destroy'])->name('siswa.janji-konseling.destroy');
    
    // Profile & Password
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Jika AuthController menyediakan change password:
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::put('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

// Default dashboard redirect
Route::get('/dashboard', function () {
    return redirect()->route('siswa.dashboard');
})->name('dashboard');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Development routes (hapus di production)
Route::get('/reset-password/{email}', function($email) {
    try {
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) return "User tidak ditemukan!";
        
        $newPassword = 'password123';
        $user->password = bcrypt($newPassword);
        $user->save();
        
        return "
            <h2>Password Reset Berhasil!</h2>
            <p><strong>Email:</strong> {$user->email}</p>
            <p><strong>Password Baru:</strong> {$newPassword}</p>
            <p><strong>Role:</strong> {$user->role}</p>
            <br>
            <a href='/login'>Login Sekarang</a>
        ";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/check-users', function() {
    echo "<h2>Daftar User Terdaftar:</h2>";
    try {
        $users = DB::table('users')->select('name', 'email', 'role', 'created_at')->get();
        if ($users->count() > 0) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Nama</th><th>Email</th><th>Role</th><th>Dibuat</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user->name}</td>";
                echo "<td><strong>{$user->email}</strong></td>";
                echo "<td>{$user->role}</td>";
                echo "<td>{$user->created_at}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada user terdaftar</p>";
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
});