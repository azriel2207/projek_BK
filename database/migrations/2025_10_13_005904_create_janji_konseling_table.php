<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('janji_konselings', function (Blueprint $table) {
            if (! Schema::hasColumn('janji_konselings', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('waktu');
            }
            if (! Schema::hasColumn('janji_konselings', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('lokasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('janji_konselings', function (Blueprint $table) {
            if (Schema::hasColumn('janji_konselings', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            if (Schema::hasColumn('janji_konselings', 'lokasi')) {
                $table->dropColumn('lokasi');
            }
        });
    }
};