<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trechos', function (Blueprint $table) {
            // Adiciona o campo tipo (ex: 'B' para Trecho Principal)
            $table->string('tipo', 1)->default('B')->after('rodovia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trechos', function (Blueprint $table) {
            //
        });
    }
};
