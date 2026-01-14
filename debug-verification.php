<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DEBUG EMAIL VERIFICATION ===\n\n";

$users = DB::table('users')->select('id', 'name', 'email', 'role', 'email_verified_at')->get();
foreach ($users as $u) {
    $verified = $u->email_verified_at ? "✓ VERIFIED" : "✗ NOT VERIFIED";
    echo "ID: " . $u->id . " | Name: " . $u->name . " | Email: " . $u->email . " | Role: " . $u->role . " | " . $verified . "\n";
}

?>
