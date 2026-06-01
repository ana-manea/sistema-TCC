@csrf

<div class="mb-3">
    <label class="form-label">TCC <span class="text-danger">*</span></label>
    <select class="form-select" name="tcc_id" required>
        <option value="">— Selecione o TCC —</option>
        @foreach($tccs as $tcc)
            <option value="{{ $tcc->id }}"
                @selected(old('tcc_id', $reuniao->tcc_id ?? '') == $tcc->id)>
                #{{ $tcc->id }} — {{ $tcc->tema }}
            </option>
        @endforeach
    </select>
    @error('tcc_id') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Data e Hora <span class="text-danger">*</span></label>
    <input class="form-control" type="datetime-local" name="data_hora"
        value="{{ old('data_hora', isset($reuniao->data_hora) ? $reuniao->data_hora->format('Y-m-d\TH:i') : '') }}"
        required>
    @error('data_hora') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Local</label>
    <input class="form-control" type="text" name="local"
        placeholder="Ex: Sala 3-B ou https://meet.google.com/..."
        value="{{ old('local', $reuniao->local ?? '') }}">
    @error('local') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select class="form-select" name="status" required>
        <option value="agendada"  @selected(old('status', $reuniao->status ?? 'agendada') == 'agendada')>Agendada</option>
        <option value="realizada" @selected(old('status', $reuniao->status ?? '') == 'realizada')>Realizada</option>
        <option value="cancelada" @selected(old('status', $reuniao->status ?? '') == 'cancelada')>Cancelada</option>
    </select>
    @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

{{-- Campos de pós-reunião — visíveis apenas no edit --}}
@isset($reuniao)
<hr class="my-3">
<p class="text-muted small mb-3"><i class="bi bi-pencil-square"></i> Preencha após a reunião ser realizada.</p>

<div class="mb-3">
    <label class="form-label">Observações</label>
    <textarea class="form-control" name="observacoes" rows="3"
        placeholder="Anotações feitas durante a reunião...">{{ old('observacoes', $reuniao->observacoes ?? '') }}</textarea>
    @error('observacoes') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Próximos Passos</label>
    <textarea class="form-control" name="proximos_passos" rows="3"
        placeholder="O que foi combinado para fazer depois...">{{ old('proximos_passos', $reuniao->proximos_passos ?? '') }}</textarea>
    @error('proximos_passos') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
@endisset
