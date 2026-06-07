@csrf

<div class="mb-3">
    <label class="form-label">Entrega <span class="text-danger">*</span></label>
    <select class="form-select" name="entrega_id" required>
        <option value="">— Selecione a entrega —</option>
        @foreach($entregas as $entrega)
            <option value="{{ $entrega->id }}"
                @selected(old('entrega_id') == $entrega->id)>
                {{ $entrega->titulo }}
                @if($entrega->tcc)
                    — {{ $entrega->tcc->tema }}
                @endif
            </option>
        @endforeach
    </select>
    @error('entrega_id') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Arquivo <span class="text-danger">*</span></label>
    <input class="form-control" type="file" name="arquivo" required>
    <div class="form-text">Tamanho máximo: 20 MB.</div>
    @error('arquivo') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Versão</label>
    <input class="form-control" type="number" name="versao" min="1"
        value="{{ old('versao', 1) }}"
        placeholder="Ex: 1, 2, 3...">
    @error('versao') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Observação</label>
    <textarea class="form-control" name="observacao" rows="3"
        placeholder="Descreva o que foi alterado nessa versão...">{{ old('observacao') }}</textarea>
    @error('observacao') <div class="text-danger small">{{ $message }}</div> @enderror
</div>