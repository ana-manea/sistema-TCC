@csrf

<div class="mb-3">
    <label class="form-label">Tema</label>
    <input class="form-control" type="text" name="tema"
           value="{{ old('tema', $tcc->tema ?? '') }}">
    @error('tema') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Orientador</label>
    <select class="form-select" name="orientador_id">
        <option value="">— Sem orientador —</option>
        @foreach($orientadores as $orientador)
            <option value="{{ $orientador->id }}"
                @selected(old('orientador_id', $tcc->orientador_id ?? '') == $orientador->id)>
                {{ $orientador->user?->name ?? 'Usuário não encontrado' }}
            </option>
        @endforeach
    </select>
    @error('orientador_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Descrição</label>
    <textarea class="form-control" name="descricao" rows="5">{{ old('descricao', $tcc->descricao ?? '') }}</textarea>
    @error('descricao') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select class="form-select" name="status">
        <option value="em_andamento" @selected(old('status', $tcc->status ?? '') == 'em_andamento')>Em andamento</option>
        <option value="concluido"    @selected(old('status', $tcc->status ?? '') == 'concluido')>Concluído</option>
        <option value="cancelado"    @selected(old('status', $tcc->status ?? '') == 'cancelado')>Cancelado</option>
        <option value="suspenso"     @selected(old('status', $tcc->status ?? '') == 'suspenso')>Suspenso</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

{{-- Campo de observação aparece apenas na edição para registrar motivo da mudança de status --}}
@isset($tcc)
<div class="mb-3">
    <label class="form-label">Observação sobre a alteração</label>
    <p class="mb-0 text-muted small">(preenchida automaticamente no histórico ao mudar o status)</p>

    <textarea class="form-control" name="observacao" rows="2"
              placeholder="Ex: Status alterado após reunião de orientação.">{{ old('observacao') }}</textarea>
    @error('observacao') <div class="form-error">{{ $message }}</div> @enderror
</div>
@endisset
