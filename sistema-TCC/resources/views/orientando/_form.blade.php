@csrf

<div class="student-block border rounded p-3 mb-3">
    <div class="mb-2 fw-bold">Dados do Orientando</div>
    <div class="mb-3">
        <label class="form-label">Matrícula (RA) *</label>
        <input type="text" name="matricula" class="form-control @error('matricula') is-invalid @enderror" value="{{ old('matricula', $orientando->matricula ?? '') }}" required>
        @error('matricula') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Curso *</label>
        <input type="text" name="curso" class="form-control @error('curso') is-invalid @enderror" value="{{ old('curso', $orientando->curso ?? '') }}" required>
        @error('curso') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Semestre</label>
        <input type="number" name="semestre" class="form-control @error('semestre') is-invalid @enderror" min="1" max="6" value="{{ old('semestre', $orientando->semestre ?? '') }}">
        @error('semestre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

</div>

@if(isset($orientando) && isset($orientadores))
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Solicitar Orientador</h5>
            <form action="{{ route('solicitacoes_orientador.store') }}" method="POST" class="row g-2">
                @csrf
                <input type="hidden" name="orientando_id" value="{{ $orientando->id }}">

                <div class="col-md-6">
                    <label class="form-label">Orientador desejado</label>
                    <select name="orientador_id" class="form-select">
                        <option value="">Qualquer um</option>
                        @foreach($orientadores as $orientador)
                            <option value="{{ $orientador->id }}">{{ $orientador->user->name ?? '—' }} ({{ $orientador->area_atuacao ?? '' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mensagem (opcional)</label>
                    <input type="text" name="mensagem" class="form-control" placeholder="Breve motivo ou observação">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-sm btn-primary">Enviar solicitação</button>
                </div>
            </form>
        </div>
    </div>
@endif