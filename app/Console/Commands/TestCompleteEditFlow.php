<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestCompleteEditFlow extends Command
{
    protected $signature = 'test:complete-edit-flow {userId=4}';
    protected $description = 'Test complete flow: edit form → submit → list view';

    public function handle()
    {
        $userId = $this->argument('userId');
        
        $this->info("=== Complete Edit Flow Test ===");
        $this->info("User ID: $userId (Siswa: Azriel)");
        
        // Step 1: Load edit form (like editSiswa)
        $this->line("\n[STEP 1] Load edit form");
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
            $this->line("✓ Form loaded, current kelas: " . ($siswa->kelas ?? 'NULL'));
        } else {
            $this->error("✗ Siswa not found!");
            return;
        }
        
        // Step 2: Submit form (like updateSiswa)
        $this->line("\n[STEP 2] Submit form with new kelas");
        $user = User::findOrFail($userId);
        $newKelas = "XII PEMASARAN DAGANG";
        
        // Update user (phone)
        $user->update(['phone' => '081234567890']);
        $this->line("✓ User updated: phone = " . $user->phone);
        
        // Update student
        $studentRecord = Student::where('user_id', $userId)->first();
        if ($studentRecord) {
            $studentRecord->update([
                'kelas' => $newKelas,
                'nama_lengkap' => 'azriel updated',
                'alamat' => 'new address',
            ]);
            $this->line("✓ Student updated: kelas = " . $studentRecord->kelas);
        }
        
        // Step 3: Verify in daftarSiswa list view
        $this->line("\n[STEP 3] Verify daftarSiswa list view");
        $listQuery = DB::table('users')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('users.role', 'siswa')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.class',
                'students.id as student_id',
                'students.nis',
                'students.nama_lengkap',
                'students.kelas',
                'students.tgl_lahir',
                'students.alamat',
                'students.no_hp',
                'users.created_at'
            )
            ->get();
        
        foreach ($listQuery as $item) {
            if ($item->id == $userId) {
                $this->line("Found in list view:");
                $this->line("  Name: " . $item->name);
                $this->line("  Kelas: " . ($item->kelas ?? 'NULL/UNDEFINED'));
                $this->line("  Email: " . $item->email);
                $this->line("  Phone: " . $item->phone);
                
                if ($item->kelas == $newKelas) {
                    $this->info("\n✓ SUCCESS: kelas updated and visible in list!");
                } else {
                    $this->warn("\n⚠ WARNING: kelas in list is '" . ($item->kelas ?? 'NULL') . "', expected '" . $newKelas . "'");
                }
            }
        }
        
        $this->info("\n=== End Test ===");
    }
}
