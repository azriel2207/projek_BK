<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum role untuk menambahkan wali_kelas
        Schema::table('users', function (Blueprint $table) {
            // Tidak bisa langsung mengubah enum di SQLite, jadi skip jika SQLite
            if (DB::getDriverName() !== 'sqlite') {
                $table->string('role')->change();
            }
        });

        // Tambahkan kolom NIS verification di users table
        if (!Schema::hasColumn('users', 'nis_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('nis_verified')->default(false)->after('role')->nullable();
            });
        }

        // Tambahkan kolom untuk tracking wali kelas di students table
        if (!Schema::hasColumn('students', 'wali_kelas_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->unsignedBigInteger('wali_kelas_id')->nullable()->after('kelas');
                $table->foreign('wali_kelas_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'wali_kelas_id')) {
                $table->dropForeign(['wali_kelas_id']);
                $table->dropColumn('wali_kelas_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nis_verified')) {
                $table->dropColumn('nis_verified');
            }
        });
    }
};
