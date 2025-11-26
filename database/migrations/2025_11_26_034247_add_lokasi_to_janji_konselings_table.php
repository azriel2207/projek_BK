<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('janji_konselings', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('waktu');
        });
    }

    public function down()
    {
        Schema::table('janji_konselings', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });
    }
};