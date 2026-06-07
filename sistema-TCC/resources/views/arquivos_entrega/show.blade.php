@extends('layouts.app')

@section('title', 'Arquivo de Entrega')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota'        => 'arquivos_entrega.index',
        'pagAnterior' => 'aos Arquivos',
        'pagAtual'    => 'Arquivo de Entrega'
    ])
</div>

<div class="card">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Entrega</dt>
            <dd class="col-sm-9">{{ $arquivoEntrega->entrega->titulo ?? '—' }}</dd>

            <dt class="col-sm-3">TCC</dt>
            <dd class="col-sm-9">{{ $arquivoEntrega->entrega->tcc->tema ?? '—' }}</dd>

            <dt class="col-sm-3">Versão</dt>
            <dd class="col-sm-9">v{{ $arquivoEntrega->versao }}</dd>

            <dt class="col-sm-3">Arquivo</dt>
            <dd class="col-sm-9">
                <a href="{{ Storage::url($arquivoEntrega->arquivo_path) }}" target="_blank">
                    {{ basename($arquivoEntrega->arquivo_path) }}
                </a>
            </dd>

            <dt class="col-sm-3">Enviado por</dt>
            <dd class="col-sm-9">{{ $arquivoEntrega->enviadoPor->name ?? '—' }}</dd>

            <dt class="col-sm-3">Validação</dt>
            <dd class="col-sm-9">{{ ucfirst($arquivoEntrega->status_validacao) }}</dd>

            <dt class="col-sm-3">Observação</dt>
            <dd class="col-sm-9">{{ $arquivoEntrega->observacao ?? '—' }}</dd>
        </dl>
    </div>
</div>
@endsection
