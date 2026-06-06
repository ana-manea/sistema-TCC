@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')

@php
    $nomes = explode(' ', trim($user->name));

    $iniciais = strtoupper(
        substr($nomes[0], 0, 1) .
        (count($nomes) > 1 ? substr(end($nomes), 0, 1) : '')
    );

    $corAvatar = $user->avatar ?? '#b20000';

    $orientador = $user->orientador;
    $orientando = $user->orientando;
@endphp

<div class="d-flex flex-column mb-4">
    <div class="d-flex justify-content-between align-items-start">
        @include('layouts.voltar_titulo', [
            'rota' => 'dashboard',
            'pagAnterior' => 'ao Dashboard',
            'pagAtual' => 'Meu Perfil'
        ])
        <a href="{{ route('users.perfil.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Editar Perfil
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <div class="text-center mb-4">

            <div class="avatar-user mx-auto"
                    @style(['background-color: ' . $corAvatar])>

                {{ $iniciais }}

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-bordered align-middle">

                <tbody>

                    <tr>
                        <th width="30%">Nome</th>
                        <td>{{ $user->name }}</td>
                    </tr>

                    <tr>
                        <th>E-mail</th>
                        <td>{{ $user->email }}</td>
                    </tr>

                    <tr>
                        <th>Função</th>
                        <td>
                            <span class="badge bg-dark">
                                {{ ucfirst(str_replace('_', ' ', $user->funcao)) }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Cor do Avatar</th>
                        <td>

                            <span class="d-inline-flex align-items-center gap-2">

                                <span
                                    @style([
                                        'width:20px',
                                        'height:20px',
                                        'border-radius:50%',
                                        'background-color:' . $corAvatar,
                                        'border:1px solid #ccc'
                                    ])>
                                </span>

                                {{ $corAvatar }}

                            </span>

                        </td>
                    </tr>

                    {{-- ===================================================== --}}
                    {{-- ADMIN --}}
                    {{-- ===================================================== --}}

                    @if($user->funcao === 'admin')

                        <tr>
                            <th>Tipo de acesso</th>
                            <td>
                                Controle total do sistema.
                            </td>
                        </tr>

                    @endif

                    {{-- ===================================================== --}}
                    {{-- ORIENTADOR --}}
                    {{-- ===================================================== --}}

                    @if($user->funcao === 'orientador' && $orientador)

                        <tr>
                            <th>Área de atuação</th>
                            <td>
                                {{ $orientador->area_atuacao }}
                            </td>
                        </tr>

                        <tr>
                            <th>Disponibilidade</th>
                            <td>
                                {{ $orientador->disponibilidade ?: '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Máximo de orientandos</th>
                            <td>
                                {{ $orientador->max_orientandos }}
                            </td>
                        </tr>

                        <tr>
                            <th>Orientandos atuais</th>
                            <td>
                                {{ $orientador->orientandos()->count() }}
                            </td>
                        </tr>

                        <tr>
                            <th>Vagas disponíveis</th>
                            <td>

                                {{
                                    max(
                                        $orientador->max_orientandos
                                        - $orientador->orientandos()->count(),
                                        0
                                    )
                                }}

                            </td>
                        </tr>

                        <tr>
                            <th>Orientandos vinculados</th>

                            <td>

                                @forelse($orientador->orientandos as $aluno)

                                    <div class="mb-2">

                                        <strong>
                                            {{ $aluno->user->name ?? '-' }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            Matrícula:
                                            {{ $aluno->matricula }}

                                        </small>

                                    </div>

                                @empty

                                    Nenhum orientando vinculado.

                                @endforelse

                            </td>
                        </tr>

                    @endif

                    {{-- ===================================================== --}}
                    {{-- ORIENTANDO --}}
                    {{-- ===================================================== --}}

                    @if($user->funcao === 'orientando' && $orientando)

                        <tr>
                            <th>Matrícula</th>
                            <td>
                                {{ $orientando->matricula }}
                            </td>
                        </tr>

                        <tr>
                            <th>Curso</th>
                            <td>
                                {{ $orientando->curso }}
                            </td>
                        </tr>

                        <tr>
                            <th>Semestre</th>
                            <td>
                                {{ $orientando->semestre ?: '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Orientador</th>
                            <td>

                                {{ $orientando->orientador?->user?->name ?? 'Sem orientador vinculado' }}

                            </td>
                        </tr>

                    @endif

                    {{-- ===================================================== --}}
                    {{-- MEMBRO DA BANCA --}}
                    {{-- ===================================================== --}}

                    @if($user->funcao === 'membro_banca')

                        <tr>
                            <th>Participação em bancas</th>

                            <td>

                                @php
                                    $bancas = $user->bancaMembros ?? [];
                                @endphp

                                @forelse($bancas as $membro)

                                    <div class="mb-2">

                                        <strong>
                                            {{ $membro->papel }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            Banca #{{ $membro->banca_id }}

                                        </small>

                                    </div>

                                @empty

                                    Nenhuma banca vinculada.

                                @endforelse

                            </td>
                        </tr>

                    @endif

                    <tr>
                        <th>Cadastrado em</th>

                        <td>
                            {{ $user->created_at?->format('d/m/Y H:i') }}
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection