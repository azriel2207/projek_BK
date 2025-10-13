<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom name jika belum ada
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id');
            }
            
            // Tambah kolom role jika belum ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['koordinator_bk', 'guru_bk', 'siswa'])->default('siswa')->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Optional: Hapus kolom jika rollback
            // $table->dropColumn(['name', 'role']);
        });
    }
};