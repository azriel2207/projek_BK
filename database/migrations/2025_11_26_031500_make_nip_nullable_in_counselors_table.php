<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom nip menjadi nullable
        Schema::table('counselors', function (Blueprint $table) {
            // Perubahan kolom memerlukan doctrine/dbal pada beberapa environment
            $table->string('nip')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Kembalikan menjadi NOT NULL (pastikan tidak ada nilai NULL sebelum menjalankan rollback)
        Schema::table('counselors', function (Blueprint $table) {
            $table->string('nip')->nullable(false)->change();
        });
    }
};