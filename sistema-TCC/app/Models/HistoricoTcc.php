<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoTcc extends Model
{
    protected $table = 'historico_tcc';

    protected $fillable = [
        'tcc_id',
        'alterado_por',
        'status_anterior',
        'status_novo',
        'observacao',
    ];

    public function tcc()
    {
        return $this->belongsTo(Tcc::class, 'tcc_id');
    }

    public function alteradoPor()
    {
        return $this->belongsTo(User::class, 'alterado_por');
    }
}