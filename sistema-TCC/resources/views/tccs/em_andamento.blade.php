@extends('layouts.app')

@section('title', 'TCCs em Andamento')

@section('content')

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

@endsection