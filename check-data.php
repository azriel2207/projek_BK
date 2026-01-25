<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\User;

echo "\n========================================\n";
echo "📊 STATUS DATA DATABASE\n";
echo "========================================\n\n";

$totalStudents = Student::count();
$totalUsers = User::count();
$totalSiswa = User::where('role', 'siswa')->count();

echo "✓ Total Student Records: {$totalStudents}\n";
echo "✓ Total Users (semua): {$totalUsers}\n";
echo "✓ Total Users (siswa): {$totalSiswa}\n";

echo "\n📋 DAFTAR SISWA (5 pertama):\n";
echo "─────────────────────────────────────────────────────────\n";

$students = Student::with('user')
    ->orderBy('nomor_absen')
    ->take(5)
    ->get();

foreach ($students as $student) {
    echo "No Absen: {$student->nomor_absen} | NIS: {$student->nis} | Nama: {$student->nama_lengkap}\n";
    echo "Email: {$student->user->email}\n";
    echo "─────────────────────────────────────────────────────────\n";
}

echo "\n📋 DAFTAR SISWA (5 terakhir):\n";
echo "─────────────────────────────────────────────────────────\n";

$studentsLast = Student::with('user')
    ->orderBy('nomor_absen', 'desc')
    ->take(5)
    ->orderBy('nomor_absen')
    ->get();

foreach ($studentsLast as $student) {
    echo "No Absen: {$student->nomor_absen} | NIS: {$student->nis} | Nama: {$student->nama_lengkap}\n";
    echo "Email: {$student->user->email}\n";
    echo "─────────────────────────────────────────────────────────\n";
}

echo "\n========================================\n";
if ($totalStudents == 44) {
    echo "✅ SEMUA DATA SISWA SUDAH MASUK!\n";
} else {
    echo "⚠️  DATA SISWA BELUM LENGKAP! (Harapan: 44, Aktual: {$totalStudents})\n";
}
echo "========================================\n\n";
