<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddMissingFirstStudentSeeder extends Seeder
{
    public function run(): void
    {
        $waliKelasId = 4;

        // Check if ACHMAD DEVANI already exists
        $existing = DB::table('students')
            ->where('nis', '20240001')
            ->first();

        if ($existing) {
            $this->command->info('✅ ACHMAD DEVANI RIZQY PRATAMA SETIYAWAN sudah ada');
            return;
        }

        // Create user
        $email = 'achmad.devani.rizqy.pratama.setiyawan.1@siswa.local';
        
        // Check if email exists
        $existingUser = DB::table('users')->where('email', $email)->first();
        
        if (!$existingUser) {
            $user = DB::table('users')->insertGetId([
                'name' => 'ACHMAD DEVANI RIZQY PRATAMA SETIYAWAN',
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role' => 'siswa',
                'email_verified_at' => now(),
                'nis_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $user = $existingUser->id;
        }

        // Create student record
        DB::table('students')->insert([
            'user_id' => $user,
            'nama_lengkap' => 'ACHMAD DEVANI RIZQY PRATAMA SETIYAWAN',
            'nis' => '20240001',
            'nomor_absen' => 1,
            'kelas' => 'XII RPL',
            'wali_kelas_id' => $waliKelasId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ ACHMAD DEVANI RIZQY PRATAMA SETIYAWAN berhasil ditambahkan!');
    }
}
