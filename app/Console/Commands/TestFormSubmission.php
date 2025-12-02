<?php
// Test: Verify form submission data

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\User;

class TestFormSubmission extends Command
{
    protected $signature = 'test:form-submission {userId=4} {newKelas="XII TKJ"}';
    protected $description = 'Simulate form submission to test updateSiswa';

    public function handle()
    {
        $userId = $this->argument('userId');
        $newKelas = $this->argument('newKelas');
        
        $this->info("=== Simulating Form Submission ===");
        $this->info("User ID: $userId");
        $this->info("New Kelas: $newKelas");
        
        // Get current state
        $this->line("\n[BEFORE]");
        $student = Student::where('user_id', $userId)->first();
        if ($student) {
            $this->line("Student ID: " . $student->id);
            $this->line("Current Kelas: " . ($student->kelas ?? 'NULL'));
            $this->line("Updated at: " . $student->updated_at);
        } else {
            $this->error("Student not found!");
            return;
        }
        
        // Simulate the update from updateSiswa()
        $this->line("\n[UPDATING]");
        $updateResult = $student->update([
            'kelas' => $newKelas,
        ]);
        $this->line("Update result: " . ($updateResult ? 'TRUE' : 'FALSE'));
        
        // Check if data is actually in DB
        $this->line("\n[AFTER - Fresh Query]");
        $fresh = Student::find($student->id);
        $this->line("Fresh query kelas: " . ($fresh->kelas ?? 'NULL'));
        $this->line("Updated at: " . $fresh->updated_at);
        
        // Also check via raw DB
        $this->line("\n[RAW DB CHECK]");
        $raw = DB::table('students')->where('id', $student->id)->first();
        if ($raw) {
            $this->line("Raw DB kelas: " . ($raw->kelas ?? 'NULL'));
        }
        
        $this->info("\n=== End Test ===");
    }
}
