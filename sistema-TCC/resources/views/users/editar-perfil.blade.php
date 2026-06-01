@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow-sm">

            <div class="card-header">
                <i class="bi bi-pencil-square"></i>
                Editar Perfil
            </div>

            <div class="card-body">

                <form action="{{ route('users.perfil.update') }}"
                      method="POST"
                      class="vstack gap-3">

                    @csrf
                    @method('PUT')

                    <div>

                        <label class="form-label">
                            Nome
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div>

                        <label class="form-label">
                            E-mail
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror">

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div>

                        <label class="form-label">
                            Nova senha
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror">

                        <small class="text-muted">
                            Deixe vazio para manter a senha atual.
                        </small>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div>

                        <label class="form-label">
                            Cor do Avatar
                        </label>

                        <input type="color"
                               name="avatar"
                               value="{{ old('avatar', $user->avatar ?? '#b20000') }}"
                               class="form-control form-control-color @error('avatar') is-invalid @enderror">

                        @error('avatar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    @if($user->funcao === 'orientador' && $user->orientador)

                        <hr>

                        <h5 class="mb-3">
                            Dados de orientação
                        </h5>

                        <div>

                            <label class="form-label">
                                Área de atuação
                            </label>

                            <input type="text"
                                   name="area_atuacao"
                                   value="{{ old('area_atuacao', $user->orientador->area_atuacao) }}"
                                   class="form-control @error('area_atuacao') is-invalid @enderror">

                            @error('area_atuacao')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div>

                            <label class="form-label">
                                Disponibilidade
                            </label>

                            <textarea name="disponibilidade"
                                      rows="3"
                                      class="form-control @error('disponibilidade') is-invalid @enderror">{{ old('disponibilidade', $user->orientador->disponibilidade) }}</textarea>

                            @error('disponibilidade')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div>

                            <label class="form-label">
                                Máximo de orientandos
                            </label>

                            <input type="number"
                                   name="max_orientandos"
                                   min="1"
                                   value="{{ old('max_orientandos', $user->orientador->max_orientandos) }}"
                                   class="form-control @error('max_orientandos') is-invalid @enderror">

                            @error('max_orientandos')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    @endif

                    @if($user->funcao === 'orientando' && $user->orientando)

                        <hr>

                        <h5 class="mb-3">
                            Dados acadêmicos
                        </h5>

                        <div class="alert alert-secondary">
                            Matrícula, curso e semestre são apenas para visualização e não podem ser alterados pelo aluno.
                        </div>

                        <div>
                            <label class="form-label">Matrícula</label>
                            <input type="text" value="{{ $user->orientando->matricula }}" class="form-control" disabled>
                        </div>

                        <div>
                            <label class="form-label">Curso</label>
                            <input type="text" value="{{ $user->orientando->curso }}" class="form-control" disabled>
                        </div>

                        <div>
                            <label class="form-label">Semestre</label>
                            <input type="number" value="{{ $user->orientando->semestre }}" class="form-control" disabled>
                        </div>

                    @endif

                    <div>

                        <label class="form-label">
                            Função
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ ucfirst(str_replace('_', ' ', $user->funcao)) }}"
                               disabled>

                        <small class="text-muted">
                            A função não pode ser alterada.
                        </small>

                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-check-circle"></i>
                            Salvar alterações

                        </button>

                        <a href="{{ route('users.perfil') }}"
                           class="btn btn-outline-secondary">

                            Voltar

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection