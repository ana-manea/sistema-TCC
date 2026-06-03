<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reuniao extends Model
{
    protected $table = 'reunioes';

    protected $fillable = [
        'tcc_id',
        'data_hora',
        'local',
        'observacoes',
        'proximos_passos',
        'status',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
    ];

    // Uma reunião pertence a um TCC
    public function tcc()
    {
        return $this->belongsTo(Tcc::class, 'tcc_id');
    }
}