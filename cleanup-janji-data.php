<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "CLEANING OLD DATA\n";
echo "═══════════════════════════════════════════════════════════\n\n";

// Delete dibatalkan data
$deleted = DB::table('janji_konselings')
    ->where('status', 'dibatalkan')
    ->delete();

echo "✅ Deleted $deleted dibatalkan records\n\n";

// Check remaining data
$remaining = DB::table('janji_konselings')->count();
echo "Remaining records: $remaining\n";

$data = DB::table('janji_konselings')
    ->orderBy('created_at', 'desc')
    ->get(['id', 'user_id', 'tanggal', 'waktu', 'status']);

foreach ($data as $janji) {
    echo "  • ID {$janji->id}: {$janji->tanggal} {$janji->waktu} - Status: {$janji->status}\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "✅ CLEAN UP COMPLETE\n";
echo "═══════════════════════════════════════════════════════════\n\n";
echo "Sekarang coba buat janji konseling baru di web!\n";
