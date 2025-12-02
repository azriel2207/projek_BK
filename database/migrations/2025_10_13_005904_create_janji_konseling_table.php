<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('janji_konselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tanggal');
            $table->string('waktu');
            $table->string('lokasi')->nullable();
            $table->text('keluhan')->nullable();
            $table->enum('jenis_bimbingan', ['pribadi', 'belajar', 'karir', 'sosial'])->default('pribadi');
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->string('guru_bk')->nullable();
            $table->text('catatan_konselor')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('janji_konselings');
    }
};