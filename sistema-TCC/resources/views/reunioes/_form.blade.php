@csrf

<div class="mb-3">
    <label class="form-label">TCC</label>
    <select name="tcc_id" class="form-select @error('tcc_id') is-invalid @enderror" required>
        <option value="">Selecione o TCC</option>
        @foreach($tccs as $tcc)
            <option value="{{ $tcc->id }}" @selected(old('tcc_id', $reuniao->tcc_id ?? '') == $tcc->id)>
                {{ $tcc->tema }}
            </option>
        @endforeach
    </select>
    @error('tcc_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Data e Hora</label>
    <input type="datetime-local"
           name="data_hora"
           class="form-control @error('data_hora') is-invalid @enderror"
           value="{{ old('data_hora', isset($reuniao) && $reuniao->data_hora ? $reuniao->data_hora->format('Y-m-d\TH:i') : '') }}"
           required>
    @error('data_hora') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Local ou Link</label>
    <input type="text"
           name="local"
           class="form-control @error('local') is-invalid @enderror"
           value="{{ old('local', $reuniao->local ?? '') }}">
    @error('local') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    @php($statusAtual = old('status', $reuniao->status ?? 'agendada'))
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
        <option value="agendada" @selected($statusAtual === 'agendada')>Agendada</option>
        <option value="realizada" @selected($statusAtual === 'realizada')>Realizada</option>
        <option value="cancelada" @selected($statusAtual === 'cancelada')>Cancelada</option>
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Observações</label>
    <textarea name="observacoes" rows="4" class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes', $reuniao->observacoes ?? '') }}</textarea>
    @error('observacoes') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Próximos passos</label>
    <textarea name="proximos_passos" rows="4" class="form-control @error('proximos_passos') is-invalid @enderror">{{ old('proximos_passos', $reuniao->proximos_passos ?? '') }}</textarea>
    @error('proximos_passos') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
