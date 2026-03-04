<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterStudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_registration_assigns_wali_kelas_by_class()
    {
        // buat wali kelas untuk kelas tertentu
        $wali = User::create([
            'name' => 'Wali X-A',
            'email' => 'wali@school.test',
            'password' => bcrypt('password'),
            'role' => 'wali_kelas',
            'class' => 'X-A',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/register', [
            'name' => 'Siswa Baru',
            'email' => 'siswa@school.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'nis' => '12345',
            'kelas' => 'X-A',
            'role' => 'siswa',
        ]);

        $response->assertRedirect();
        $student = Student::where('nis', '12345')->first();
        $this->assertNotNull($student);
        $this->assertEquals($wali->id, $student->wali_kelas_id);
    }
}
