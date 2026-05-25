<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banca extends Model
{
    // 1. Avisa o Laravel qual é o nome exato da tabela no banco de dados
    protected $table = 'bancas';

    // 2. Define quais campos podem ser preenchidos via formulário (Segurança contra ataques de injeção de dados)
    protected $fillable = [
        'tcc_id',
        'data_hora',
        'local',
        'status',
        'parecer_final',
        'resultado_final',
        'nota_final'
    ];
}
