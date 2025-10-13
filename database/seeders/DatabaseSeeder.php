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
        
        // Data user dasar
        $baseUsers = [
            [
                'email' => 'bk@gmail.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'gurubk@gmail.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'siswa@gmail.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Tambahkan kolom name jika ada
        if (in_array('name', $columns)) {
            $baseUsers[0]['name'] = 'Koordinator BK';
            $baseUsers[1]['name'] = 'Guru BK';
            $baseUsers[2]['name'] = 'Siswa Contoh';
        }
        
        // Tambahkan kolom role jika ada
        if (in_array('role', $columns)) {
            $baseUsers[0]['role'] = 'koordinator_bk';
            $baseUsers[1]['role'] = 'guru_bk';
            $baseUsers[2]['role'] = 'siswa';
        }

        // Insert data
        DB::table('users')->insert($baseUsers);

        $this->command->info('✅ User data berhasil ditambahkan!');
        $this->command->info('📧 Email: bk@gmail.com | 🔑 Password: 123456 (Koordinator BK)');
        $this->command->info('📧 Email: gurubk@gmail.com | 🔑 Password: 123456 (Guru BK)');
        $this->command->info('📧 Email: siswa@gmail.com | 🔑 Password: 123456 (Siswa)');
    }
}