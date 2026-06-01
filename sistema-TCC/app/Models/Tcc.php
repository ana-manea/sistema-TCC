<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tcc extends Model
{
    protected $table = 'tccs';

    /**
     * nota_final e resultado_final estão no fillable para permitir
     * a sincronização feita pelo BancaController::fecharBanca().
     * Os formulários de criação/edição de TCC NÃO enviam esses campos,
     * e o TccController::update() não os aceita via validate().
     */
    protected $fillable = [
        'orientador_id',
        'tema',
        'descricao',
        'status',
        'nota_final',
        'resultado_final',
    ];

    protected $casts = [
        'nota_final'  => 'decimal:2',
    ];

    public function orientador()
    {
        return $this->belongsTo(Orientador::class, 'orientador_id');
    }

    public function orientandos()
    {
        return $this->belongsToMany(Orientando::class, 'tcc_orientandos', 'tcc_id', 'orientando_id')
            ->withTimestamps();
    }

    public function reunioes()
    {
        return $this->hasMany(Reuniao::class, 'tcc_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'tcc_id');
    }

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class, 'tcc_id');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'tcc_id');
    }

    public function banca()
    {
        return $this->hasOne(Banca::class, 'tcc_id');
    }

    public function historicos()
    {
        return $this->hasMany(HistoricoTcc::class, 'tcc_id');
    }

    public function avaliacoes()
    {
        return $this->hasManyThrough(AvaliacaoBanca::class, Banca::class, 'tcc_id', 'banca_id', 'id', 'id');
    }
}