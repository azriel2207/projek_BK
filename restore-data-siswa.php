<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

// Data siswa
$dataFormSiswa = [
    'ACHMAD DEVANI RIZQY PRATAMA SETIYAWAN',
    'AFRIZAL DANI FERDIANSYAH',
    'AHMAD ZAKY FAZA',
    'ANDHI LUKMAN SYAH TJAHJONO',
    'BRYAN ANDRIY SHEVCENKO',
    'CATHERINE ABIGAIL APRILLIA CANDYSE',
    'CHELSEA NAYLIXA AZKA',
    'DAFFA MAULANA WIJAYA',
    'DENICO TUESDY OESMANA',
    'DILAN ALAUDIN AMRU',
    'DIMAS SATRYA IRAWAN',
    'FADHIL SURYA BUANA',
    'FAIS FAISHAL HAKIM',
    'FARDAN HABIBI',
    'FAREL DWI NUGROHO',
    'FATCHUR ROCHMAN',
    'GALANG ARIVIANTO',
    'HANIFA MAULITA ZAHRA SAFFUDIN',
    'KENZA EREND PUTRA TAMA',
    'KHOFIFI AKBAR INDRATAMA',
    'LUBNA AQILA SALSABIL',
    'M. AZRIEL ANHAR',
    'MARCHELIN EKA FRIANTISA',
    'MAULANA RIDHO RAMADHAN',
    'MOCH. DICKY KURNIAWAN',
    'MOHAMMAD ALIF RIZKY FADHILAH',
    'MOHAMMAD FAJRI HARIANTO',
    'MOHAMMAD VALLEN NUR RIZKI PRADANA',
    'MOH. WIJAYA ANDIKA SAPUTRA',
    'MUHAMAD FATHUL HADI',
    'MUHAMMAD FAIRUZ ZAIDAN',
    'MUHAMMAD IDRIS',
    'MUHAMMAD MIKAIL KAROMATULLAH',
    'NASRULLAH AL AMIN',
    'NOVAN WAHYU HIDAYAT',
    'NUR AVIVAH MAULUD DIAH',
    'QODAMA MAULANA YUSUF',
    'RASSYA RAJA ISLAMI NOVEANSYAH',
    'RAYHAN ALIF PRATAMA',
    'RENDI SATRIA NUGROHO WICAKSANA',
    'RESTU CANDRA NOVIANTO',
    'RONI KURNIASANDY',
    'SATRYA PRAMUDYA ANANDITA',
];

echo "🔄 RESTORE DATA SISWA\n";
echo "====================================\n\n";

// Get wali kelas
$waliKelas = User::where('role', 'wali_kelas')->first();
if (!$waliKelas) {
    echo "❌ ERROR: Wali Kelas tidak ditemukan\n";
    exit;
}

echo "✓ Wali Kelas: {$waliKelas->name}\n";
echo "✓ Total siswa yang akan di-restore: " . count($dataFormSiswa) . "\n\n";

$successCount = 0;
$errorCount = 0;

foreach ($dataFormSiswa as $index => $namaSiswa) {
    try {
        DB::beginTransaction();

        $nomorAbsen = $index + 1;
        $nis = str_pad($nomorAbsen, 4, '0', STR_PAD_LEFT);
        
        // Extract first name for username
        $parts = explode(' ', $namaSiswa);
        $firstName = strtolower($parts[0]);
        
        // Generate email
        $emailParts = explode(' ', trim($namaSiswa));
        $firstPart = $emailParts[0];
        if (strlen($firstPart) <= 2 && strpos($firstPart, '.') !== false) {
            $firstWord = $emailParts[1] ?? $emailParts[0];
        } else {
            $firstWord = $firstPart;
        }
        $lastWord = end($emailParts);
        $email = strtolower($firstWord . $lastWord) . '@gmail.com';

        // Create User
        $userData = User::create([
            'name' => $namaSiswa,
            'email' => $email,
            'password' => Hash::make('12345678'),
            'role' => 'siswa',
            'phone' => null,
            'class' => 'XII RPL',
            'email_verified_at' => now(),
        ]);

        // Create Student
        $student = Student::create([
            'user_id' => $userData->id,
            'nama_lengkap' => $namaSiswa,
            'nis' => $nis,
            'nomor_absen' => $nomorAbsen,
            'tgl_lahir' => null,
            'alamat' => null,
            'no_hp' => null,
            'kelas' => 'XII RPL',
            'wali_kelas_id' => $waliKelas->id,
        ]);

        // Create StudentIdentity
        \App\Models\StudentIdentity::create([
            'student_id' => $student->id,
            'tempat_lahir' => null,
        ]);

        DB::commit();
        $successCount++;
        
        if ($nomorAbsen % 10 == 0 || $nomorAbsen == count($dataFormSiswa)) {
            echo "✓ {$nomorAbsen}/{" . count($dataFormSiswa) . "} siswa berhasil di-restore\n";
        }

    } catch (\Exception $e) {
        DB::rollBack();
        $errorCount++;
        echo "❌ Error restore siswa '{$namaSiswa}': " . $e->getMessage() . "\n";
    }
}

echo "\n====================================\n";
echo "✅ RESTORE SELESAI\n";
echo "====================================\n";
echo "✓ Berhasil: {$successCount} siswa\n";
echo "❌ Gagal: {$errorCount} siswa\n\n";

// Verification
$totalStudents = Student::count();
$siswaWithWaliKelas = Student::whereNotNull('wali_kelas_id')->count();
echo "✓ Total student records sekarang: {$totalStudents}\n";
echo "✓ Siswa dengan wali_kelas_id: {$siswaWithWaliKelas}\n\n";

if ($totalStudents == count($dataFormSiswa)) {
    echo "✅ DATA SUDAH SELESAI DI-RESTORE!\n";
}
