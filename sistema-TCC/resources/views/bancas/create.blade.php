<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Nova Banca</title>
</head>
<body>

    <h2>Cadastrar Nova Banca Avaliadora</h2>
    <a href="{{ route('bancas.index') }}">← Voltar</a>
    <hr>

    @if(session('erro'))
        <p style="color: red;"><strong>{{ session('erro') }}</strong></p>
    @endif

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('bancas.store') }}" method="POST">
        @csrf

        {{--
            Regra do documento: o admin seleciona entre TCCs disponíveis (sem banca),
            e o sistema exibe orientando e orientador vinculados ao selecionar.
        --}}
        <div style="margin-bottom: 15px;">
            <label for="tcc_id" style="font-weight: bold;">TCC:</label><br>
            <select name="tcc_id" id="tcc_id" required onchange="mostrarInfoTcc(this)">
                <option value="">-- Selecione um TCC disponível --</option>
                @forelse($tccsDisponiveis as $tcc)
                    <option value="{{ $tcc->id }}"
                        data-orientador="{{ $tcc->orientador?->user?->name ?? 'Sem orientador' }}"
                        data-orientandos="{{ $tcc->orientandos->map(fn($o) => $o->user?->name)->filter()->join(', ') ?: 'Sem orientando vinculado' }}"
                        {{ old('tcc_id') == $tcc->id ? 'selected' : '' }}>
                        #{{ $tcc->id }} — {{ $tcc->tema }}
                    </option>
                @empty
                    <option disabled>Nenhum TCC disponível para banca no momento.</option>
                @endforelse
            </select>
            @error('tcc_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        {{-- Painel de informações do TCC selecionado --}}
        <div id="info-tcc" style="display:none; background:#f9f9f9; border:1px solid #ddd; padding:10px; margin-bottom:15px;">
            <strong>Orientador:</strong> <span id="info-orientador"></span><br>
            <strong>Orientando(s):</strong> <span id="info-orientandos"></span>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="data_hora" style="font-weight: bold;">Data e Hora da Apresentação:</label><br>
            <input type="datetime-local" name="data_hora" id="data_hora"
                   value="{{ old('data_hora') }}" required>
            @error('data_hora')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="local" style="font-weight: bold;">Local ou Link:</label><br>
            <input type="text" name="local" id="local"
                   placeholder="Ex: Sala 4 ou https://meet.google.com/..."
                   value="{{ old('local') }}" required>
            @error('local')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <a href="{{ route('bancas.index') }}">Cancelar</a>
            <button type="submit">Agendar Banca</button>
        </div>
    </form>

    <script>
        // Mostra orientador e orientando ao selecionar o TCC
        function mostrarInfoTcc(select) {
            const option = select.options[select.selectedIndex];
            const painel = document.getElementById('info-tcc');

            if (select.value) {
                document.getElementById('info-orientador').textContent  = option.dataset.orientador;
                document.getElementById('info-orientandos').textContent = option.dataset.orientandos;
                painel.style.display = 'block';
            } else {
                painel.style.display = 'none';
            }
        }

        // Restaura painel se houver old() após erro de validação
        window.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('tcc_id');
            if (select.value) mostrarInfoTcc(select);
        });
    </script>

</body>
</html>
