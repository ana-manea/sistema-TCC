@extends('layouts.app')

@section('title', 'Orientadores')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">
            Professores Orientadores
        </h1>
    </div>

    {{-- LISTA VAZIA --}}
    @if($orientadores->isEmpty())

        <div class="alert alert-secondary">
            Nenhum orientador cadastrado.
        </div>

    @else

        <div class="table-responsive">

            <table class="table table-striped align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Área de Atuação</th>
                        <th>Disponibilidade</th>
                        <th>Limite de Orientandos</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($orientadores as $orientador)

                        <tr>

                            <td class="fw-medium">
                                {{ $orientador->user->name ?? 'Sem usuário' }}
                            </td>

                            <td>
                                {{ $orientador->area_atuacao }}
                            </td>

                            <td>
                                {{ $orientador->disponibilidade }}
                            </td>

                            <td>
                                {{ $orientador->max_orientandos }}
                            </td>

                            <td class="text-end">

                                {{-- SHOW --}}
                                <a
                                    class="btn btn-sm btn-outline-secondary"
                                    href="{{ route('orientadores.show', $orientador) }}"
                                >
                                    <i class="bi bi-eye"></i>
                                    Ver
                                </a>

                                {{-- EDIT --}}
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('orientadores.edit', $orientador) }}"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                    Editar
                                </a>

                                {{-- DELETE --}}
                                <form
                                    action="{{ route('orientadores.destroy', $orientador) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Tem certeza que deseja remover este orientador?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        <i class="bi bi-trash"></i>
                                        Excluir
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

@endsection