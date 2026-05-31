@csrf

<div class="mb-3">
    <label class="form-label">TCC</label>
    <select class="form-select" name="tcc_id">
        <option value="">— Selecione o TCC —</option>
        @foreach($tccs as $tcc)
            <option value="{{ $tcc->id }}" @selected(old('tcc_id', $tarefa->tcc_id ?? '') == $tcc->id)>
                {{ $tcc->tema }}
            </option>
        @endforeach
    </select>
    @error('tcc_id') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Titulo</label>
    <input class="form-control" type="text" name="titulo" value="{{ old('titulo', $tarefa->titulo ?? '') }}">
    @error('titulo') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Descricao</label>
    <textarea class="form-control" name="descricao" rows="4">{{ old('descricao', $tarefa->descricao ?? '') }}</textarea>
    @error('descricao') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Prazo</label>
    <input class="form-control" type="date" name="prazo" value="{{ old('prazo', isset($tarefa->prazo) ? $tarefa->prazo->format('Y-m-d') : '') }}">
    @error('prazo') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select class="form-select" name="status">
        <option value="pendente" @selected(old('status', $tarefa->status ?? 'pendente') == 'pendente')>Pendente</option>
        <option value="em_andamento" @selected(old('status', $tarefa->status ?? '') == 'em_andamento')>Em andamento</option>
        <option value="concluida" @selected(old('status', $tarefa->status ?? '') == 'concluida')>Concluida</option>
        <option value="atrasada" @selected(old('status', $tarefa->status ?? '') == 'atrasada')>Atrasada</option>
    </select>
    @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
