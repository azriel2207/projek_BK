<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n=== CHECKING SESSIONS TABLE ===\n\n";

// Check if sessions table exists
if (Schema::hasTable('sessions')) {
    echo "✅ Sessions table EXISTS\n\n";
    
    $columns = Schema::getColumnListing('sessions');
    echo "Columns:\n";
    foreach ($columns as $col) {
        echo "  - $col\n";
    }
    
    echo "\n";
    
    $count = DB::table('sessions')->count();
    echo "Current sessions: $count\n";
    
} else {
    echo "❌ Sessions table NOT FOUND\n";
    echo "\nNeed to create it. Run these commands:\n";
    echo "  1. php artisan session:table\n";
    echo "  2. php artisan migrate\n";
}

echo "\n";
