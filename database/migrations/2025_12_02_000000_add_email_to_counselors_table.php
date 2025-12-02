<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            // Add email column jika belum ada
            if (!Schema::hasColumn('counselors', 'email')) {
                $table->string('email')->nullable()->unique()->after('no_hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            if (Schema::hasColumn('counselors', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
