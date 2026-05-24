<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    // CRUD inicial - usuários
    protected $fillable = ['name', 'email', 'password', 'funcao', 'avatar'];

    // senha

    // verificação email/senha

    // usuário -> orientador

    // usuário -> orientando

    // arquivos enviados
    public function arquivosEnviados()
    {
        return $this->hasMany(ArquivoEntrega::class, 'enviado_por');
    }

    // membros da banca

    // avaliações

    // histórico


}
