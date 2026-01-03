<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('rodovias', function (Blueprint $table) {
            // Remove o índice único que está travando o seeder
            $table->dropUnique('rodovias_nome_unique');
        });
    }
};
