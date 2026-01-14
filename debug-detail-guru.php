<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DEBUG DETAIL CATATAN GURU ===\n\n";

// 1. Semua catatan
echo "1. Semua Catatan di Database:\n";
$catatans = DB::table('catatan')->get();
foreach ($catatans as $c) {
    echo "\n   ID: " . $c->id;
    echo "\n   User ID: " . $c->user_id;
    echo "\n   Janji ID: " . $c->janji_id;
    echo "\n   Tanggal: " . $c->tanggal;
    echo "\n   Guru BK: " . $c->guru_bk;
    echo "\n   Isi: " . substr($c->isi, 0, 50) . "...";
    echo "\n   Created: " . $c->created_at;
    echo "\n---";
}

echo "\n\n2. Catatan dengan User ID 10:\n";
$catatan10 = DB::table('catatan')
    ->where('user_id', 10)
    ->get();

foreach ($catatan10 as $c) {
    echo "\n   ID: " . $c->id;
    echo "\n   Isi: " . $c->isi;
}

echo "\n\n3. Query detail catatan (join dengan users):\n";
$detail = DB::table('catatan')
    ->join('users', 'catatan.user_id', '=', 'users.id')
    ->where('catatan.id', 2)
    ->select('catatan.*', 'users.name as nama_siswa', 'users.email')
    ->first();

if ($detail) {
    echo "   ID: " . $detail->id . "\n";
    echo "   Nama Siswa: " . $detail->nama_siswa . "\n";
    echo "   User ID: " . $detail->user_id . "\n";
    echo "   Janji ID: " . $detail->janji_id . "\n";
    echo "   Isi: " . $detail->isi . "\n";
    echo "   Guru BK: " . $detail->guru_bk . "\n";
} else {
    echo "   Catatan tidak ditemukan\n";
}

?>
