<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orientando extends Model
{
    protected $table = 'orientandos';

    // infos tabela
    protected $fillable = ['user_id', 'orientador_id', 'matricula', 'curso', 'semestre'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orientador()
    {
        return $this->belongsTo(Orientador::class, 'orientador_id');
    }

    public function tccs()
    {
        return $this->belongsToMany(Tcc::class, 'tcc_orientandos', 'orientando_id', 'tcc_id')
            ->withTimestamps();
    }

    public function solicitacoes()
    {
        return $this->hasMany(SolicitacaoOrientador::class, 'orientando_id');
    }
}
