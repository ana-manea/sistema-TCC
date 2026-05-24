<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoOrientador extends Model
{
    protected $table = 'solicitacoes_orientador';

    // infos tabela
    protected $fillable = ['orientando_id', 'orientador_id', 'mensagem', 'resposta', 'status', 'respondido_em'];

    protected $casts = [
        'respondido_em' => 'datetime',
    ];

    public function orientando()
    {
        return $this->belongsTo(Orientando::class, 'orientando_id');
    }

    public function orientador()
    {
        return $this->belongsTo(Orientador::class, 'orientador_id');
    }
}
