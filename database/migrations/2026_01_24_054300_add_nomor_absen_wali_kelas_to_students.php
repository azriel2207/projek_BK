<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Tambah kolom nomor_absen
            if (!Schema::hasColumn('students', 'nomor_absen')) {
                $table->integer('nomor_absen')->nullable()->after('kelas');
            }
            
            // Tambah kolom wali_kelas_id
            if (!Schema::hasColumn('students', 'wali_kelas_id')) {
                $table->unsignedBigInteger('wali_kelas_id')->nullable()->after('nomor_absen');
                $table->foreign('wali_kelas_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop foreign key if exists
            if (Schema::hasColumn('students', 'wali_kelas_id')) {
                $table->dropForeign(['wali_kelas_id']);
                $table->dropColumn('wali_kelas_id');
            }
            
            if (Schema::hasColumn('students', 'nomor_absen')) {
                $table->dropColumn('nomor_absen');
            }
        });
    }
};
