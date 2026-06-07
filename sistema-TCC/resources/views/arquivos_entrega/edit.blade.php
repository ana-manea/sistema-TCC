@extends('layouts.app')

@section('title', 'Editar Arquivo')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'arquivos_entrega.index',
        'pagAnterior' => 'aos Arquivos',
        'pagAtual' => 'Editar Arquivo — v' . $arquivoEntrega->versao
    ])
</div>

<div class="card">
    <div class="card-body">
        {{-- Info somente leitura --}}
        <div class="alert alert-light border mb-4">
            <strong>Entrega:</strong> {{ $arquivoEntrega->entrega->titulo ?? '—' }}<br>
            <strong>Arquivo:</strong>
            <a href="{{ Storage::url($arquivoEntrega->arquivo_path) }}" target="_blank">
                {{ basename($arquivoEntrega->arquivo_path) }}
            </a>
        </div>
        {{-- enctype obrigatório para upload de arquivo --}}
        <form action="{{ route('arquivos_entrega.update', $arquivoEntrega) }}" method="POST" class="vstack gap-3">
            @method('PUT')
            @include('arquivos_entrega._form', ['entregas' => $arquivoEntrega])

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-floppy"></i> Atualizar</button>
                <a class="btn btn-outline-secondary" href="{{ route('arquivos_entrega.index') }}">Cancelar</a>
            </div>
        </form>

    </div>
</div>
@endsection
