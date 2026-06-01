@csrf

<div class="mb-3">
    <label class="form-label">TCC <span class="text-danger">*</span></label>
    <select class="form-select" name="tcc_id" required>
        <option value="">— Selecione o TCC —</option>
        @foreach($tccs as $tcc)
            <option value="{{ $tcc->id }}"
                @selected(old('tcc_id', $entrega->tcc_id ?? '') == $tcc->id)>
                #{{ $tcc->id }} — {{ $tcc->tema }}
            </option>
        @endforeach
    </select>
    @error('tcc_id') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Título <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="titulo"
        placeholder="Ex: Capítulo 1, Versão Final, Proposta..."
        value="{{ old('titulo', $entrega->titulo ?? '') }}" required>
    @error('titulo') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Descrição</label>
    <textarea class="form-control" name="descricao" rows="3"
        placeholder="Descreva o que deve ser entregue...">{{ old('descricao', $entrega->descricao ?? '') }}</textarea>
    @error('descricao') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Prazo <span class="text-danger">*</span></label>
    <input class="form-control" type="date" name="prazo"
        value="{{ old('prazo', isset($entrega->prazo) ? $entrega->prazo->format('Y-m-d') : '') }}" required>
    @error('prazo') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select class="form-select" name="status" required>
        <option value="pendente"  @selected(old('status', $entrega->status ?? 'pendente') == 'pendente')>Pendente</option>
        <option value="entregue"  @selected(old('status', $entrega->status ?? '') == 'entregue')>Entregue</option>
        <option value="validado"  @selected(old('status', $entrega->status ?? '') == 'validado')>Validado</option>
        <option value="rejeitado" @selected(old('status', $entrega->status ?? '') == 'rejeitado')>Rejeitado</option>
        <option value="atrasado"  @selected(old('status', $entrega->status ?? '') == 'atrasado')>Atrasado</option>
    </select>
    @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
