@extends('layouts.app')

@section('title', 'Trabalhos em Andamento')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-start gap-4 align-items-center mb-3">
        <a href="{{ route('tccs.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Ver todos os Trabalhos
        </a>
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-people me-2"></i>Trabalhos em Andamento</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
        {{-- Bloco de acesso restrito para membros de banca --}}
        @if(auth()->check() && auth()->user()->funcao === 'membro_banca')
            <div>
                <i class="bi bi-lock-fill"></i>
                Membros de banca não têm acesso à listagem de TCCs em andamento.
            </div>
        @else

            @if($tccs->isEmpty())
                <div class="alert alert-secondary">
                    <i class="bi bi-info-circle"></i> Nenhum TCC em andamento no momento.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tema</th>
                                <th>Orientador</th>
                                <th>Orientando(s)</th>
                                <th>Data de Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($tccs as $tcc)
                                <tr>
                                    <td>
                                        <strong>{{ $tcc->tema }}</strong>
                                        @if($tcc->descricao)
                                            <br>
                                            <small>{{ Str::limit($tcc->descricao, 80) }}</small>
                                        @endif
                                    </td>

                                    <td>
                                        <i class="bi bi-person-badge"></i> 
                                        {{ $tcc->orientador?->user?->name ?? 'A definir' }}
                                    </td>

                                    <td>
                                        <i class="bi bi-person"></i>
                                        @forelse($tcc->orientandos as $orientando)
                                            {{ $orientando->user?->name }}@if(!$loop->last), @endif
                                        @empty
                                            A definir
                                        @endforelse
                                    </td>

                                    <td>{{ $tcc->created_at->format('d/m/Y') }}</td>

                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('tccs.show', $tcc) }}">
                                            <i class="bi bi-eye"></i> Ver detalhes
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        @endif
        </div>
    </div>
</div>
@endsection