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
        // Kosongkan tabel users
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
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guru BK',
                'email' => 'gurubk@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'guru_bk',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siswa Contoh',
                'email' => 'siswa@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'siswa',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Insert data
        DB::table('users')->insert($baseUsers);

        $this->command->info('✅ User data berhasil ditambahkan!');
        $this->command->info('📧 Email: bk@gmail.com | 🔑 Password: 12345678 (Koordinator BK)');
        $this->command->info('📧 Email: gurubk@gmail.com | 🔑 Password: 12345678 (Guru BK)');
        $this->command->info('📧 Email: siswa@gmail.com | 🔑 Password: 12345678 (Siswa)');
    }
}