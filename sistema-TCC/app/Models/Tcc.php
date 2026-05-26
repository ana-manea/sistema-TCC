<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tcc extends Model
{
<<<<<<< HEAD
    protected $table = 'tccs';

    protected $fillable = [
        'orientador_id',
        'tema',
        'descricao',
        'status',
        'resultado_final',
        'nota_final',
    ];

    public function orientador()
    {
        return $this->belongsTo(Orientador::class, 'orientador_id');
    }

    public function reunioes()
    {
        return $this->hasMany(Reuniao::class, 'tcc_id');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'tcc_id');
    }

    public function banca()
    {
        return $this->hasOne(Banca::class, 'tcc_id');
    }
}
=======
    protected $fillable = ['id', 'orientador_id', 'tema', 'descricao', 'status', 'resultado_final', 'nota_final', 'created_at', 'updated_at'];
    
    protected $casts = [
        'data' => 'datetime'
    ];

    public function orientandos() //dizer que tem relação n:n com orientandos
    {
        return $this->belongsToMany(Orientando::class, 'tcc_orientandos', 'tcc_id', 'orientando_id');
    }
}
>>>>>>> dev
