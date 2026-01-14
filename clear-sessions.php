<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

echo "=== CLEARING SESSIONS ===\n\n";

// Hapus semua sessions
DB::table('sessions')->truncate();
echo "✓ Semua sessions sudah dihapus\n\n";

// Clear cache
Artisan::call('cache:clear');
echo "✓ Cache cleared\n";

Artisan::call('config:clear');
echo "✓ Config cleared\n";

echo "\nSekarang silakan:\n";
echo "1. Logout dari aplikasi\n";
echo "2. Clear cookies/cache di browser (Ctrl+Shift+Delete)\n";
echo "3. Login kembali dengan akun siswa (siswa@gmail.com / password)\n";

?>
