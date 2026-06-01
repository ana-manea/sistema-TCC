@extends('layouts.app')


@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-chat-left-text"></i> Meus Feedbacks</h2>
    </div>


    <div class="card shadow-sm">
        <div class="card-body">
            @forelse($feedbacks as $feedback)
                <div class="list-group list-group-flush">
                    <div class="list-group-item py-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1 text-primary">TCC: {{ $feedback->tcc->tema ?? 'Sem TCC vinculado' }}</h5>
                            <small class="text-muted">{{ $feedback->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mb-2">{{ $feedback->descricao }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('feedbacks.edit', $feedback->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                            <form action="{{ route('feedbacks.destroy', $feedback->id) }}" method="POST" onsubmit="return confirm('Tem certeza?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-2">Nenhum feedback registrado ainda.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
