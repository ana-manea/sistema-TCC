@extends('layouts.app')

@section('title', 'Bancas')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h1>Bancas</h1>
        <a href="{{ route('bancas.create') }}">+ Nova Banca</a>
    </div>

    {{-- Mensagens de sessão --}}
    @if(session('sucesso'))
        <p style="color: green;"><strong>✔ {{ session('sucesso') }}</strong></p>
    @endif
    @if(session('erro'))
        <p style="color: red;"><strong>✘ {{ session('erro') }}</strong></p>
    @endif

    {{-- Filtro por Status (Situação Banca) --}}
    <form method="GET" action="{{ route('bancas.index') }}" style="margin-bottom: 15px; display: flex; gap: 10px; align-items: center;">
        <label for="status" style="font-weight: bold;">Filtrar por status:</label>
        <select name="status" id="status">
            <option value="">-- Todas --</option>
            <option value="agendada"   {{ $statusFiltro === 'agendada'   ? 'selected' : '' }}>Agendada</option>
            <option value="realizada"  {{ $statusFiltro === 'realizada'  ? 'selected' : '' }}>Realizada</option>
            <option value="cancelada"  {{ $statusFiltro === 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
        </select>
        <button type="submit">Filtrar</button>
        @if($statusFiltro)
            <a href="{{ route('bancas.index') }}">Limpar filtro</a>
        @endif
    </form>

    @if($bancas->isEmpty())
        <p>Nenhuma banca encontrada{{ $statusFiltro ? ' com o status "' . $statusFiltro . '"' : '' }}.</p>
    @else
        <table border="1" cellpadding="8" style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f0f0f0;">
                <tr>
                    <th>#</th>
                    <th>TCC</th>
                    <th>Data e Hora</th>
                    <th>Local</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bancas as $banca)
                    <tr>
                        <td>{{ $banca->id }}</td>
                        <td>{{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($banca->data_hora)->format('d/m/Y H:i') }}</td>
                        <td>{{ $banca->local }}</td>
                        <td>
                            @if($banca->status === 'agendada')
                                <span style="color: blue;">Agendada</span>
                            @elseif($banca->status === 'realizada')
                                <span style="color: green;">Realizada</span>
                            @else
                                <span style="color: red;">Cancelada</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <a href="{{ route('bancas.show', $banca) }}">Ver</a>

                            {{-- Definir membros: só se ainda não tem membros --}}
                            @if($banca->bancaMembros->isEmpty())
                                <a href="{{ route('bancas.definirMembros', $banca) }}">Def. Membros</a>
                            @endif

                            {{-- Confirmar realizada: só presidente, só quando agendada --}}
                            @if($banca->status === 'agendada')
                                @php
                                    $euSouPresidente = $banca->bancaMembros
                                        ->where('usuario_id', $usuarioLogadoId)
                                        ->where('papel', 'presidente')
                                        ->isNotEmpty();
                                @endphp
                                @if($euSouPresidente)
                                    <form action="{{ route('bancas.confirmarRealizada', $banca) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Confirmar apresentação realizada?')">
                                            Confirmar Realizada
                                        </button>
                                    </form>
                                @endif
                            @endif

                            {{-- Fechamento: só presidente, só quando realizada e sem resultado --}}
                            @if($banca->status === 'realizada' && !$banca->resultado_final)
                                @php
                                    $euSouPresidente = $banca->bancaMembros
                                        ->where('usuario_id', $usuarioLogadoId)
                                        ->where('papel', 'presidente')
                                        ->isNotEmpty();
                                @endphp
                                @if($euSouPresidente)
                                    <a href="{{ route('bancas.telaFechamento', $banca) }}">Fechar Banca</a>
                                @endif
                            @endif

                            {{-- Lançar nota: membros que ainda não avaliaram --}}
                            @if($banca->status === 'realizada')
                                @php
                                    $euSouMembro = $banca->bancaMembros
                                        ->where('usuario_id', $usuarioLogadoId)
                                        ->isNotEmpty();
                                    $jaAvaliou = $banca->avaliacoes
                                        ->where('avaliador_id', $usuarioLogadoId)
                                        ->isNotEmpty();
                                @endphp
                                @if($euSouMembro && !$jaAvaliou)
                                    <a href="{{ route('avaliacoes.criar', $banca->id) }}">Lançar Nota</a>
                                @endif
                            @endif

                            {{-- Ata --}}
                            @if($banca->status === 'realizada' && $banca->resultado_final)
                                <a href="{{ route('bancas.ata', $banca) }}">Ver Ata</a>
                            @endif

                            <a href="{{ route('bancas.edit', $banca) }}">Editar</a>

                            <form action="{{ route('bancas.destroy', $banca) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Excluir esta banca?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Total: {{ $bancas->count() }} banca(s).</strong></p>
    @endif

@endsection