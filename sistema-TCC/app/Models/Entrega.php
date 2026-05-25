<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{    
    protected $table = 'entregas';

    // infos tabela
    protected $fillable = ['tcc_id', 'titulo', 'descricao', 'prazo', 'status'];

}
