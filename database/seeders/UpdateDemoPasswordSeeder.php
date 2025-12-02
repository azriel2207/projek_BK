<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateDemoPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini untuk update password akun demo agar sesuai dengan validasi (min 8 karakter)
     * Password baru: Password123
     */
    public function run(): void
    {
        // Update password untuk akun-akun demo
        $accounts = [
            'bk@gmail.com',
            'gurubk@gmail.com',
            'siswa@gmail.com',
        ];

        $newPassword = Hash::make('12345678');

        DB::table('users')
            ->whereIn('email', $accounts)
            ->update(['password' => $newPassword]);

        $this->command->info('✅ Password akun demo berhasil diupdate!');
        $this->command->info('📧 Email: bk@gmail.com | 🔑 Password: 12345678 (Koordinator BK)');
        $this->command->info('📧 Email: gurubk@gmail.com | 🔑 Password: 12345678 (Guru BK)');
        $this->command->info('📧 Email: siswa@gmail.com | 🔑 Password: 12345678 (Siswa)');
    }
}
