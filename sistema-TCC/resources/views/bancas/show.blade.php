<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Banca</title>
</head>
<body>

    <h1>Banca — {{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}</h1>
    <a href="{{ route('bancas.index') }}">← Voltar para a listagem</a>
    <hr>

    @if(session('sucesso'))
        <p style="color: green;"><strong>✔ {{ session('sucesso') }}</strong></p>
    @endif
    @if(session('erro'))
        <p style="color: red;"><strong>✘ {{ session('erro') }}</strong></p>
    @endif

    {{-- Dados do TCC --}}
    <h2>Dados do TCC</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Tema</th>
            <td>{{ $banca->tcc->tema ?? '—' }}</td>
        </tr>
        <tr>
            <th>Descrição</th>
            <td>{{ $banca->tcc->descricao ?? '—' }}</td>
        </tr>
        <tr>
            <th>Orientador</th>
            <td>{{ $banca->tcc->orientador?->user?->name ?? '—' }}</td>
        </tr>
        <tr>
            <th>Orientando(s)</th>
            <td>
                @forelse($banca->tcc->orientandos as $orientando)
                    {{ $orientando->user?->name }}@if(!$loop->last), @endif
                @empty
                    —
                @endforelse
            </td>
        </tr>
        <tr>
            <th>Status do TCC</th>
            <td>{{ ucfirst(str_replace('_', ' ', $banca->tcc->status)) }}</td>
        </tr>
    </table>

    <br>

    {{-- Dados da Banca --}}
    <h2>Dados da Banca</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Data e Hora</th>
            <td>{{ \Carbon\Carbon::parse($banca->data_hora)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Local / Link</th>
            <td>{{ $banca->local ?? '—' }}</td>
        </tr>
        <tr>
            <th>Status da Banca</th>
            <td>{{ ucfirst($banca->status) }}</td>
        </tr>
    </table>

    <br>

    {{-- Membros da Banca --}}
    <h2>Membros da Banca</h2>
    @if($banca->membros->isEmpty())
        <p>Nenhum membro cadastrado ainda.</p>
        <a href="{{ route('bancas.definirMembros', $banca) }}">
            <button>Definir Membros da Banca</button>
        </a>
    @else
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Papel</th>
                </tr>
            </thead>
            <tbody>
                @foreach($banca->membros as $membro)
                    <tr>
                        <td>{{ $membro->user?->name ?? '—' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $membro->papel)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <a href="{{ route('bancas.definirMembros', $banca) }}">Redefinir Membros</a>
    @endif

    <br>

    {{-- Ações disponíveis por papel --}}
    <h2>Ações</h2>

    @php
        $euSouPresidente = $banca->membros
            ->where('user_id', auth()->id())
            ->where('papel', 'presidente')
            ->isNotEmpty();

        $euSouMembro = $banca->membros
            ->where('user_id', auth()->id())
            ->isNotEmpty();

        // Verifica se este avaliador já lançou nota (considera apenas 1 linha por banca/avaliador)
        $jaAvaliou = $banca->avaliacoes
            ->where('avaliador_id', auth()->id())
            ->isNotEmpty();
    @endphp

    {{-- Confirmar realizada: só o presidente, só quando agendada --}}
    @if($banca->status === 'agendada' && $euSouPresidente)
        <form action="{{ route('bancas.confirmarRealizada', $banca) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" onclick="return confirm('Confirmar que a apresentação foi realizada?')">
                ✔ Confirmar Apresentação Realizada
            </button>
        </form>
    @endif

    {{-- Lançar nota: só membros, só quando realizada, só quem ainda não avaliou --}}
    @if($banca->status === 'realizada' && $euSouMembro && !$jaAvaliou)
        <a href="{{ route('avaliacoes.criar', $banca->id) }}">
            <button>Lançar Nota e Parecer</button>
        </a>
    @endif

    {{-- Fechar banca: só presidente, só quando realizada e ainda sem resultado --}}
    @if($banca->status === 'realizada' && $euSouPresidente && !$banca->resultado_final)
        <a href="{{ route('bancas.telaFechamento', $banca) }}">
            <button>Fechar Banca / Definir Resultado</button>
        </a>
    @endif

    <br><br>

    {{-- Avaliações dos membros --}}
    <h2>Avaliações Individuais</h2>
    @if($banca->avaliacoes->isEmpty())
        <p>Nenhuma avaliação registrada ainda.</p>
    @else
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Avaliador</th>
                    <th>Nota</th>
                    <th>Parecer</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                {{--
                    Exibe uma linha por avaliador (agrupa por avaliador_id para não duplicar em TCCs dupla).
                --}}
                @foreach($banca->avaliacoes->unique('avaliador_id') as $avaliacao)
                    @php
                        $podeEditar = $avaliacao->avaliador_id === auth()->id()
                                   && $avaliacao->created_at->diffInHours(now()) <= 48;
                    @endphp
                    <tr>
                        <td>{{ $avaliacao->avaliador?->name ?? 'ID ' . $avaliacao->avaliador_id }}</td>
                        <td>{{ number_format($avaliacao->nota, 2, ',', '') }}</td>
                        <td>{{ $avaliacao->parecer }}</td>
                        <td>
                            @if($podeEditar)
                                <a href="{{ route('avaliacoes.edit', $avaliacao->id) }}">Editar</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            // Média das notas únicas por avaliador (para não duplicar em caso de dupla)
            $mediaAtual = $banca->avaliacoes->unique('avaliador_id')->avg('nota');
        @endphp
        <p><strong>Média atual: {{ number_format($mediaAtual, 2, ',', '') }}</strong></p>
    @endif

    <br>

    {{-- Resultado Final — só aparece após fechamento da banca --}}
    @if($banca->status === 'realizada' && $banca->resultado_final)
        <h2>Resultado Final</h2>
        <table border="1" cellpadding="6">
            <tr>
                <th>Nota Final</th>
                <td><strong>{{ number_format($banca->nota_final, 2, ',', '') }}</strong></td>
            </tr>
            <tr>
                <th>Resultado</th>
                <td>
                    @if($banca->resultado_final === 'aprovado')
                        <span style="color: green; font-weight: bold;">✔ Aprovado</span>
                    @elseif($banca->resultado_final === 'aprovado_com_ressalvas')
                        <span style="color: orange; font-weight: bold;">⚠ Aprovado com Ressalvas</span>
                    @else
                        <span style="color: red; font-weight: bold;">✘ Reprovado</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Parecer Final</th>
                <td>{{ $banca->parecer_final }}</td>
            </tr>
        </table>

        <br>
        <a href="{{ route('bancas.ata', $banca->id) }}">📄 Ver Ata da Banca</a>
    @endif

    <br>
    <a href="{{ route('bancas.edit', $banca) }}">Editar dados da banca</a> |
    <form action="{{ route('bancas.destroy', $banca) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Excluir esta banca?')">Excluir Banca</button>
    </form>

    <br><br>
    <a href="{{ route('bancas.index') }}">← Voltar para a listagem</a>

</body>
</html>