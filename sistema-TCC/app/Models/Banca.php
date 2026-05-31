<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banca extends Model
{
    protected $table = 'bancas';

    protected $fillable = [
        'tcc_id',
        'data_hora',
        'local',
        'status',
        'parecer_final',
        'resultado_final',
        'nota_final',
    ];

    protected $casts = [
        'data_hora'  => 'datetime',
        'nota_final' => 'decimal:2',
    ];

    public function tcc()
    {
        return $this->belongsTo(Tcc::class, 'tcc_id');
    }

    public function bancaMembros()
    {
        return $this->hasMany(BancaMembro::class, 'banca_id');
    }

    public function avaliacoes()
    {
        return $this->hasMany(AvaliacaoBanca::class, 'banca_id');
    }
}