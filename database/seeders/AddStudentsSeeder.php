<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class AddStudentsSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar nama siswa dengan nomor absen urut dan NIS urut
        $students = [
            ['name' => 'ACHMAD DEVANI RIZQY PRATAMA SETIYAWAN', 'nomor_absen' => 1, 'nis' => '20240001'],
            ['name' => 'AFRIZAL DANI FERDIANSYAH', 'nomor_absen' => 2, 'nis' => '20240002'],
            ['name' => 'AHMAD ZAKY FAZA', 'nomor_absen' => 3, 'nis' => '20240003'],
            ['name' => 'ANDHI LUKMAN SYAH TJAHJONO', 'nomor_absen' => 4, 'nis' => '20240004'],
            ['name' => 'BRYAN ANDRIY SHEVCENKO', 'nomor_absen' => 5, 'nis' => '20240005'],
            ['name' => 'CATHERINE ABIGAIL APRILLIA CANDYSE', 'nomor_absen' => 6, 'nis' => '20240006'],
            ['name' => 'CHELSEA NAYLIXA AZKA', 'nomor_absen' => 7, 'nis' => '20240007'],
            ['name' => 'DAFFA MAULANA WIJAYA', 'nomor_absen' => 8, 'nis' => '20240008'],
            ['name' => 'DENICO TUESDY OESMANA', 'nomor_absen' => 9, 'nis' => '20240009'],
            ['name' => 'DILAN ALAUDIN AMRU', 'nomor_absen' => 10, 'nis' => '20240010'],
            ['name' => 'DIMAS SATRYA IRAWAN', 'nomor_absen' => 11, 'nis' => '20240011'],
            ['name' => 'FADHIL SURYA BUANA', 'nomor_absen' => 12, 'nis' => '20240012'],
            ['name' => 'FAIS FAISHAL HAKIM', 'nomor_absen' => 13, 'nis' => '20240013'],
            ['name' => 'FARDAN HABIBI', 'nomor_absen' => 14, 'nis' => '20240014'],
            ['name' => 'FAREL DWI NUGROHO', 'nomor_absen' => 15, 'nis' => '20240015'],
            ['name' => 'FATCHUR ROCHMAN', 'nomor_absen' => 16, 'nis' => '20240016'],
            ['name' => 'GALANG ARIVIANTO', 'nomor_absen' => 17, 'nis' => '20240017'],
            ['name' => 'HANIFA MAULITA ZAHRA SAFFUDIN', 'nomor_absen' => 18, 'nis' => '20240018'],
            ['name' => 'KENZA EREND PUTRA TAMA', 'nomor_absen' => 19, 'nis' => '20240019'],
            ['name' => 'KHOFIFI AKBAR INDRATAMA', 'nomor_absen' => 20, 'nis' => '20240020'],
            ['name' => 'LUBNA AQILIA SALSABIL', 'nomor_absen' => 21, 'nis' => '20240021'],
            ['name' => 'M. AZRIEL ANHAR', 'nomor_absen' => 22, 'nis' => '20240022'],
            ['name' => 'MARCHELIN EKA FRIANTISA', 'nomor_absen' => 23, 'nis' => '20240023'],
            ['name' => 'MAULANA RIDHO RAMADHAN', 'nomor_absen' => 24, 'nis' => '20240024'],
            ['name' => 'MOCH. DICKY KURNIAWAN', 'nomor_absen' => 25, 'nis' => '20240025'],
            ['name' => 'MOHAMMAD ALIF RIZKY FADHILAH', 'nomor_absen' => 26, 'nis' => '20240026'],
            ['name' => 'MOHAMMAD FAJRI HARIANTO', 'nomor_absen' => 27, 'nis' => '20240027'],
            ['name' => 'MOHAMMAD VALLEN NUR RIZKI PRADANA', 'nomor_absen' => 28, 'nis' => '20240028'],
            ['name' => 'MOH. WIJAYA ANDIKA SAPUTRA', 'nomor_absen' => 29, 'nis' => '20240029'],
            ['name' => 'MUHAMAD FATHUL HADI', 'nomor_absen' => 30, 'nis' => '20240030'],
            ['name' => 'MUHAMMAD FAIRUZ ZAIDAN', 'nomor_absen' => 31, 'nis' => '20240031'],
            ['name' => 'MUHAMMAD IDRIS', 'nomor_absen' => 32, 'nis' => '20240032'],
            ['name' => 'MUHAMMAD MIKAIL KAROMATULLAH', 'nomor_absen' => 33, 'nis' => '20240033'],
            ['name' => 'NASRULLAH AL AMIN', 'nomor_absen' => 34, 'nis' => '20240034'],
            ['name' => 'NOVAN WAHYU HIDAYAT', 'nomor_absen' => 35, 'nis' => '20240035'],
            ['name' => 'NUR AVIVAH MAULUD DIAH', 'nomor_absen' => 36, 'nis' => '20240036'],
            ['name' => 'QODAMA MAULANA YUSUF', 'nomor_absen' => 37, 'nis' => '20240037'],
            ['name' => 'RASSYA RAJA ISLAMI NOVEANSYAH', 'nomor_absen' => 38, 'nis' => '20240038'],
            ['name' => 'RAYHAN ALIF PRATAMA', 'nomor_absen' => 39, 'nis' => '20240039'],
            ['name' => 'RENDI SATRIA NUGROHO WICAKSANA', 'nomor_absen' => 40, 'nis' => '20240040'],
            ['name' => 'RESTU CANDRA NOVIANTO', 'nomor_absen' => 41, 'nis' => '20240041'],
            ['name' => 'RONI KURNIASANDY', 'nomor_absen' => 42, 'nis' => '20240042'],
            ['name' => 'SATRYA PRAMUDYA ANANDITA', 'nomor_absen' => 43, 'nis' => '20240043'],
        ];

        // Wali Kelas ID (user_id untuk walikelas@gmail.com)
        $waliKelasId = 4;

        // Insert semua siswa
        foreach ($students as $index => $studentData) {
            // Generate email dari nama dengan index unik
            $emailName = strtolower(str_replace(' ', '.', $studentData['name']));
            $email = $emailName . '.' . ($index + 1) . '@siswa.local';

            // Check if user already exists
            $existingUser = DB::table('users')->where('email', $email)->first();
            if ($existingUser) {
                $this->command->warn('⚠️  User dengan email ' . $email . ' sudah ada, skip...');
                continue;
            }

            // Create user first
            $user = DB::table('users')->insertGetId([
                'name' => $studentData['name'],
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role' => 'siswa',
                'email_verified_at' => now(),
                'nis_verified' => 1, // Sudah verified
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create student record
            DB::table('students')->insert([
                'user_id' => $user,
                'nama_lengkap' => $studentData['name'],
                'nis' => $studentData['nis'],
                'nomor_absen' => $studentData['nomor_absen'],
                'kelas' => 'XII RPL', // atau adjust sesuai kebutuhan
                'wali_kelas_id' => $waliKelasId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ ' . count($students) . ' siswa berhasil ditambahkan!');
    }
}
