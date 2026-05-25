<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tcc extends Model
{
    protected $fillable = ['id', 'orientador_id', 'tema', 'descricao', 'status', 'resultado_final', 'nota_final', 'created_at', 'updated_at'];
    
    protected $casts = [
        'data' => 'datetime'
    ];

    public function orientandos() //dizer que tem relação n:n com orientandos
    {
        return $this->belongsToMany(Orientando::class, 'tcc_orientandos', 'tcc_id', 'orientando_id');
    }
}
