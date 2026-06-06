@extends('layouts.app')

@section('title', 'Solicitar Orientador')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'solicitacoes_orientando.index',
        'pagAnterior' => 'às Minhas Solicitações',
        'pagAtual' => 'Nova Solicitação de Orientação'
    ])
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('solicitacoes_orientador.store') }}" method="POST" class="vstack gap-3">
            @csrf
            {{-- ID do Aluno logado (Injetado pelo Controller) --}}
            <input type="hidden" name="orientando_id" value="{{ $orientando->id }}">

            {{-- 1. Seleção do Orientador --}}
            <div class="mb-3">
                <label class="form-label">Orientador Desejado *</label>
                <p class="mb-0 text-muted small">Apenas professores com vagas em aberto aparecem nesta listagem.</p>
                <select name="orientador_id" class="form-select @error('orientador_id') is-invalid @enderror" required>

                    <option value="" selected disabled>Escolha um professor...</option>
                    @foreach($orientadores as $orientador)
                        {{-- Exibe o nome, a área e quantas vagas ele ainda tem --}}
                        <option value="{{ $orientador->id }}">
                            {{ $orientador->user->name ?? 'Professor' }} 
                            — {{ $orientador->area_atuacao ?? 'Geral' }} 
                            ({{ $orientador->vagas_disponiveis ?? 0 }} vagas restantes)
                        </option>
                    @endforeach
                </select>
                @error('orientador_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- 2. Mensagem de Justificativa --}}
            <div class="mb-3">
                <label class="form-label">Apresentação / Justificativa (Opcional)</label>
                <textarea name="mensagem" class="form-control @error('mensagem') is-invalid @enderror" rows="5" placeholder="Escreva uma breve mensagem explicando o tema do seu TCC ou o motivo de escolher este orientador..."></textarea>
                @error('mensagem')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botões de Ação --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Enviar Solicitação</button>
                <a href="{{ route('solicitacoes_orientando.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection