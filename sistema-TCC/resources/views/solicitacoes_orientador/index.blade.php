@extends('layouts.app')

@section('title', 'Solicitações de Orientação')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-start gap-4 align-items-center mb-4">
        <a href="{{ route('dashboard.orientador') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar ao Painel
        </a>
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-people me-2"></i>Meus Orientandos</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Erro ao processar resposta. Verifique os dados.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($solicitacoesOrientador->isEmpty())
        <div class="card shadow-sm text-center p-5">
            <div class="card-body">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">Nenhuma solicitação encontrada por enquanto.</h5>
            </div>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($solicitacoesOrientador as $solicitacao)
                <div class="col">
                    <div class="card shadow-sm h-100 border-start-0 border-top-0 border-bottom-0 border-5 {{ $solicitacao->status === 'pendente' || $solicitacao->status === null ? 'border-warning' : ($solicitacao->status === 'aceita' ? 'border-success' : 'border-danger') }}">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 fw-bold">
                                    {{ $solicitacao->orientando->user->name ?? 'Aluno ID: ' . $solicitacao->orientando_id }}
                                </h5>
                                <span class="badge {{ $solicitacao->status === 'pendente' || $solicitacao->status === null ? 'bg-warning text-dark' : ($solicitacao->status === 'aceita' ? 'bg-success' : 'bg-danger') }}">
                                    {{ ucfirst($solicitacao->status ?? 'Pendente') }}
                                </span>
                            </div>
                            
                            <p class="card-text text-muted flex-grow-1 bg-light p-3 rounded small mt-2">
                                <strong>Mensagem do Aluno:</strong><br>
                                "{{ $solicitacao->mensagem ?? 'Sem mensagem enviada.' }}"
                            </p>

                            @if($solicitacao->status !== 'pendente' && $solicitacao->status !== null)
                                <div class="mt-2 border-top pt-2 small">
                                    <strong>Sua Resposta:</strong> {{ $solicitacao->resposta ?? 'Sem justificativa.' }} <br>
                                    @if($solicitacao->respondido_em)
                                        <span class="text-muted text-xs">
                                            <i class="bi bi-clock me-1"></i>Respondido em: {{ \Carbon\Carbon::parse($solicitacao->respondido_em)->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="d-flex gap-2 mt-3">
                                    <button class="btn btn-success btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalResponder-{{ $solicitacao->id }}" onclick="configurarModal('{{ $solicitacao->id }}', 'aceita')">
                                        <i class="bi bi-check-lg"></i> Aceitar
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalResponder-{{ $solicitacao->id }}" onclick="configurarModal('{{ $solicitacao->id }}', 'recusada')">
                                        <i class="bi bi-x-lg"></i> Recusar
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- MODAL DINÂMICO PARA RESPOSTA --}}
                <div class="modal fade" id="modalResponder-{{ $solicitacao->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('solicitacoes_orientador.responder', $solicitacao->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" id="status-{{ $solicitacao->id }}">
                            
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="titulo-modal-{{ $solicitacao->id }}">Responder Solicitação</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Escreva um feedback ou justificativa para o aluno:</label>
                                        <textarea name="resposta" class="form-control" rows="4" placeholder="Ex: Seja bem-vindo! Entrarei em contato para agendar..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn" id="btn-submit-{{ $solicitacao->id }}">Confirmar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function configurarModal(id, acao) {
        const inputStatus = document.getElementById('status-' + id);
        const titulo = document.getElementById('titulo-modal-' + id);
        const btnSubmit = document.getElementById('btn-submit-' + id);

        inputStatus.value = acao;

        if (acao === 'aceita') {
            titulo.innerText = "Aceitar Aluno Orientando";
            btnSubmit.innerText = "Confirmar e Aceitar";
            btnSubmit.className = "btn btn-success";
        } else if (acao === 'recusada') {
            titulo.innerText = "Recusar Solicitação de Orientação";
            btnSubmit.innerText = "Confirmar e Recusar";
            btnSubmit.className = "btn btn-danger";
        }
    }
</script>
@endsection