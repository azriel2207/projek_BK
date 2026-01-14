<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\JanjiKonseling;
use App\Models\User;
use Carbon\Carbon;

echo "=== TEST JANJI KONSELING CREATION ===\n\n";

// Find siswa user
$siswaUser = User::where('role', 'siswa')->first();
if (!$siswaUser) {
    echo "ERROR: No siswa user found\n";
    exit;
}

echo "Found siswa user: ID=" . $siswaUser->id . ", Name=" . $siswaUser->name . "\n\n";

// Test 1: Create janji
echo "TEST 1: Creating new janji konseling...\n";
$janji = JanjiKonseling::create([
    'user_id' => $siswaUser->id,
    'tanggal' => Carbon::today()->addDay()->format('Y-m-d'),
    'waktu' => '10:00 - 11:00',
    'keluhan' => 'Test keluhan untuk debugging janji konseling',
    'jenis_bimbingan' => 'pribadi',
    'guru_id' => null,
    'guru_bk' => 'Guru BK',
    'status' => 'menunggu',
    'is_archived' => false
]);

echo "✓ Created janji ID: " . $janji->id . "\n";
echo "  User ID: " . $janji->user_id . "\n";
echo "  Status: " . $janji->status . "\n";
echo "  Is Archived: " . ($janji->is_archived ? 'YES' : 'NO') . "\n\n";

// Test 2: Query janji menunggu
echo "TEST 2: Querying janji with status 'menunggu'...\n";
$janjiMenunggu = JanjiKonseling::where('user_id', $siswaUser->id)
    ->where('status', 'menunggu')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Found " . $janjiMenunggu->count() . " janji with status 'menunggu'\n";
foreach ($janjiMenunggu as $j) {
    echo "  - ID: " . $j->id . ", Tanggal: " . $j->tanggal . ", Waktu: " . $j->waktu . "\n";
}
echo "\n";

// Test 3: Check is_archived field
echo "TEST 3: Checking is_archived filter...\n";
$janjiNotArchived = JanjiKonseling::where('user_id', $siswaUser->id)
    ->where('is_archived', false)
    ->where('status', 'menunggu')
    ->get();

echo "Found " . $janjiNotArchived->count() . " not-archived janji\n";
foreach ($janjiNotArchived as $j) {
    echo "  - ID: " . $j->id . ", Status: " . $j->status . ", Is Archived: " . ($j->is_archived ? 'YES' : 'NO') . "\n";
}
echo "\n";

// Test 4: Check database directly
echo "TEST 4: Checking database record...\n";
$dbRecord = \Illuminate\Support\Facades\DB::table('janji_konselings')
    ->where('id', $janji->id)
    ->first();

if ($dbRecord) {
    echo "✓ Found in database\n";
    echo "  is_archived: " . ($dbRecord->is_archived ?? 'NULL') . "\n";
    echo "  status: " . $dbRecord->status . "\n";
} else {
    echo "✗ NOT found in database!\n";
}
echo "\n";

echo "=== TEST COMPLETE ===\n";
