@extends('layouts.app')

@section('title', 'Arquivos de Entrega')

@section('content')
@php
    $corValidacao = [
        'pendente'  => 'secondary',
        'validado'  => 'success',
        'rejeitado' => 'danger',
    ];
    $funcao = auth()->user()->funcao;
@endphp

<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota'        => 'dashboard',
        'pagAnterior' => 'ao Dashboard',
        'pagAtual'    => 'Arquivos de Entrega'
    ])

    <div>
        <a class="btn btn-primary" href="{{ route('arquivos_entrega.create') }}">
            <i class="bi bi-upload"></i> Enviar Arquivo
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($arquivosEntrega->isEmpty())
            <div class="alert alert-secondary mb-0">
                <i class="bi bi-info-circle"></i> Nenhum arquivo enviado ainda.
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Entrega</th>
                        <th>TCC</th>
                        <th>Versão</th>
                        <th>Arquivo</th>
                        <th>Enviado por</th>
                        <th>Validação</th>
                        <th>Data</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arquivosEntrega as $arquivo)
                    <tr>
                        <td>{{ $arquivo->entrega->titulo ?? '—' }}</td>
                        <td class="fw-medium">{{ $arquivo->entrega->tcc->tema ?? '—' }}</td>
                        <td><span class="badge bg-secondary">v{{ $arquivo->versao }}</span></td>
                        <td>
                            <a href="{{ Storage::url($arquivo->arquivo_path) }}"
                                target="_blank" class="text-decoration-none">
                                <i class="bi bi-file-earmark-arrow-down"></i>
                                {{ basename($arquivo->arquivo_path) }}
                            </a>
                        </td>
                        <td>{{ $arquivo->enviadoPor->name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $corValidacao[$arquivo->status_validacao] ?? 'secondary' }}">
                                {{ ucfirst($arquivo->status_validacao) }}
                            </span>
                        </td>
                        <td>{{ $arquivo->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary"
                                href="{{ route('arquivos_entrega.edit', $arquivo) }}">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>

                            {{-- Excluir: somente admin (controller bloqueia os demais com 403) --}}
                            @if($funcao === 'admin')
                                <form class="d-inline"
                                    action="{{ route('arquivos_entrega.destroy', $arquivo) }}"
                                    method="POST"
                                    onsubmit="return confirm('Deseja excluir este arquivo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
