<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name', 'email', 'password', 'funcao', 'avatar'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function orientador()
    {
        return $this->hasOne(Orientador::class, 'user_id');
    }

    public function orientando()
    {
        return $this->hasOne(Orientando::class, 'user_id');
    }
    
    public function arquivosEnviados()
    {
        return $this->hasMany(ArquivoEntrega::class, 'enviado_por');
    }

    public function bancaMembros()
    {
        return $this->hasMany(BancaMembro::class, 'user_id');
    }

    public function bancas()
    {
        return $this->bancaMembros();
    }

    public function avaliacoes()
    {
        return $this->hasMany(AvaliacaoBanca::class, 'avaliador_id');
    }

    public function historicosAlterados()
    {
        return $this->hasMany(HistoricoTcc::class, 'alterado_por');
    }


}
