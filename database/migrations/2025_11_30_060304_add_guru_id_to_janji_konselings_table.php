<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Migration ini sudah tidak diperlukan karena kolom 'guru_id' 
     * sudah ada di migration create_janji_konseling_table
     * Dibiarkan kosong untuk menghindari duplicate column error
     */
    public function up()
    {
        // Kolom guru_id sudah ada di create table, skip
    }

    public function down()
    {
        // Skip
    }
};
