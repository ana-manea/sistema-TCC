<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BancaMembro extends Model
{
    protected $table = 'banca_membros';

    protected $fillable = [
        'banca_id',
        'usuario_id',
        'papel',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function banca()
    {
        return $this->belongsTo(Banca::class, 'banca_id');
    }
}
