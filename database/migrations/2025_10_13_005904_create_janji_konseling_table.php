<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('janji_konselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('jenis_bimbingan')->default('pribadi');
            $table->string('guru_bk')->nullable();
            $table->date('tanggal');
            $table->string('waktu');
            $table->text('keluhan');
            $table->string('status')->default('menunggu');
            $table->text('catatan_konselor')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('janji_konselings');
    }
};