@extends('layouts.app')

@section('title', 'Editar Feedback')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="bi bi-pencil-square"></i> Editar Feedback
        </div>
        <div class="card-body">
            <form action="{{ route('feedbacks.update', $feedback) }}" method="POST">
                @csrf
                @method('PUT')

                @include('feedbacks._form')

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Atualizar Feedback</button>
                    <a href="{{ route('feedbacks.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
