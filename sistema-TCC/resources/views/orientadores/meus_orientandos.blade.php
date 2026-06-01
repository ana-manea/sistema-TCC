@extends('layouts.app')

@section('title', 'Meus Orientandos')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-start gap-4 align-items-center mb-3">
        <a href="{{ route('dashboard.orientador') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar ao Painel
        </a>
    </div>

    @if($orientandos->isEmpty())
        <div class="card shadow-sm text-center p-5">
            <div class="card-body">
                <i class="bi bi-person-dash text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">Você não possui nenhum orientando ativo.</h5>
                <p class="text-muted small">Acesse a aba de solicitações para aceitar novos alunos.</p>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 bg-white">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nome do Aluno</th>
                                <th>E-mail</th>
                                <th>Curso / Vínculo</th>
                                <th>Data de Início</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orientandos as $aluno)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            {{-- Ícone padrão para simular o avatar do aluno --}}
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white me-3" style="width: 36px; height: 36px; font-size: 14px; font-weight: bold;">
                                                {{ mb_substr($aluno->user->name ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block">{{ $aluno->user->name ?? 'Aluno Desconhecido' }}</span>
                                                <span class="text-muted small">RA: {{ $aluno->matricula ?? 'Não informado' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="bi bi-envelope text-muted me-1"></i>{{ $aluno->user->email ?? 'Sem e-mail' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $aluno->curso ?? 'TCC' }}</span>
                                    </td>
                                    <td>
                                        {{ $aluno->updated_at ? $aluno->updated_at->format('d/m/Y') : 'Não disponível' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="bi bi-folder2-open me-1"></i> Ver Projeto
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection