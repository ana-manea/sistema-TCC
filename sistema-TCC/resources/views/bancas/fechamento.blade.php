<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fechamento da Banca</title>
</head>
<body>

    <h1>Fechamento da Banca — {{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}</h1>
    <a href="{{ route('bancas.show', $banca) }}">← Voltar para a banca</a>
    <hr>

    @if(session('erro'))
        <p style="color: red;"><strong>{{ session('erro') }}</strong></p>
    @endif

    {{-- Resumo das avaliações --}}
    <h3>Avaliações dos Membros</h3>
    @if($avaliacoes->isEmpty())
        <p style="color: red;">Nenhuma avaliação lançada ainda. O fechamento não pode ser realizado.</p>
    @else
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Avaliador</th>
                    <th>Nota</th>
                    <th>Parecer</th>
                </tr>
            </thead>
            <tbody>
                {{-- Uma linha por avaliador (evita duplicação de duplas) --}}
                @foreach($avaliacoes->unique('avaliador_id') as $avaliacao)
                    <tr>
                        <td>{{ $avaliacao->avaliador?->name ?? 'ID ' . $avaliacao->avaliador_id }}</td>
                        <td>{{ number_format($avaliacao->nota, 2, ',', '') }}</td>
                        <td>{{ $avaliacao->parecer }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>
            <strong>Média calculada:
                {{ $mediaCalculada !== null ? number_format($mediaCalculada, 2, ',', '.') : '—' }}
            </strong>
        </p>

        {{--
            Resultado sugerido automaticamente pelo sistema conforme critérios do documento:
            >= 7      → Aprovado
            5 a 6.9  → Aprovado com Ressalvas
            < 5      → Reprovado
        --}}
        @if($resultadoSugerido)
            <p style="color: #555;">
                <strong>Resultado sugerido pelo sistema:</strong>
                @if($resultadoSugerido === 'aprovado')
                    <span style="color: green;">Aprovado (média ≥ 7)</span>
                @elseif($resultadoSugerido === 'aprovado_com_ressalvas')
                    <span style="color: orange;">Aprovado com Ressalvas (média entre 5 e 6,9)</span>
                @else
                    <span style="color: red;">Reprovado (média < 5)</span>
                @endif
            </p>
        @endif
    @endif

    <hr>

    <h3>Veredito Final</h3>
    <p>Esta ação irá encerrar a banca, salvar a nota final e disponibilizar a ata.</p>

    <form action="{{ route('bancas.fechar', $banca->id) }}" method="POST">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="resultado_final" style="font-weight: bold;">Resultado Final:</label><br>
            <select name="resultado_final" id="resultado_final" required>
                <option value="">-- Selecione --</option>
                <option value="aprovado"
                    {{ old('resultado_final', $resultadoSugerido) === 'aprovado' ? 'selected' : '' }}>
                    Aprovado
                </option>
                <option value="aprovado_com_ressalvas"
                    {{ old('resultado_final', $resultadoSugerido) === 'aprovado_com_ressalvas' ? 'selected' : '' }}>
                    Aprovado com Ressalvas
                </option>
                <option value="reprovado"
                    {{ old('resultado_final', $resultadoSugerido) === 'reprovado' ? 'selected' : '' }}>
                    Reprovado
                </option>
            </select>
            @error('resultado_final')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="parecer_final" style="font-weight: bold;">
                Parecer Final / Texto da Ata:
            </label><br>
            <textarea name="parecer_final" id="parecer_final" rows="8" cols="70"
                      placeholder="Registre aqui o resumo das considerações e justificativa do veredito da banca..."
                      required>{{ old('parecer_final') }}</textarea>
            @error('parecer_final')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <a href="{{ route('bancas.show', $banca) }}">Cancelar</a>
        <button type="submit"
            onclick="return confirm('Confirmar o fechamento da banca? Esta ação não pode ser desfeita.')">
            Confirmar Fechamento e Gerar Ata
        </button>
    </form>

</body>
</html>
