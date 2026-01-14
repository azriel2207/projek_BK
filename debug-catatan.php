<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DEBUG CATATAN KONSELING ===\n\n";

// 1. Check kolom di tabel catatan
echo "1. Struktur Tabel Catatan:\n";
$columns = DB::select("DESCRIBE catatan");
foreach ($columns as $col) {
    echo "   - " . $col->Field . " (" . $col->Type . ")\n";
}

echo "\n2. Semua Data di Tabel Catatan:\n";
$allCatatan = DB::table('catatan')->get();
if ($allCatatan->count() > 0) {
    foreach ($allCatatan as $c) {
        echo "\n   ID: " . $c->id;
        echo "\n   User ID: " . $c->user_id;
        echo "\n   Janji ID: " . (isset($c->janji_id) ? $c->janji_id : "TIDAK ADA");
        echo "\n   Tanggal: " . $c->tanggal;
        echo "\n   Isi: " . substr($c->isi, 0, 50) . "...";
        echo "\n   Guru BK: " . $c->guru_bk;
        echo "\n   Created: " . $c->created_at;
        echo "\n---\n";
    }
} else {
    echo "   TIDAK ADA DATA CATATAN\n";
}

echo "\n3. Janji Konseling dengan Status Selesai:\n";
$janjiSelesai = DB::table('janji_konselings')
    ->where('status', 'selesai')
    ->get();

if ($janjiSelesai->count() > 0) {
    foreach ($janjiSelesai as $j) {
        echo "\n   ID: " . $j->id;
        echo "\n   User ID: " . $j->user_id;
        echo "\n   Status: " . $j->status;
        echo "\n   Tanggal: " . $j->tanggal;
        
        // Cek apakah ada catatan untuk janji ini
        $catatan = DB::table('catatan')->where('janji_id', $j->id)->first();
        if ($catatan) {
            echo "\n   ✓ Ada Catatan (ID: " . $catatan->id . ")";
        } else {
            echo "\n   ✗ TIDAK ADA CATATAN";
        }
        echo "\n---\n";
    }
} else {
    echo "   TIDAK ADA JANJI DENGAN STATUS SELESAI\n";
}

echo "\n4. Test Query (Join Janji + Catatan untuk User ID 10):\n";
$result = DB::table('janji_konselings')
    ->leftJoin('catatan', 'janji_konselings.id', '=', 'catatan.janji_id')
    ->where('janji_konselings.user_id', 10)
    ->select('janji_konselings.*', 'catatan.isi as catatan_konselor')
    ->first();

if ($result) {
    echo "   Query Result:\n";
    echo "   - Janji ID: " . $result->id . "\n";
    echo "   - Status: " . $result->status . "\n";
    echo "   - Catatan: " . ($result->catatan_konselor ? $result->catatan_konselor : "NULL") . "\n";
} else {
    echo "   Query tidak menemukan hasil\n";
}

?>
