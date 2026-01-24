<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel users dan students
        DB::table('students')->delete();
        DB::table('users')->delete();

        // Cek struktur tabel
        $columns = Schema::getColumnListing('users');
        
        // PERBAIKAN: Gunakan role yang konsisten
        // Role di database harus sesuai dengan yang di migration: 
        // 'koordinator_bk', 'guru_bk', 'siswa'
        
        $baseUsers = [
            [
                'name' => 'Koordinator BK',
                'email' => 'bk@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'koordinator_bk',
                'email_verified_at' => now(),
                'nis_verified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guru BK',
                'email' => 'gurubk@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'guru_bk',
                'email_verified_at' => now(),
                'nis_verified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siswa Contoh',
                'email' => 'siswa@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'siswa',
                'email_verified_at' => now(),
                'nis_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wali Kelas XII RPL',
                'email' => 'walikelas@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'wali_kelas',
                'email_verified_at' => now(),
                'nis_verified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Insert data
        DB::table('users')->insert($baseUsers);

        // Ambil user siswa yang baru dibuat
        $siswaUser = DB::table('users')->where('email', 'siswa@gmail.com')->first();

        // Buat student record dengan NIS
        if ($siswaUser) {
            DB::table('students')->insert([
                'user_id' => $siswaUser->id,
                'nama_lengkap' => 'Siswa Contoh',
                'nis' => '12345678',
                'tgl_lahir' => '2007-01-15',
                'alamat' => 'Jl. Contoh No. 123',
                'no_hp' => '081234567890',
                'kelas' => 'XII RPL',
                'nis_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ User data berhasil ditambahkan!');
        $this->command->info('');
        $this->command->info('📌 AKUN KOORDINATOR BK:');
        $this->command->info('   📧 Email: bk@gmail.com');
        $this->command->info('   🔑 Password: 12345678');
        $this->command->info('');
        $this->command->info('📌 AKUN GURU BK:');
        $this->command->info('   📧 Email: gurubk@gmail.com');
        $this->command->info('   🔑 Password: 12345678');
        $this->command->info('');
        $this->command->info('📌 AKUN SISWA:');
        $this->command->info('   📧 Email: siswa@gmail.com');
        $this->command->info('   🔑 Password: 12345678');
        $this->command->info('   📌 NIS: 12345678');
        $this->command->info('');
        $this->command->info('📌 AKUN WALI KELAS:');
        $this->command->info('   📧 Email: walikelas@gmail.com');
        $this->command->info('   🔑 Password: 12345678');
    }
}