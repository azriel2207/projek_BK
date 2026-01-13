<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== ALL USERS IN DATABASE ===\n";
$users = User::all(['id', 'name', 'email', 'role']);
foreach ($users as $user) {
    echo "ID: {$user->id} | Email: {$user->email} | Name: {$user->name} | Role: {$user->role}\n";
}
echo "\n=== TOTAL: " . $users->count() . " users ===\n";
