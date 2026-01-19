<?php
// DEBUG: Check janji selesai vs catatan
// 
// Jalankan di browser: http://localhost:8000/guru/debug-riwayat
// atau di terminal: php artisan tinker

echo "=== DEBUG RIWAYAT KONSELING ===\n\n";

// Test 1: Check janji yang selesai
echo "TEST 1: Janji dengan status 'selesai'\n";
$janjiSelesai = \DB::table('janji_konselings')
    ->where('status', 'selesai')
    ->latest()
    ->get();
echo "Total: " . $janjiSelesai->count() . "\n";
foreach ($janjiSelesai as $j) {
    echo "  - ID: {$j->id}, User: {$j->user_id}, Guru: {$j->guru_bk}, Status: {$j->status}\n";
}
echo "\n";

// Test 2: Check catatan di database
echo "TEST 2: Catatan di database\n";
$catatan = \DB::table('catatan')->latest()->limit(10)->get();
echo "Total: " . $catatan->count() . "\n";
foreach ($catatan as $c) {
    echo "  - ID: {$c->id}, Janji ID: {$c->janji_id}, User: {$c->user_id}, Guru: {$c->guru_bk}\n";
}
echo "\n";

// Test 3: Janji selesai tapi TANPA catatan
echo "TEST 3: Janji SELESAI tapi TIDAK ADA CATATAN\n";
$janjiTanpaCatatan = \DB::table('janji_konselings as j')
    ->leftJoin('catatan as c', 'j.id', '=', 'c.janji_id')
    ->where('j.status', 'selesai')
    ->whereNull('c.id')
    ->select('j.*')
    ->distinct()
    ->get();
echo "Total: " . $janjiTanpaCatatan->count() . "\n";
foreach ($janjiTanpaCatatan as $j) {
    echo "  - ID: {$j->id}, User: {$j->user_id}, Guru: {$j->guru_bk}, Status: {$j->status}\n";
}
echo "\n";

echo "=== END ===\n";
