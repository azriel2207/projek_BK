<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained()->onDelete('cascade');
            $table->datetime('jadwal');
            $table->string('topik', 200);
            $table->enum('status', ['dijadwalkan', 'selesai', 'batal', 'menunggu_konfirmasi', 'jadwal_ulang']);
            $table->text('alasan_batal')->nullable();
            $table->datetime('jadwal_ulang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_sessions');
    }
};