<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\JanjiKonseling;
use App\Models\User;

// Simulate auth user (siswa)
$siswa = User::where('role', 'siswa')->first();
if (!$siswa) {
    echo "No siswa user found\n";
    exit;
}

echo "=== DEBUG JANJI DISPLAY ===\n\n";
echo "Siswa ID: " . $siswa->id . "\n";
echo "Siswa Name: " . $siswa->name . "\n\n";

// Test 1: Get ALL janji for siswa
echo "TEST 1: All janji for siswa\n";
$allJanji = JanjiKonseling::where('user_id', $siswa->id)->get();
echo "Total: " . $allJanji->count() . "\n";
foreach ($allJanji as $j) {
    echo "  - ID: {$j->id}, Status: {$j->status}, Tanggal: {$j->tanggal}, Created: {$j->created_at}\n";
}
echo "\n";

// Test 2: Get MENUNGGU janji
echo "TEST 2: Janji MENUNGGU for siswa\n";
$janjiMenunggu = JanjiKonseling::where('user_id', $siswa->id)
    ->where('status', 'menunggu')
    ->orderBy('created_at', 'desc')
    ->get();
echo "Total: " . $janjiMenunggu->count() . "\n";
foreach ($janjiMenunggu as $j) {
    echo "  - ID: {$j->id}, Status: {$j->status}, Tanggal: {$j->tanggal}, Created: {$j->created_at}\n";
}
echo "\n";

// Test 3: Get DIKONFIRMASI janji
echo "TEST 3: Janji DIKONFIRMASI for siswa\n";
$janjiKonfirmasi = JanjiKonseling::where('user_id', $siswa->id)
    ->where('status', 'dikonfirmasi')
    ->where('tanggal', '>=', now()->format('Y-m-d'))
    ->orderBy('tanggal', 'desc')
    ->get();
echo "Total: " . $janjiKonfirmasi->count() . "\n";
foreach ($janjiKonfirmasi as $j) {
    echo "  - ID: {$j->id}, Status: {$j->status}, Tanggal: {$j->tanggal}, Created: {$j->created_at}\n";
}
echo "\n";

// Test 4: Check database directly
echo "TEST 4: Raw database query\n";
$rawJanji = DB::table('janji_konselings')
    ->where('user_id', $siswa->id)
    ->get();
echo "Total: " . $rawJanji->count() . "\n";
foreach ($rawJanji as $j) {
    echo "  - ID: {$j->id}, Status: {$j->status}, User ID: {$j->user_id}, Tanggal: {$j->tanggal}\n";
}

echo "\n=== END ===\n";
