@csrf

<div>
    <label>Tema</label>
    <input type="text" name="tema"
           value="{{ old('tema', $tcc->tema ?? '') }}">
    @error('tema') <div>{{ $message }}</div> @enderror
</div>

<div>
    <label>Orientador</label>
    <select name="orientador_id">
        <option value="">— Sem orientador —</option>
        @foreach($orientadores as $orientador)
            <option value="{{ $orientador->id }}"
                @selected(old('orientador_id', $tcc->orientador_id ?? '') == $orientador->id)>
                {{ $orientador->user->name }}
            </option>
        @endforeach
    </select>
    @error('orientador_id') <div>{{ $message }}</div> @enderror
</div>

<div>
    <label>Descrição</label>
    <textarea name="descricao" rows="5">{{ old('descricao', $tcc->descricao ?? '') }}</textarea>
    @error('descricao') <div>{{ $message }}</div> @enderror
</div>

<div>
    <label>Status</label>
    <select name="status">
        <option value="em_andamento" @selected(old('status', $tcc->status ?? '') == 'em_andamento')>Em andamento</option>
        <option value="concluido"    @selected(old('status', $tcc->status ?? '') == 'concluido')>Concluído</option>
        <option value="cancelado"    @selected(old('status', $tcc->status ?? '') == 'cancelado')>Cancelado</option>
        <option value="suspenso"     @selected(old('status', $tcc->status ?? '') == 'suspenso')>Suspenso</option>
    </select>
    @error('status') <div>{{ $message }}</div> @enderror
</div>

{{-- Campo de observação aparece apenas na edição para registrar motivo da mudança de status --}}
@isset($tcc)
<div>
    <label>
        Observação sobre a alteração
        <span>(preenchida automaticamente no histórico ao mudar o status)</span>
    </label>
    <textarea name="observacao" rows="2"
              placeholder="Ex: Status alterado após reunião de orientação.">{{ old('observacao') }}</textarea>
    @error('observacao') <div>{{ $message }}</div> @enderror
</div>
@endisset
