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
        // Tabel untuk menyimpan riwayat/perilaku siswa
        Schema::create('student_behaviors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('recorded_by'); // Guru BK
            $table->string('kategori'); // akademik, perilaku, kesehatan, sosial, dll
            $table->text('deskripsi');
            $table->date('tanggal_kejadian')->nullable();
            $table->string('status')->default('aktif'); // aktif, resolved, monitoring
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('restrict');
        });

        // Tabel untuk menyimpan catatan identitas siswa yang lebih detail
        Schema::create('student_identities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->unique();
            $table->string('no_induk')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->text('catatan_khusus')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        // Tabel untuk menyimpan catatan dari wali kelas
        Schema::create('wali_kelas_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('wali_kelas_id');
            $table->text('catatan');
            $table->date('tanggal_catatan');
            $table->string('tipe_catatan'); // perkembangan, prestasi, masalah, dll
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('wali_kelas_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_kelas_notes');
        Schema::dropIfExists('student_identities');
        Schema::dropIfExists('student_behaviors');
    }
};
