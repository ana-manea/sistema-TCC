@extends('layouts.app')

@section('title', 'Solicitar Orientador')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-person-plus me-2"></i>Nova Solicitação de Orientação</h1>
                <a href="{{ route('solicitacoes_orientando.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Ver Minhas Solicitações
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 fw-bold text-primary">
                    Selecione um Orientador Disponível
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('solicitacoes_orientador.store') }}" method="POST">
                        @csrf
                        
                        {{-- ID do Aluno logado (Injetado pelo Controller) --}}
                        <input type="hidden" name="orientando_id" value="{{ $orientando->id }}">

                        {{-- 1. Seleção do Orientador --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Orientador Desejado *</label>
                            <select name="orientador_id" class="form-select form-select-lg @error('orientador_id') is-invalid @enderror" required>
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
                            <div class="form-text text-muted">
                                Apenas professores com vagas em aberto aparecem nesta listagem.
                            </div>
                            @error('orientador_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. Mensagem de Justificativa --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Apresentação / Justificativa (Opcional)</label>
                            <textarea name="mensagem" class="form-control @error('mensagem') is-invalid @enderror" rows="5" placeholder="Escreva uma breve mensagem explicando o tema do seu TCC ou o motivo de escolher este orientador..."></textarea>
                            @error('mensagem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Botões de Ação --}}
                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('solicitacoes_orientando.index') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-send me-1"></i> Enviar Solicitação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection