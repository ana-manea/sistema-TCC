<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Banca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mb-5">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Editar Banca Avaliadora</h4>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('bancas.update', $banca->id) }}" method="POST">
                        @csrf 
                        @method('PUT') <div class="mb-3">
                            <label for="tcc_id" class="form-label">Cód. TCC</label>
                            <input type="number" name="tcc_id" id="tcc_id" class="form-control" value="{{ $banca->tcc_id }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="data_hora" class="form-label">Data e Hora</label>
                            <input type="datetime-local" name="data_hora" id="data_hora" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($banca->data_hora)) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="local" class="form-label">Local</label>
                            <input type="text" name="local" id="local" class="form-control" value="{{ $banca->local }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status da Banca</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="agendada" {{ $banca->status == 'agendada' ? 'selected' : '' }}>Agendada</option>
                                <option value="realizada" {{ $banca->status == 'realizada' ? 'selected' : '' }}>Realizada</option>
                                <option value="cancelada" {{ $banca->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>

                        <hr class="my-4">
                        <h5 class="text-muted">Avaliação e Fechamento (Preencher se Realizada)</h5>

                        <div class="mb-3">
                            <label for="resultado_final" class="form-label">Resultado Final</label>
                            <select name="resultado_final" id="resultado_final" class="form-select">
                                <option value="" {{ is_null($banca->resultado_final) ? 'selected' : '' }}>-- Selecione se houver --</option>
                                <option value="aprovado" {{ $banca->resultado_final == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                <option value="aprovado_com_ressalvas" {{ $banca->resultado_final == 'aprovado_com_ressalvas' ? 'selected' : '' }}>Aprovado com Ressalvas</option>
                                <option value="reprovado" {{ $banca->resultado_final == 'reprovado' ? 'selected' : '' }}>Reprovado</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nota_final" class="form-label">Nota Final</label>
                            <input type="number" name="nota_final" id="nota_final" class="form-control" step="0.01" min="0" max="10" value="{{ $banca->nota_final }}" placeholder="Ex: 8.50">
                        </div>

                        <div class="mb-3">
                            <label for="parecer_final" class="form-label">Parecer Final</label>
                            <textarea name="parecer_final" id="parecer_final" class="form-control" rows="3" placeholder="Considerações da banca avaliadora...">{{ $banca->parecer_final }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('bancas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Banca</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>