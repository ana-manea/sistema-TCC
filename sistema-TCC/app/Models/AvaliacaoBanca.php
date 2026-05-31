<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoBanca extends Model
{
    protected $table = 'avaliacoes_banca';

    protected $fillable = [
        'banca_id',
        'orientando_id',
        'avaliador_id',
        'nota',
        'parecer',
        'resultado',
    ];

    protected $casts = [
        'nota'       => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function banca()
    {
        return $this->belongsTo(Banca::class, 'banca_id');
    }

    // Avaliador é um User
    public function avaliador()
    {
        return $this->belongsTo(User::class, 'avaliador_id');
    }

    // Orientando avaliado nesta banca
    public function orientando()
    {
        return $this->belongsTo(Orientando::class, 'orientando_id');
    }
}