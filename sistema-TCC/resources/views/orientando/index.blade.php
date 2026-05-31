@extends('layouts.app')

@section('title', 'Orientandos')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Orientandos</h1>

        <a class="btn btn-primary" href="{{ route('users.create', ['funcao' => 'orientando']) }}">
            <i class="bi bi-plus-circle"></i> Novo Orientando
        </a>
    </div>

    <!-- success flash handled in layout -->

    @if($orientandos->isEmpty())
        <div class="alert alert-secondary">Nenhum orientando cadastrado.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Matrícula</th>
                        <th>Curso</th>
                        <th>Semestre</th>
                        <th>Orientador</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orientandos as $orientando)
                        <tr>
                            <td class="fw-medium">{{ $orientando->user->name ?? 'Sem usuário' }}</td>
                            <td>{{ $orientando->matricula }}</td>
                            <td>{{ $orientando->curso }}</td>
                            <td>{{ $orientando->semestre ?? '-' }}</td>
                            <td>{{ $orientando->orientador->user->name ?? '-' }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('orientandos.show', $orientando) }}"><i class="bi bi-eye"></i> Ver</a>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('orientandos.edit', $orientando) }}"><i class="bi bi-pencil-square"></i> Editar</a>
                                <form action="{{ route('orientandos.destroy', $orientando) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir este orientando?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i> Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection