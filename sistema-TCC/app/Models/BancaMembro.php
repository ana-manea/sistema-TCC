<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BancaMembro extends Model
{
    protected $table = 'banca_membros';

    protected $fillable = [
        'banca_id',
        'user_id',
        'papel',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function banca()
    {
        return $this->belongsTo(Banca::class, 'banca_id');
    }
}
