<?php

// Diagnostic script untuk check siswa data
// Jalankan: php artisan tinker < check-siswa-data.php

use Illuminate\Support\Facades\DB;

echo "=== SISWA DATA DIAGNOSTIC ===\n\n";

echo "1. Check Users Table (role = siswa):\n";
$users = DB::table('users')->where('role', 'siswa')->get();
foreach ($users as $user) {
    echo "   ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Phone: {$user->phone}\n";
}
echo "   Total: {$users->count()}\n\n";

echo "2. Check Students Table:\n";
$students = DB::table('students')->get();
foreach ($students as $student) {
    echo "   ID: {$student->id} | User ID: {$student->user_id} | Kelas: {$student->kelas} | Nama: {$student->nama_lengkap}\n";
}
echo "   Total: {$students->count()}\n\n";

echo "3. Check JOIN (Users + Students):\n";
$joined = DB::table('users')
    ->where('role', 'siswa')
    ->leftJoin('students', 'students.user_id', '=', 'users.id')
    ->select(
        'users.id as user_id',
        'users.name as user_name',
        'users.email',
        'users.phone',
        'students.id as student_id',
        'students.kelas',
        'students.nama_lengkap',
        'students.nis'
    )
    ->get();

foreach ($joined as $item) {
    echo "   User ID: {$item->user_id} | Name: {$item->user_name} | Kelas: {$item->kelas} | Email: {$item->email}\n";
}
echo "   Total: {$joined->count()}\n\n";

echo "4. Check if Student record exists for each User:\n";
$users = DB::table('users')->where('role', 'siswa')->get();
foreach ($users as $user) {
    $student = DB::table('students')->where('user_id', $user->id)->first();
    if ($student) {
        echo "   ✓ User {$user->id} has Student record: {$student->id}\n";
    } else {
        echo "   ✗ User {$user->id} NO Student record!\n";
    }
}

echo "\nDiagnostic complete.\n";
