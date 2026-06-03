<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entregas';

    protected $fillable = ['tcc_id', 'titulo', 'descricao', 'prazo', 'status'];

    protected $casts = [
        'prazo' => 'date',
    ];

    // Uma entrega pertence a um TCC
    public function tcc()
    {
        return $this->belongsTo(Tcc::class, 'tcc_id');
    }

    // Uma entrega tem muitos arquivos enviados
    public function arquivos()
    {
        return $this->hasMany(ArquivoEntrega::class, 'entrega_id');
    }
}