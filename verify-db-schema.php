<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n======================================\n";
echo "DATABASE SCHEMA VERIFICATION\n";
echo "======================================\n\n";

// Check janji_konselings table
echo "TABLE: janji_konselings\n";
echo "------------------------\n\n";

if (Schema::hasTable('janji_konselings')) {
    echo "✓ Table exists\n\n";
    
    $columns = Schema::getColumnListing('janji_konselings');
    echo "Columns found:\n";
    foreach ($columns as $column) {
        echo "  • $column\n";
    }
    
    echo "\n";
    
    // Check for new columns
    if (Schema::hasColumn('janji_konselings', 'is_archived')) {
        echo "✅ Column 'is_archived' exists\n";
    } else {
        echo "❌ Column 'is_archived' MISSING\n";
    }
    
    if (Schema::hasColumn('janji_konselings', 'archived_at')) {
        echo "✅ Column 'archived_at' exists\n";
    } else {
        echo "❌ Column 'archived_at' MISSING\n";
    }
    
    echo "\n";
    
    // Show table structure
    echo "Column Details:\n";
    $details = DB::select("DESCRIBE janji_konselings");
    foreach ($details as $detail) {
        if (in_array($detail->Field, ['is_archived', 'archived_at', 'status', 'user_id', 'tanggal'])) {
            echo "  {$detail->Field}: {$detail->Type} (Null: {$detail->Null})\n";
        }
    }
    
    echo "\n";
    
} else {
    echo "❌ Table does not exist\n";
}

echo "======================================\n";
echo "✅ VERIFICATION COMPLETE\n";
echo "======================================\n\n";
