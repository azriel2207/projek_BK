<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TestEditSiswaQuery extends Command
{
    protected $signature = 'test:edit-siswa-query {userId=4}';
    protected $description = 'Test the editSiswa query to verify data structure';

    public function handle()
    {
        $userId = $this->argument('userId');
        
        $this->info("=== Testing editSiswa Query ===");
        $this->info("User ID: $userId");
        
        // Test 1: The actual query from editSiswa()
        $this->line("\n[1] DB::table query (used in editSiswa):");
        $siswa = DB::table('users')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('users.id', $userId)
            ->where('users.role', 'siswa')
            ->select(
                'users.*',
                'students.id as student_id',
                'students.nis',
                'students.nama_lengkap',
                'students.kelas',
                'students.tgl_lahir',
                'students.alamat',
                'students.no_hp'
            )
            ->first();
        
        if ($siswa) {
            $this->line("Type: " . get_class($siswa));
            $this->line("Properties:");
            foreach ((array)$siswa as $key => $value) {
                $this->line("  - $key: " . ($value ? $value : 'NULL'));
            }
            $this->line("\nAccess check:");
            $this->line("  \$siswa->kelas: " . ($siswa->kelas ?? 'UNDEFINED/NULL'));
            $this->line("  \$siswa->id: " . ($siswa->id ?? 'UNDEFINED'));
            $this->line("  \$siswa->student_id: " . ($siswa->student_id ?? 'UNDEFINED/NULL'));
        } else {
            $this->error("No result from DB::table query!");
        }
        
        // Test 2: Eloquent approach
        $this->line("\n[2] Eloquent User model:");
        $user = User::with('student')->find($userId);
        if ($user) {
            $this->line("User: " . $user->name);
            if ($user->student) {
                $this->line("Student exists: YES");
                $this->line("Student kelas: " . ($user->student->kelas ?? 'NULL'));
                $this->line("Student updated_at: " . $user->student->updated_at);
            } else {
                $this->line("Student exists: NO");
            }
        }
        
        // Test 3: Direct Student query
        $this->line("\n[3] Direct Student query:");
        $student = Student::where('user_id', $userId)->first();
        if ($student) {
            $this->line("Student ID: " . $student->id);
            $this->line("Student kelas: " . ($student->kelas ?? 'NULL'));
            $this->line("Student updated_at: " . $student->updated_at);
        } else {
            $this->line("No student record found!");
        }
        
        $this->info("\n=== End Test ===");
    }
}
