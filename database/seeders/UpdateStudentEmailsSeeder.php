<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateStudentEmailsSeeder extends Seeder
{
    public function run(): void
    {
        // Get all students
        $students = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.id', 'students.user_id', 'students.nama_lengkap', 'users.email')
            ->get();

        $updated = 0;

        foreach ($students as $student) {
            // Generate new email from name
            // Remove dots, spaces, and special characters, then lowercase
            $emailName = strtolower(
                preg_replace('/[^a-z0-9]/i', '', $student->nama_lengkap)
            );
            
            $newEmail = $emailName . '@gmail.com';

            // Update user email
            DB::table('users')
                ->where('id', $student->user_id)
                ->update(['email' => $newEmail]);

            $updated++;
            echo "✓ {$student->nama_lengkap} → {$newEmail}\n";
        }

        $this->command->info("\n✅ Total {$updated} email siswa berhasil diubah!");
    }
}
