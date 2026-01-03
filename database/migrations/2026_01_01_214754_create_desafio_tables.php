<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // Tabela de UFs
    Schema::create('ufs', function (Blueprint $table) {
        $table->id();
        $table->string('sigla', 2)->unique();
        $table->timestamps();
    });

    // Tabela de Rodovias
    Schema::create('rodovias', function (Blueprint $table) {
        $table->id();
        $table->string('nome')->unique();
        $table->timestamps();
    });

    // Tabela Principal de Trechos
    Schema::create('trechos', function (Blueprint $table) {
        $table->id();
        $table->date('data_referencia');
        $table->foreignId('uf_id')->constrained('ufs');
        $table->foreignId('rodovia_id')->constrained('rodovias');
        $table->decimal('km_inicial', 8, 2);
        $table->decimal('km_final', 8, 2);
        $table->json('geo')->nullable(); // Para o GeoJSON do DNIT
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desafio_tables');
    }
};
