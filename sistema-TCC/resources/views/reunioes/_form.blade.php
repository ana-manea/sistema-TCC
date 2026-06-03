@csrf

@php
    $dataHoraValor = '';
    if (!empty($reuniao?->data_hora)) {
        $dataHoraValor = \Carbon\Carbon::parse($reuniao->data_hora)->format('Y-m-d\TH:i');
    }
@endphp

<div class="mb-3">
    <label class="form-label">TCC</label>
    <select class="form-select" name="tcc_id" required>
        <option value="">— Selecione —</option>
        @foreach($tccs as $tcc)
            @php
                $orientadorNome = $tcc->orientador?->user?->name ?? '—';
                $orientandosNomes = $tcc->orientandos
                    ->map(function ($orientando) {
                        return $orientando->user?->name;
                    })
                    ->filter()
                    ->implode(', ');
                $orientandosLabel = $orientandosNomes !== '' ? $orientandosNomes : '—';
            @endphp
            <option value="{{ $tcc->id }}" @selected(old('tcc_id', $reuniao->tcc_id ?? '') == $tcc->id)>
                {{ $tcc->tema }} — Orientador: {{ $orientadorNome }} — Orientando(s): {{ $orientandosLabel }}
            </option>
        @endforeach
    </select>
    @error('tcc_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Data e hora</label>
    <input class="form-control" type="datetime-local" name="data_hora"
           value="{{ old('data_hora', $dataHoraValor) }}" required>
    @error('data_hora') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Local</label>
    <input class="form-control" type="text" name="local"
           value="{{ old('local', $reuniao->local ?? '') }}" maxlength="255">
    @error('local') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select class="form-select" name="status" required>
        <option value="agendada" @selected(old('status', $reuniao->status ?? 'agendada') == 'agendada')>Agendada</option>
        <option value="realizada" @selected(old('status', $reuniao->status ?? '') == 'realizada')>Realizada</option>
        <option value="cancelada" @selected(old('status', $reuniao->status ?? '') == 'cancelada')>Cancelada</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Observações</label>
    <textarea class="form-control" name="observacoes" rows="3">{{ old('observacoes', $reuniao->observacoes ?? '') }}</textarea>
    @error('observacoes') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Próximos passos</label>
    <textarea class="form-control" name="proximos_passos" rows="3">{{ old('proximos_passos', $reuniao->proximos_passos ?? '') }}</textarea>
    @error('proximos_passos') <div class="form-error">{{ $message }}</div> @enderror
</div>
