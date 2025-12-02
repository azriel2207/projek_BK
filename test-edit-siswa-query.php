<?php
// Test script untuk verify query result di editSiswa()

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\User;

// Get the laravel app instance
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Test with user ID 4 (azriel)
$userId = 4;

echo "=== TESTING QUERY RESULT ===\n";
echo "User ID: $userId\n\n";

// Test 1: DB::table query (seperti yang dipakai editSiswa)
echo "1. DB::table query result:\n";
$result = DB::table('users')
    ->leftJoin('students', 'users.id', '=', 'students.user_id')
    ->where('users.id', $userId)
    ->where('users.role', 'siswa')
    ->select(
        'users.*',
        'students.id as student_id',
        'students.nis',
        'students.nama_lengkap',
        'students.kelas',
        'students.tgl_lahir',
        'students.alamat',
        'students.no_hp'
    )
    ->first();

if ($result) {
    echo "Type: " . get_class($result) . "\n";
    echo "Properties:\n";
    foreach ((array)$result as $key => $value) {
        echo "  - $key: " . ($value ? $value : 'NULL') . "\n";
    }
    echo "\nAccessing \$result->kelas: " . ($result->kelas ?? 'UNDEFINED') . "\n";
    echo "Accessing \$result->id: " . $result->id . "\n";
} else {
    echo "No result found!\n";
}

// Test 2: Direct Student Model
echo "\n2. Student Model query:\n";
$student = Student::where('user_id', $userId)->first();
if ($student) {
    echo "Type: " . get_class($student) . "\n";
    echo "ID: " . $student->id . "\n";
    echo "Kelas: " . ($student->kelas ?? 'NULL') . "\n";
    echo "Nama: " . $student->nama_lengkap . "\n";
} else {
    echo "No student found!\n";
}

// Test 3: Direct User Model
echo "\n3. User Model query:\n";
$user = User::find($userId);
if ($user) {
    echo "Type: " . get_class($user) . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Student relation: " . ($user->student ? 'EXISTS' : 'NOT EXISTS') . "\n";
    if ($user->student) {
        echo "Student kelas: " . ($user->student->kelas ?? 'NULL') . "\n";
    }
}

echo "\n=== END TEST ===\n";
