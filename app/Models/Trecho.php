<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trecho extends Model
{
    use HasFactory;

    // Permite preenchimento em massa (importante para o formulário)
    protected $fillable = [
        'data_referencia', 
        'uf_id', 
        'rodovia_id', 
        'km_inicial', 
        'km_final', 
        'geo'
    ];

    // Converte o JSON do banco automaticamente em array/objeto para o PHP
    protected $casts = [
        'geo' => 'array',
        'data_referencia' => 'date',
    ];

    // Relacionamentos (Úteis para a listagem do Módulo 2)
    public function uf(): BelongsTo { return $this->belongsTo(Uf::class); }
    public function rodovia(): BelongsTo { return $this->belongsTo(Rodovia::class); }
}