@extends('layouts.app')

@section('title', 'Bancas')

@section('content')
    <h1>Bancas Avaliadoras</h1>

    <a class="btn btn-primary" href="{{ route('bancas.create') }}">
        <i class="bi bi-plus-circle"></i> Nova Banca
    </a>

    @if(session('sucesso'))
        <p>{{ session('sucesso') }}</p>
    @endif

    @if($bancas->isEmpty())
        <p>Nenhuma banca cadastrada até o momento.</p>
    @else
        @if (session('erro'))
            {{ session('erro') }}<br>
        @endif
        <table border="1">
            <thead>
                <tr>
                    <th>Cód. TCC</th>
                    <th>Data e Hora</th>
                    <th>Local</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bancas as $banca)
                    <tr>
                        <td>{{ $banca->tcc_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($banca->data_hora)->format('d/m/Y H:i') }}</td>
                        <td>{{ $banca->local }}</td>
                        <td>{{ $banca->status }}</td>
                        <td>
                            <a href="{{ route('bancas.edit', $banca->id) }}">Editar</a>
                            
                            | <a href="{{ route('avaliacoes.criar', $banca->id) }}">Avaliar Banca</a>

                            @php
                                // Pega o ID e a função do usuário que está logado de verdade no sistema
                                $usuarioLogadoId = auth()->id();
                                $funcaoUsuario = auth()->user()->funcao ?? null; 

                                // 1. Checa dinamicamente se o usuário logado é o presidente DESTA banca específica
                                $eOPresidente = \DB::table('banca_membros')
                                    ->where('banca_id', $banca->id)
                                    ->where('user_id', $usuarioLogadoId)
                                    ->where('papel', 'presidente')
                                    ->exists();

                                // 2. Checa dinamicamente se o usuário logado é o dono do TCC desta banca
                                // O link aparece se ele for 'orientando' E o ID dele for o mesmo ID do TCC da banca
                                $eOOrientandoDestaBanca = ($funcaoUsuario === 'orientando' && $banca->tcc_id == $usuarioLogadoId);
                            @endphp

                            {{-- Se estiver agendada e for o presidente, mostra a opção de encerrar --}}
                            @if($eOPresidente && $banca->status == 'agendada')
                                | <a href="{{ route('bancas.telaFechamento', $banca->id) }}">[Fechar Banca]</a>
                            @endif
                            
                            {{-- NOVA REGRA: Se estiver realizada E for o presidente OU o orientando, mostra a Ata --}}
                            @if($banca->status == 'realizada' && ($eOPresidente || $eOOrientandoDestaBanca))
                                | <a href="{{ route('bancas.ata', $banca->id) }}">[Ver Ata]</a>
                            @endif
                            |
                            <form action="{{ route('bancas.destroy', $banca->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Deseja mesmo excluir esta banca?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection