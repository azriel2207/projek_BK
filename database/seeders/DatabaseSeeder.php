<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\CounselingSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Student::truncate();
        Counselor::truncate();
        CounselingSession::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Admin User
        $adminUser = User::create([
            'username' => 'admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        echo "Admin user created: admin@school.com / password123\n";

        // Create Counselor User
        $counselorUser = User::create([
            'username' => 'konselor1',
            'email' => 'konselor@school.com',
            'password' => Hash::make('password123'),
            'role' => 'counselor',
            'email_verified_at' => now(),
        ]);

        // Create Counselor Profile
        $counselor = Counselor::create([
            'user_id' => $counselorUser->id,
            'nama_lengkap' => 'Dr. Siti Rahayu, M.Pd',
            'nip' => '198012152006042001',
            'no_hp' => '081234567890',
        ]);

        echo "Counselor user created: konselor@school.com / password123\n";

        // Create Student User
        $studentUser = User::create([
            'username' => 'siswa1',
            'email' => 'siswa@school.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Create Student Profile
        $student = Student::create([
            'user_id' => $studentUser->id,
            'nama_lengkap' => 'Ahmad Fauzi',
            'nis' => '2024001',
            'tgl_lahir' => '2008-05-15',
            'alamat' => 'Jl. Merdeka No. 123',
            'no_hp' => '081298765432',
            'kelas' => 'XII IPA 1',
        ]);

        echo "Student user created: siswa@school.com / password123\n";

        // Create Sample Counseling Session - PASTIKAN kolom sesuai
        CounselingSession::create([
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
            'jadwal' => now()->addDays(2),
            'topik' => 'Konsultasi Pemilihan Jurusan Kuliah',
            'status' => 'dijadwalkan',
        ]);

        echo "Sample counseling session created\n";

        // Create additional sample data
        $this->createSampleData();
    }

    private function createSampleData(): void
    {
        // Create additional students
        $students = [
            [
                'username' => 'siswa2',
                'email' => 'siswa2@school.com',
                'nama_lengkap' => 'Maya Sari',
                'nis' => '2024002',
                'kelas' => 'XII IPS 1',
            ],
            [
                'username' => 'siswa3', 
                'email' => 'siswa3@school.com',
                'nama_lengkap' => 'Rizki Pratama',
                'nis' => '2024003',
                'kelas' => 'XII IPA 2',
            ]
        ];

        foreach ($students as $studentData) {
            $user = User::create([
                'username' => $studentData['username'],
                'email' => $studentData['email'],
                'password' => Hash::make('password123'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);

            Student::create([
                'user_id' => $user->id,
                'nama_lengkap' => $studentData['nama_lengkap'],
                'nis' => $studentData['nis'],
                'tgl_lahir' => '2008-'.rand(1,12).'-'.rand(1,28),
                'alamat' => 'Jl. Contoh No. '.rand(1,100),
                'no_hp' => '0813'.rand(1000000,9999999),
                'kelas' => $studentData['kelas'],
            ]);
        }

        // Create additional counselor
        $counselorUser = User::create([
            'username' => 'konselor2',
            'email' => 'konselor2@school.com',
            'password' => Hash::make('password123'),
            'role' => 'counselor',
            'email_verified_at' => now(),
        ]);

        Counselor::create([
            'user_id' => $counselorUser->id,
            'nama_lengkap' => 'Dr. Bambang Sutrisno, M.Psi',
            'nip' => '197508102005011002',
            'no_hp' => '082345678901',
        ]);

        echo "Additional sample data created\n";
        echo "\n=== Login Credentials ===\n";
        echo "Admin: admin@school.com / password123\n";
        echo "Counselor: konselor@school.com / password123\n";
        echo "Student: siswa@school.com / password123\n";
        echo "==========================\n";
    }
}