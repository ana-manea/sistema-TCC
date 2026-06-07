@extends('layouts.app')

@section('title', 'Novo Feedback')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="bi bi-chat-left-text"></i> Novo Feedback
        </div>
        <div class="card-body">
            <form action="{{ route('feedbacks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tcc_id" value="{{ $tccId }}">

                @include('feedbacks._form')

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar Feedback</button>
                    <a href="{{ route('tccs.show', $tccId) }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
