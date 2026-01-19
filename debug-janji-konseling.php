<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get authenticated user from session - ini tidak akan work karena CLI
// Jadi kita check semua janji

echo "=== DEBUG JANJI KONSELING ===\n\n";

// 1. Check total janji
$totalJanji = DB::table('janji_konselings')->count();
echo "Total janji di database: $totalJanji\n\n";

// 2. Check janji per status
$byStatus = DB::table('janji_konselings')
    ->select('status', DB::raw('COUNT(*) as count'))
    ->groupBy('status')
    ->get();

echo "Janji per status:\n";
foreach ($byStatus as $item) {
    echo "  - {$item->status}: {$item->count}\n";
}

echo "\n";

// 3. Check janji terbaru
$recentJanji = DB::table('janji_konselings')
    ->join('users', 'janji_konselings.user_id', '=', 'users.id')
    ->select('janji_konselings.*', 'users.name', 'users.email', 'users.id as user_id_check')
    ->orderBy('janji_konselings.created_at', 'desc')
    ->limit(10)
    ->get();

echo "10 Janji Terbaru:\n";
foreach ($recentJanji as $janji) {
    echo "  ID: {$janji->id}\n";
    echo "  User: {$janji->name} ({$janji->email}) - ID: {$janji->user_id}\n";
    echo "  Tanggal: {$janji->tanggal} | Waktu: {$janji->waktu}\n";
    echo "  Status: {$janji->status}\n";
    echo "  Jenis: {$janji->jenis_bimbingan}\n";
    echo "  Created: {$janji->created_at}\n";
    echo "  ---\n";
}

echo "\n=== END DEBUG ===\n";
