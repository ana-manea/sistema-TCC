@extends('layouts.app')

@section('title', 'TCCs em Andamento')

@section('content')

    <div>
        <div>
            <h1>TCCs em Andamento</h1>
        </div>

        <div>
            <a href="{{ route('tccs.index') }}">
                <i class="bi bi-arrow-left"></i> Ver todos os TCCs
            </a>
        </div>
    </div>

    {{-- Bloco de acesso restrito para membros de banca --}}
    @if(auth()->check() && auth()->user()->funcao === 'membro_banca')
        <div>
            <i class="bi bi-lock-fill"></i>
            Membros de banca não têm acesso à listagem de TCCs em andamento.
        </div>
    @else

        @if($tccs->isEmpty())
            <div>
                <i class="bi bi-info-circle"></i> Nenhum TCC em andamento no momento.
            </div>
        @else
            <div>
                <table>
                    <thead>
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
                                    <a href="{{ route('tccs.show', $tcc) }}">
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
@endsection