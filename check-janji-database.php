<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  DATABASE DIAGNOSTIC - JANJI KONSELING                    ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// 1. Check Database Connection
echo "1️⃣  DATABASE CONNECTION\n";
echo "─────────────────────────────\n";
try {
    DB::connection()->getPdo();
    echo "✅ Database connected\n\n";
} catch (\Exception $e) {
    echo "❌ Database connection failed!\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 2. Check janji_konselings table
echo "2️⃣  JANJI_KONSELINGS TABLE\n";
echo "─────────────────────────────\n";

if (!Schema::hasTable('janji_konselings')) {
    echo "❌ Table 'janji_konselings' does NOT exist!\n";
    exit(1);
}

echo "✅ Table exists\n\n";

// Check columns
$columns = Schema::getColumnListing('janji_konselings');
echo "Columns in table:\n";
foreach ($columns as $col) {
    echo "  • $col\n";
}
echo "\n";

// Check required columns
$requiredColumns = ['id', 'user_id', 'tanggal', 'waktu', 'status', 'jenis_bimbingan'];
foreach ($requiredColumns as $col) {
    if (!in_array($col, $columns)) {
        echo "❌ Missing column: $col\n";
    }
}
echo "\n";

// 3. Check current data
echo "3️⃣  CURRENT DATA IN TABLE\n";
echo "─────────────────────────────\n";

$totalCount = DB::table('janji_konselings')->count();
echo "Total records: $totalCount\n";

if ($totalCount > 0) {
    $latest = DB::table('janji_konselings')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get(['id', 'user_id', 'tanggal', 'waktu', 'status', 'created_at']);
    
    echo "\nLatest 5 records:\n";
    echo str_repeat("─", 80) . "\n";
    foreach ($latest as $janji) {
        echo "ID: {$janji->id} | User: {$janji->user_id} | Date: {$janji->tanggal} | Time: {$janji->waktu} | Status: {$janji->status}\n";
        echo "Created: {$janji->created_at}\n";
        echo str_repeat("─", 80) . "\n";
    }
} else {
    echo "⚠️  No records found!\n";
}

echo "\n";

// 4. Check by status
echo "4️⃣  DATA BY STATUS\n";
echo "─────────────────────────────\n";

$statuses = ['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan'];
foreach ($statuses as $status) {
    $count = DB::table('janji_konselings')->where('status', $status)->count();
    echo "Status '$status': $count records\n";
}

echo "\n";

// 5. Check for specific user
echo "5️⃣  CHECK DATA FOR SISWA USER\n";
echo "─────────────────────────────\n";

$siswaUser = DB::table('users')->where('role', 'siswa')->first();
if ($siswaUser) {
    echo "Found siswa user: {$siswaUser->name} (ID: {$siswaUser->id})\n";
    
    $userJanji = DB::table('janji_konselings')
        ->where('user_id', $siswaUser->id)
        ->count();
    
    echo "Janji count for this user: $userJanji\n";
    
    if ($userJanji > 0) {
        $userJanjiData = DB::table('janji_konselings')
            ->where('user_id', $siswaUser->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        echo "\nUser's janji:\n";
        foreach ($userJanjiData as $janji) {
            echo "  • {$janji->tanggal} {$janji->waktu} - Status: {$janji->status}\n";
        }
    }
} else {
    echo "⚠️  No siswa user found\n";
}

echo "\n";

// 6. Check table structure details
echo "6️⃣  TABLE STRUCTURE DETAILS\n";
echo "─────────────────────────────\n";

try {
    $details = DB::select("DESCRIBE janji_konselings");
    foreach ($details as $detail) {
        echo "{$detail->Field}: {$detail->Type} (Null: {$detail->Null})\n";
    }
} catch (\Exception $e) {
    echo "Could not retrieve table details\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "✅ DIAGNOSTIC COMPLETE\n";
echo "═══════════════════════════════════════════════════════════\n\n";
