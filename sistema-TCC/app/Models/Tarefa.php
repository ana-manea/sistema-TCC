<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    protected $table = 'tarefas';

    protected $fillable = [
        'tcc_id',
        'titulo',
        'descricao',
        'prazo',
        'status',
    ];

    protected $casts = [
        'prazo' => 'date',
    ];

    public function tcc()
    {
        return $this->belongsTo(Tcc::class, 'tcc_id');
    }
}
