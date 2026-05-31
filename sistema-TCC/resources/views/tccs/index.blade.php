@extends('layouts.app')

@section('title', 'Trabalhos')

@section('content')
<div class="container-fluid py-4">
    @if(session('sucesso'))
        <div class="flash">{{ session('sucesso') }}</div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Trabalhos de Conclusão de Curso</h1>

        <a class="btn btn-primary" href="{{ route('tccs.create') }}">
            <i class="bi bi-plus-circle"></i> Novo Trabalho
        </a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
        @if($tccs->isEmpty())
            <div class="alert alert-secondary">Nenhum trabalho cadastrado.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tema</th>
                            <th>Orientador</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Resultado Final</th>
                            <th>Nota Final</th>
                            <th>Criado em</th>
                            <th>Atualizado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tccs as $trabalho)
                            <tr>
                                <td>{{ $trabalho->tema }}</td>
                                <td>{{ $trabalho->orientador_id }}</td>
                                <td>{{ $trabalho->descricao }}</td>
                                <td>{{ $trabalho->status }}</td>
                                <td>{{ $trabalho->resultado_final }}</td>
                                <td>{{ $trabalho->nota_final }}</td> <!-- colocar verificação de acordo com status para mostrar isso só depois de ser avaliado -->
                                <td>{{ $trabalho->created_at }}</td>
                                <td>{{ $trabalho->updated_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection