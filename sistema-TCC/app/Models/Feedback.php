<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'tcc_id',
        'orientador_id',
        'descricao',
    ];

    public function tcc()
    {
        return $this->belongsTo(Tcc::class, 'tcc_id');
    }

    public function orientador()
    {
        return $this->belongsTo(Orientador::class, 'orientador_id');
    }
}