<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DEBUG CATATAN UNTUK GURU DETAIL ===\n\n";

// 1. Cek catatan dengan janji_id 5
echo "1. Catatan dengan Janji ID 5:\n";
$catatan = DB::table('catatan')->where('janji_id', 5)->first();
if ($catatan) {
    echo "   ID: " . $catatan->id . "\n";
    echo "   User ID: " . $catatan->user_id . "\n";
    echo "   Janji ID: " . $catatan->janji_id . "\n";
    echo "   Isi: " . substr($catatan->isi, 0, 50) . "...\n";
    echo "   Tanggal: " . $catatan->tanggal . "\n";
} else {
    echo "   TIDAK ADA CATATAN DENGAN JANJI ID 5\n";
}

echo "\n2. Janji dengan ID 5:\n";
$janji = DB::table('janji_konselings')->where('id', 5)->first();
if ($janji) {
    echo "   ID: " . $janji->id . "\n";
    echo "   User ID: " . $janji->user_id . "\n";
    echo "   Status: " . $janji->status . "\n";
    echo "   Keluhan: " . substr($janji->keluhan, 0, 50) . "...\n";
} else {
    echo "   TIDAK ADA JANJI DENGAN ID 5\n";
}

echo "\n3. Test Query - Join Catatan dengan field yang berbeda:\n";
$result = DB::table('catatan')
    ->where('janji_id', 5)
    ->select('*')
    ->first();

if ($result) {
    echo "   Query berhasil\n";
    echo "   Fields yang tersedia: " . implode(', ', array_keys((array) $result)) . "\n";
    foreach ((array) $result as $key => $value) {
        echo "   $key: " . substr($value, 0, 30) . "\n";
    }
} else {
    echo "   Query tidak menemukan hasil\n";
}

?>
