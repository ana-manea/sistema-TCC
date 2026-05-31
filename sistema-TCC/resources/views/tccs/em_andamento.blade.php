@extends('layouts.app')

@section('title', 'Trabalhos em Andamento')

@section('content')
<<<<<<< HEAD

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h1>TCCs em Andamento</h1>
        <a href="{{ route('tccs.index') }}">← Ver todos os TCCs</a>
    </div>

    {{-- Mensagens de sessão --}}
    @if(session('sucesso'))
        <p style="color: green;"><strong>✔ {{ session('sucesso') }}</strong></p>
    @endif

    @if($tccs->isEmpty())
        <p>Nenhum TCC em andamento no momento.</p>
    @else
        <table border="1" cellpadding="8" style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f0f0f0;">
                <tr>
                    <th>#</th>
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
                        <td>{{ $tcc->id }}</td>
                        <td>{{ $tcc->tema }}</td>
                        <td>{{ $tcc->orientador?->user?->name ?? '—' }}</td>
                        <td>
                            @forelse($tcc->orientandos as $orientando)
                                {{ $orientando->user?->name }}@if(!$loop->last), @endif
                            @empty
                                —
                            @endforelse
                        </td>
                        <td>{{ $tcc->created_at?->format('d/m/Y') ?? 'Não informada' }}</td>
                        <td>
                            <a href="{{ route('tccs.show', $tcc) }}">Ver Detalhes</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Total: {{ $tccs->count() }} TCC(s) em andamento.</strong></p>
    @endif

=======
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
>>>>>>> dev
@endsection