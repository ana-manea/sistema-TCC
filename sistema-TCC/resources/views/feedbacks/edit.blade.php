@extends('layouts.app')


@section('content')
<div class="container mt-4">
    <h3>Editar Feedback</h3>
    <form action="{{ route('feedbacks.update', $feedback->id) }}" method="POST">
        @csrf
        @method('PUT')


        @include('feedbacks._form')


        <button type="submit" class="btn btn-success">Atualizar Feedback</button>
        <a href="{{ route('feedbacks.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
