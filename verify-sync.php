<?php

// Script untuk test sinkronisasi manual
// Jalankan: php artisan tinker < verify-sync.php

use App\Models\User;
use App\Models\Counselor;

echo "=== Testing Sinkronisasi Data Koordinator BK dan Guru BK ===\n\n";

// Test 1: Create Test Guru
echo "1. Creating test guru BK...\n";
$user = User::firstOrCreate(
    ['email' => 'testguru@example.com'],
    [
        'name' => 'Test Guru Sync',
        'password' => bcrypt('password123'),
        'role' => 'guru_bk',
        'phone' => '081234567890'
    ]
);
echo "User created/updated: ID={$user->id}, Name={$user->name}, Phone={$user->phone}\n\n";

// Test 2: Create or Update Counselor
echo "2. Creating counselor record...\n";
$counselor = Counselor::firstOrCreate(
    ['user_id' => $user->id],
    [
        'nama_lengkap' => 'Test Guru Sync',
        'nip' => 'NIP000' . $user->id,
        'no_hp' => '081234567890',
        'email' => 'testguru@example.com',
        'specialization' => 'Bimbingan Akademik'
    ]
);
echo "Counselor created/updated: ID={$counselor->id}, Name={$counselor->nama_lengkap}, Phone={$counselor->no_hp}\n\n";

// Test 3: Update User - Check if Counselor syncs
echo "3. Updating User name and phone...\n";
$user->update([
    'name' => 'Updated Guru Name',
    'phone' => '082345678901'
]);
echo "User updated: Name={$user->name}, Phone={$user->phone}\n";

// Refresh counselor dari DB
$counselor->refresh();
echo "Counselor after User update: Name={$counselor->nama_lengkap}, Phone={$counselor->no_hp}\n";

if ($counselor->nama_lengkap === 'Updated Guru Name' && $counselor->no_hp === '082345678901') {
    echo "✓ SUCCESS: User → Counselor Sync Works!\n\n";
} else {
    echo "✗ FAILED: User → Counselor Sync NOT Working!\n\n";
}

// Test 4: Update Counselor - Check if User syncs
echo "4. Updating Counselor name and phone via update...\n";
$counselor->update([
    'nama_lengkap' => 'Another Updated Name',
    'no_hp' => '083456789012'
]);
echo "Counselor updated: Name={$counselor->nama_lengkap}, Phone={$counselor->no_hp}\n";

// Refresh user dari DB
$user->refresh();
echo "User after Counselor update: Name={$user->name}, Phone={$user->phone}\n";

if ($user->name === 'Another Updated Name' && $user->phone === '083456789012') {
    echo "✓ SUCCESS: Counselor → User Sync Works!\n\n";
} else {
    echo "✗ FAILED: Counselor → User Sync NOT Working!\n\n";
}

echo "=== Test Summary ===\n";
echo "Both directions of synchronization should work properly.\n";
echo "Check storage/logs/laravel.log for sync logs.\n";
