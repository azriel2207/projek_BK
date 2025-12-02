<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Counselor;
use Tests\TestCase;

class SyncDataTest extends TestCase
{
    public function test_user_update_syncs_to_counselor()
    {
        // Create user dengan role guru_bk
        $user = User::create([
            'name' => 'Test Guru',
            'email' => 'testguru@example.com',
            'password' => bcrypt('password'),
            'role' => 'guru_bk',
            'phone' => '081234567890'
        ]);

        // Create counselor
        $counselor = Counselor::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Test Guru',
            'nip' => 'NIP000001',
            'no_hp' => '081234567890',
        ]);

        // Update user
        $user->update([
            'name' => 'Updated Guru',
            'phone' => '082345678901',
            'email' => 'updated@example.com'
        ]);

        // Refresh counselor dari database
        $counselor->refresh();

        // Assert: Counselor harus ter-update
        $this->assertEquals('Updated Guru', $counselor->nama_lengkap);
        $this->assertEquals('082345678901', $counselor->no_hp);
        $this->assertEquals('updated@example.com', $counselor->email);
    }

    public function test_counselor_update_syncs_to_user()
    {
        // Create user
        $user = User::create([
            'name' => 'Test Guru',
            'email' => 'testguru@example.com',
            'password' => bcrypt('password'),
            'role' => 'guru_bk',
            'phone' => '081234567890'
        ]);

        // Create counselor
        $counselor = Counselor::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Test Guru',
            'nip' => 'NIP000001',
            'no_hp' => '081234567890',
        ]);

        // Update counselor
        $counselor->update([
            'nama_lengkap' => 'Updated Guru',
            'no_hp' => '082345678901',
        ]);

        // Refresh user dari database
        $user->refresh();

        // Assert: User harus ter-update
        $this->assertEquals('Updated Guru', $user->name);
        $this->assertEquals('082345678901', $user->phone);
    }
}
