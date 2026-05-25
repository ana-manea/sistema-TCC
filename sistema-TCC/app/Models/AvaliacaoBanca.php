<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvaliacaoBanca extends Model
{
    use HasFactory;

    // identificação da tabela)
    protected $table = 'avaliacoes_banca';

    // campos autorizados a serem salvos:
    protected $fillable = [
    'banca_id',
    'orientando_id',
    'avaliador_id',
    'nota',
    'parecer',
    'resultado',
    ];

    public function banca()
    {
        // Uma avaliação pertence a uma banca
        return $this->belongsTo(Banca::class, 'banca_id');
    }
}
