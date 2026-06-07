@extends('layouts.app')

@section('title', 'Enviar Arquivo')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'arquivos_entrega.index',
        'pagAnterior' => 'aos Arquivos',
        'pagAtual' => 'Enviar Arquivo de Entrega'
    ])
</div>

<div class="card">
    <div class="card-body">
        {{-- enctype obrigatório para upload de arquivo --}}
        <form action="{{ route('arquivos_entrega.store') }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
            @include('arquivos_entrega._form')

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-upload"></i> Enviar
                </button>
                <a class="btn btn-outline-secondary" href="{{ route('arquivos_entrega.index') }}">Cancelar</a>
            </div>
        </form>

    </div>
</div>
@endsection
