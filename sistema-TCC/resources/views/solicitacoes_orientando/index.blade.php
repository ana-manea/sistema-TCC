@extends('layouts.app')

@section('title', 'Minhas Solicitações')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'dashboard',
        'pagAnterior' => 'ao Dashboard',
        'pagAtual' => 'Acompanhar Orientação'
    ])
    
    <div>
        {{-- Botão para criar nova solicitação --}}
        <a href="{{ route('solicitacoes_orientando.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nova Solicitação
        </a>
    </div>
</div>

@if($solicitacoes->isEmpty())
    <div class="card shadow-sm text-center p-5 border-0">
        <div class="card-body">
            <i class="bi bi-send-dash text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-muted">Você ainda não enviou nenhuma solicitação.</h5>
            <p class="text-muted small mb-3">Clique no botão acima para escolher um orientador.</p>
        </div>
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 bg-white">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Orientador</th>
                            <th>Sua Mensagem</th>
                            <th>Status</th>
                            <th>Resposta do Professor</th>
                            <th class="pe-4">Data do Envio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitacoes as $solicitacao)
                            <tr>
                                {{-- Dados do Professor --}}
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block">
                                        {{ $solicitacao->orientador->user->name ?? 'Professor ID: ' . $solicitacao->orientador_id }}
                                    </span>
                                    <span class="text-muted small">{{ $solicitacao->orientador->area_atuacao ?? 'Geral' }}</span>
                                </td>
                                
                                {{-- Mensagem que o aluno enviou --}}
                                <td class="text-muted small" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $solicitacao->mensagem ?? 'Sem mensagem informada.' }}
                                </td>
                                
                                {{-- Status estilizado com badges baseados no seu ENUM --}}
                                <td>
                                    @if($solicitacao->status === 'pendente' || $solicitacao->status === null)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i>Pendente</span>
                                    @elseif($solicitacao->status === 'aceita')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aceita</span>
                                    @elseif($solicitacao->status === 'recusada')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Recusada</span>
                                    @endif
                                </td>
                                
                                {{-- Feedback do Professor --}}
                                <td class="small">
                                    @if($solicitacao->status === 'pendente')
                                        <span class="text-muted italic">Aguardando avaliação...</span>
                                    @else
                                        <span class="fw-semibold text-secondary">{{ $solicitacao->resposta ?? 'Sem justificativa preenchida.' }}</span>
                                        @if($solicitacao->respondido_em)
                                            <div class="text-muted text-xs font-monospace mt-1">
                                                Em: {{ \Carbon\Carbon::parse($solicitacao->respondido_em)->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                
                                {{-- Data de criação --}}
                                <td class="pe-4 text-muted small">
                                    {{ $solicitacao->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection