<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'username' => 'admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Counselor User
        $counselorUser = User::create([
            'username' => 'konselor1',
            'email' => 'konselor@school.com',
            'password' => Hash::make('password123'),
            'role' => 'counselor',
            'email_verified_at' => now(),
        ]);

        // Create Student User
        $studentUser = User::create([
            'username' => 'siswa1',
            'email' => 'siswa@school.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Create profiles
        Counselor::create([
            'user_id' => $counselorUser->id,
            'nama_lengkap' => 'Dr. Siti Rahayu, M.Pd',
            'nip' => '198012152006042001',
            'no_hp' => '081234567890',
        ]);

        Student::create([
            'user_id' => $studentUser->id,
            'nama_lengkap' => 'Ahmad Fauzi',
            'nis' => '2024001',
            'tgl_lahir' => '2008-05-15',
            'alamat' => 'Jl. Merdeka No. 123',
            'no_hp' => '081298765432',
            'kelas' => 'XII IPA 1',
        ]);
    }
}