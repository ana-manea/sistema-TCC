@csrf

@php
    $membroAtual = isset($banca)
        ? $banca->membros->pluck('user_id', 'papel')
        : collect();
@endphp

<div class="mb-3">
    <label class="form-label">TCC</label>
    <select name="tcc_id" class="form-select @error('tcc_id') is-invalid @enderror" required>
        <option value="">Selecione o TCC</option>
        @foreach($tccs as $tcc)
            @php($orientando = $tcc->orientandos->first())
            <option value="{{ $tcc->id }}" @selected(old('tcc_id', $banca->tcc_id ?? '') == $tcc->id)>
                {{ $tcc->tema }} — Orientando: {{ $orientando?->user?->name ?? 'Sem orientando' }} — Orientador: {{ $tcc->orientador?->user?->name ?? 'Sem orientador' }}
            </option>
        @endforeach
    </select>
    @error('tcc_id') <div class="invalid-feedback">{{ $message }}</div> @enderror

    @if($tccs->isEmpty())
        <div class="form-text text-danger">Nenhum TCC disponível para cadastro de banca.</div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Data/Hora da apresentação</label>
    <input
        type="datetime-local"
        name="data_hora"
        class="form-control @error('data_hora') is-invalid @enderror"
        value="{{ old('data_hora', isset($banca) && $banca->data_hora ? $banca->data_hora->format('Y-m-d\TH:i') : '') }}"
        required
    >
    @error('data_hora') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Local ou Link</label>
    <input
        type="text"
        name="local"
        class="form-control @error('local') is-invalid @enderror"
        value="{{ old('local', $banca->local ?? '') }}"
        placeholder="Ex: Sala 4 ou Link"
        required
    >
    @error('local') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@if(isset($banca))
    <div class="mb-3">
        <label class="form-label">Status</label>
        @php($statusAtual = old('status', $banca->status ?? 'agendada'))
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(['agendada' => 'Agendada', 'realizada' => 'Realizada', 'cancelada' => 'Cancelada'] as $valor => $label)
                <option value="{{ $valor }}" @selected($statusAtual === $valor)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

<hr>
<h5 class="mb-3">Membros da banca</h5>

@if(session('erro'))
    <div class="alert alert-danger">{{ session('erro') }}</div>
@endif

@error('membros') <div class="alert alert-danger">{{ $message }}</div> @enderror

<div class="mb-3">
    <label class="form-label">Presidente</label>
    <select name="presidente_id" class="form-select @error('presidente_id') is-invalid @enderror" required>
        <option value="">Selecione o presidente</option>
        @foreach($membrosBanca as $membro)
            <option value="{{ $membro->id }}" @selected((int) old('presidente_id', $membroAtual->get('presidente')) === (int) $membro->id)>
                {{ $membro->name }} — {{ $membro->email }}
            </option>
        @endforeach
    </select>
    @error('presidente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Membro interno</label>
    <select name="membro_interno_id" class="form-select @error('membro_interno_id') is-invalid @enderror" required>
        <option value="">Selecione o membro interno</option>
        @foreach($membrosBanca as $membro)
            <option value="{{ $membro->id }}" @selected((int) old('membro_interno_id', $membroAtual->get('membro_interno')) === (int) $membro->id)>
                {{ $membro->name }} — {{ $membro->email }}
            </option>
        @endforeach
    </select>
    @error('membro_interno_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Membro externo</label>
    <select name="membro_externo_id" class="form-select @error('membro_externo_id') is-invalid @enderror" required>
        <option value="">Selecione o membro externo</option>
        @foreach($membrosBanca as $membro)
            <option value="{{ $membro->id }}" @selected((int) old('membro_externo_id', $membroAtual->get('membro_externo')) === (int) $membro->id)>
                {{ $membro->name }} — {{ $membro->email }}
            </option>
        @endforeach
    </select>
    @error('membro_externo_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
