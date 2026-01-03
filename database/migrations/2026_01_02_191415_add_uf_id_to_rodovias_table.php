<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {

    public function up(): void
    {
        Schema::table('rodovias', function (Blueprint $table) {
            // Adiciona a coluna uf_id como chave estrangeira
            $table->foreignId('uf_id')->after('nome')->nullable()->constrained('ufs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('rodovias', function (Blueprint $table) {
            $table->dropForeign(['uf_id']);
            $table->dropColumn('uf_id');
        });
    }
};
