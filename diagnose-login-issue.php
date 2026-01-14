<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n======================================\n";
echo "DIAGNOSTIK SESSION & DATABASE\n";
echo "======================================\n\n";

// 1. Check database connection
echo "1. DATABASE CONNECTION\n";
echo "----------------------\n";
try {
    DB::connection()->getPdo();
    echo "✅ Database connected\n\n";
} catch (\Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 2. Check sessions table
echo "2. SESSIONS TABLE\n";
echo "-----------------\n";
if (Schema::hasTable('sessions')) {
    echo "✅ Table 'sessions' exists\n";
    
    $columns = Schema::getColumnListing('sessions');
    echo "Columns: " . implode(", ", $columns) . "\n";
    
    $count = DB::table('sessions')->count();
    echo "Records: $count\n\n";
} else {
    echo "❌ Table 'sessions' does NOT exist\n";
    echo "⚠️  Need to create sessions table\n";
    echo "Run: php artisan session:table && php artisan migrate\n\n";
}

// 3. Check cache table
echo "3. CACHE TABLE\n";
echo "---------------\n";
if (Schema::hasTable('cache')) {
    echo "✅ Table 'cache' exists\n";
    $count = DB::table('cache')->count();
    echo "Records: $count\n\n";
} else {
    echo "❌ Table 'cache' does NOT exist\n\n";
}

// 4. Check users table
echo "4. USERS TABLE\n";
echo "---------------\n";
if (Schema::hasTable('users')) {
    echo "✅ Table 'users' exists\n";
    
    $users = DB::table('users')->count();
    echo "Total users: $users\n";
    
    $siswa = DB::table('users')->where('role', 'siswa')->count();
    $guru = DB::table('users')->whereIn('role', ['guru_bk', 'guru'])->count();
    $koordinator = DB::table('users')->whereIn('role', ['koordinator_bk', 'koordinator'])->count();
    
    echo "  - Siswa: $siswa\n";
    echo "  - Guru BK: $guru\n";
    echo "  - Koordinator: $koordinator\n\n";
} else {
    echo "❌ Table 'users' does NOT exist\n\n";
}

// 5. Check app key
echo "5. APP KEY\n";
echo "-----------\n";
$appKey = config('app.key');
if ($appKey) {
    echo "✅ APP_KEY is set\n";
    echo "Key: " . substr($appKey, 0, 20) . "...\n\n";
} else {
    echo "❌ APP_KEY is NOT set\n";
    echo "⚠️  Run: php artisan key:generate\n\n";
}

// 6. Check CSRF configuration
echo "6. CSRF CONFIGURATION\n";
echo "---------------------\n";
echo "Session driver: " . config('session.driver') . "\n";
echo "Session lifetime: " . config('session.lifetime') . " minutes\n";
echo "Session path: " . config('session.path') . "\n";
echo "Session domain: " . config('session.domain') . "\n";
echo "Session encrypt: " . (config('session.encrypt') ? 'true' : 'false') . "\n\n";

echo "======================================\n";
echo "✅ DIAGNOSTIK COMPLETE\n";
echo "======================================\n\n";
