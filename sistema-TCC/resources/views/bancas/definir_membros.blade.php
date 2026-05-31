<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Membros da Banca</title>
</head>
<body>

    <h1>Definir Membros da Banca</h1>
    <p><strong>TCC:</strong> {{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}</p>
    <a href="{{ route('bancas.show', $banca) }}">← Voltar para a banca</a>
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

    {{-- Aviso sobre o orientador bloqueado --}}
    @if($banca->tcc->orientador)
        <p style="color: orange;">
            <strong>Atenção:</strong>
            O orientador <strong>{{ $banca->tcc->orientador->user?->name ?? '—' }}</strong>
            não pode ser membro avaliador desta banca.
        </p>
    @endif

    {{-- Aviso se não houver membros disponíveis --}}
    @if($usuariosDisponiveis->isEmpty())
        <p style="color: red;">
            Não há usuários com função "Membro da Banca" cadastrados no sistema.
            Cadastre usuários com essa função antes de definir a banca.
        </p>
    @else
        <form action="{{ route('bancas.salvarMembros', $banca) }}" method="POST">
            @csrf

            {{-- Presidente --}}
            <div style="margin-bottom: 15px;">
                <label for="presidente" style="font-weight: bold; display: block;">
                    Presidente da Banca
                    <small style="font-weight: normal;">(responsável por confirmar a realização e fechar a banca)</small>:
                </label>
                <select name="presidente" id="presidente" required>
                    <option value="">-- Selecione o Presidente --</option>
                    @foreach($usuariosDisponiveis as $usuario)
                        <option value="{{ $usuario->id }}"
                            {{ old('presidente', optional($banca->bancaMembros->firstWhere('papel', 'presidente'))->user_id) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
                @error('presidente')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>

            {{-- Membro Interno --}}
            <div style="margin-bottom: 15px;">
                <label for="membro_interno" style="font-weight: bold; display: block;">
                    Membro Interno:
                </label>
                <select name="membro_interno" id="membro_interno" required>
                    <option value="">-- Selecione o Membro Interno --</option>
                    @foreach($usuariosDisponiveis as $usuario)
                        <option value="{{ $usuario->id }}"
                            {{ old('membro_interno', optional($banca->bancaMembros->firstWhere('papel', 'membro_interno'))->user_id) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
                @error('membro_interno')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>

            {{-- Membro Externo --}}
            <div style="margin-bottom: 15px;">
                <label for="membro_externo" style="font-weight: bold; display: block;">
                    Membro Externo:
                </label>
                <select name="membro_externo" id="membro_externo" required>
                    <option value="">-- Selecione o Membro Externo --</option>
                    @foreach($usuariosDisponiveis as $usuario)
                        <option value="{{ $usuario->id }}"
                            {{ old('membro_externo', optional($banca->bancaMembros->firstWhere('papel', 'membro_externo'))->user_id) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
                @error('membro_externo')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>

            <a href="{{ route('bancas.show', $banca) }}">Cancelar</a>
            <button type="submit">Salvar Membros</button>
        </form>
    @endif

</body>
</html>
