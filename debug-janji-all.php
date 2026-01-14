<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ALL JANJI KONSELING DATA ===\n\n";

$allJanji = DB::table('janji_konselings')->get();

echo "Total records in database: " . $allJanji->count() . "\n\n";

foreach ($allJanji as $j) {
    echo "ID: " . $j->id . "\n";
    echo "  User ID: " . $j->user_id . "\n";
    echo "  Tanggal: " . $j->tanggal . "\n";
    echo "  Waktu: " . $j->waktu . "\n";
    echo "  Status: " . $j->status . "\n";
    echo "  Is Archived: " . $j->is_archived . "\n";
    echo "  Keluhan: " . substr($j->keluhan, 0, 50) . "...\n";
    echo "  Created At: " . $j->created_at . "\n";
    echo "\n";
}

echo "=== JANJI UNTUK USER ID 10 (SISWA) ===\n\n";

$userJanji = DB::table('janji_konselings')
    ->where('user_id', 10)
    ->get();

echo "Total janji for user 10: " . $userJanji->count() . "\n\n";

foreach ($userJanji as $j) {
    echo "ID: " . $j->id . " | Status: " . $j->status . " | Tanggal: " . $j->tanggal . " | Created: " . $j->created_at . "\n";
}
