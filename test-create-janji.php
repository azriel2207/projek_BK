<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JanjiKonseling;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "TEST: CREATING JANJI KONSELING PROGRAMMATICALLY\n";
echo "═══════════════════════════════════════════════════════════\n\n";

// Get siswa user
$siswa = User::where('role', 'siswa')->first();
if (!$siswa) {
    echo "❌ No siswa user found\n";
    exit(1);
}

echo "Siswa user: {$siswa->name} (ID: {$siswa->id})\n\n";

// Create test janji
echo "Creating test janji konseling...\n";

try {
    $janji = JanjiKonseling::create([
        'user_id' => $siswa->id,
        'tanggal' => now()->addDays(1)->format('Y-m-d'),
        'waktu' => '10:00 - 11:00',
        'keluhan' => 'Test keluhan untuk konseling',
        'jenis_bimbingan' => 'pribadi',
        'guru_id' => null,
        'guru_bk' => 'Guru BK',
        'status' => 'menunggu',
        'is_archived' => false
    ]);
    
    echo "✅ Created successfully!\n";
    echo "  ID: {$janji->id}\n";
    echo "  Status: {$janji->status}\n";
    echo "  Tanggal: {$janji->tanggal}\n";
    echo "  Waktu: {$janji->waktu}\n";
    echo "  Created at: {$janji->created_at}\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error creating janji: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

// Verify in database
echo "Verifying in database...\n";
$check = DB::table('janji_konselings')
    ->where('id', $janji->id)
    ->first();

if ($check) {
    echo "✅ Found in database\n";
    echo "  Status in DB: {$check->status}\n";
    echo "  Keluhan: " . substr($check->keluhan, 0, 30) . "...\n";
} else {
    echo "❌ Not found in database!\n";
}

echo "\n";

// Check what index() would return
echo "Checking what would display in siswa view:\n";
$janjiMenunggu = JanjiKonseling::where('user_id', $siswa->id)
    ->where('status', 'menunggu')
    ->get();

echo "Janji with status 'menunggu': {$janjiMenunggu->count()}\n";
foreach ($janjiMenunggu as $j) {
    echo "  • ID {$j->id}: {$j->tanggal} {$j->waktu}\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "✅ TEST COMPLETE\n";
echo "═══════════════════════════════════════════════════════════\n\n";
