<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DEBUG USER & ROLE ===\n\n";

echo "1. Semua User:\n";
$users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
foreach ($users as $u) {
    echo "   ID: " . $u->id . " | Name: " . $u->name . " | Email: " . $u->email . " | Role: " . $u->role . "\n";
}

echo "\n2. User dengan role 'siswa':\n";
$siswa = DB::table('users')->where('role', 'siswa')->select('id', 'name', 'email', 'role')->get();
if ($siswa->count() > 0) {
    foreach ($siswa as $s) {
        echo "   ID: " . $s->id . " | Name: " . $s->name . " | Email: " . $s->email . "\n";
    }
} else {
    echo "   TIDAK ADA USER DENGAN ROLE SISWA\n";
}

echo "\n3. User dengan role 'guru' atau 'guru_bk':\n";
$guru = DB::table('users')->whereIn('role', ['guru', 'guru_bk'])->select('id', 'name', 'email', 'role')->get();
if ($guru->count() > 0) {
    foreach ($guru as $g) {
        echo "   ID: " . $g->id . " | Name: " . $g->name . " | Email: " . $g->email . " | Role: " . $g->role . "\n";
    }
} else {
    echo "   TIDAK ADA USER DENGAN ROLE GURU\n";
}

?>
