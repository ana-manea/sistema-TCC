<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArquivoEntrega extends Model
{
    protected $table = 'arquivos_entrega';

    // Infos da tabela
    protected $fillable = ['entrega_id', 'enviado_por', 'arquivo_path', 'versao', 'observacao', 'status_validacao'];

    // envio arquivo
    public function entrega()
    {
        return $this->belongsTo(Entrega::class, 'entrega_id');
    }

    public function enviadoPor()
    {
        return $this->belongsTo(User::class, 'enviado_por');
    }
}
