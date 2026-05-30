<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orientador extends Model
{
    protected $table = 'orientadores';

    // infos tabela
    protected $fillable = ['user_id', 'area_atuacao', 'disponibilidade', 'max_orientandos'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // solicitação orientador
    public function solicitacoes()
    {
        return $this->hasMany(SolicitacaoOrientador::class, 'orientador_id');
    }

    public function orientandos()
    {
        return $this->hasMany(Orientando::class, 'orientador_id');
    }
}


