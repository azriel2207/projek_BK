<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('counselors', function (Blueprint $table) {
            $table->string('specialization')->nullable()->after('no_hp');
            $table->string('office_hours')->nullable()->after('specialization');
        });
    }

    public function down()
    {
        Schema::table('counselors', function (Blueprint $table) {
            $table->dropColumn(['specialization', 'office_hours']);
        });
    }
};