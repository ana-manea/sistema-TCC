@extends('layouts.app')

@section('title', 'Detalhes da Reunião')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-0">Detalhes da Reunião</h1>
            <small class="text-muted">{{ $reuniao->tcc->tema ?? 'TCC #' . $reuniao->tcc_id }}</small>
        </div>

        <a href="{{ route('reunioes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered align-middle mb-0">
                <tr>
                    <th width="30%">TCC</th>
                    <td>{{ $reuniao->tcc->tema ?? 'TCC #' . $reuniao->tcc_id }}</td>
                </tr>
                <tr>
                    <th>Data/Hora</th>
                    <td>{{ optional($reuniao->data_hora)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Local / Link</th>
                    <td>{{ $reuniao->local ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $reuniao->status)) }}</td>
                </tr>
                <tr>
                    <th>Observações</th>
                    <td>{{ $reuniao->observacoes ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Próximos passos</th>
                    <td>{{ $reuniao->proximos_passos ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    @if(auth()->user()->funcao !== 'orientando')
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('reunioes.edit', $reuniao) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Editar
            </a>

            <form action="{{ route('reunioes.destroy', $reuniao) }}" method="POST"
                  onsubmit="return confirm('Excluir esta reunião?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Excluir
                </button>
            </form>
        </div>
    @endif
@endsection
