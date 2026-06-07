@extends('layouts.app')

@section('title', 'Orientadores')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'dashboard',
        'pagAnterior' => 'aos Orientadores',
        'pagAtual' => 'Professores Orientadores'
    ])

    <div>
        <a class="btn btn-primary" href="{{ route('users.create', ['funcao' => 'orientador']) }}">
            <i class="bi bi-plus-circle"></i> Novo Orientador
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
    {{-- LISTA VAZIA --}}
    @if($orientadores->isEmpty())
        <div class="alert alert-secondary">Nenhum orientador cadastrado.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Área de Atuação</th>
                        <th>Disponibilidade</th>
                        <th>Limite de Orientandos</th>
                        <th>Vagas Ocupadas</th>
                        <th>Ações</th> </tr>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orientadores as $orientador)
                        <tr>
                            <td class="fw-medium">
                                {{ $orientador->user->name ?? 'Sem usuário' }}
                            </td>

                            <td>
                                {{ $orientador->area_atuacao }}
                            </td>

                            <td>
                                {{ $orientador->disponibilidade }}
                            </td>

                            <td>
                                {{ $orientador->max_orientandos }}
                            </td>
                            <td>
                                {{-- Exibe o número de orientandos ativos --}}
                                <span>
                                    {{ $orientador->orientandos_count }} / {{ $orientador->max_orientandos }}
                                </span>
                            </td>
                            <td class="text-end">
                            <a href="{{ route('orientadores.show', $orientador->id) }}" class="btn btn-sm btn-outline-secondary" title="Ver Detalhes">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                        </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    </div>
</div>
@endsection