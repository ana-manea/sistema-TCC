<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Nova Banca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Cadastrar Nova Banca Avaliadora</h4>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('bancas.store') }}" method="POST">
                        @csrf 

                        <div class="mb-3">
                            <label for="tcc_id" class="form-label">Cód. TCC</label> 
                            <input type="number" name="tcc_id" id="tcc_id" class="form-control" placeholder="Digite o código do TCC" required>
                        </div>

                        <div class="mb-3">
                            <label for="data_hora" class="form-label">Data e Hora</label>
                            <input type="datetime-local" name="data_hora" id="data_hora" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="local" class="form-label">Local</label>
                            <input type="text" name="local" id="local" class="form-control" placeholder="Ex: Sala 4 ou Link do Meet" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status Inicial</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="agendada">Agendada</option>
                                <option value="realizada">Realizada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('bancas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">Salvar Banca</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>