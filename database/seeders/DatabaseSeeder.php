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
        DB::table('users')->delete();

        $users = [
            [
                'name' => 'Koordinator BK',
                'email' => 'bk@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'koordinator_bk',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guru BK',
                'email' => 'gurubk@gmail.com', 
                'password' => Hash::make('123456'),
                'role' => 'guru_bk',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siswa Contoh',
                'email' => 'siswa@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'siswa',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('users')->insert($users);

        $this->command->info('✅ User data berhasil ditambahkan!');
        $this->command->info('📧 Email: bk@gmail.com | 🔑 Password: 123456 (Koordinator BK)');
        $this->command->info('📧 Email: gurubk@gmail.com | 🔑 Password: 123456 (Guru BK)');
        $this->command->info('📧 Email: siswa@gmail.com | 🔑 Password: 123456 (Siswa)');
    }
}