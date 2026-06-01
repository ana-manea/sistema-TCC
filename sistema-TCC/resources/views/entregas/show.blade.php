@extends('layouts.app')

@section('title', 'Detalhe da Entrega')

@section('content')
@php
    $corStatus = [
        'pendente'  => 'secondary',
        'entregue'  => 'primary',
        'validado'  => 'success',
        'rejeitado' => 'danger',
        'atrasado'  => 'warning',
    ];
    $corValidacao = [
        'pendente'  => 'secondary',
        'validado'  => 'success',
        'rejeitado' => 'danger',
    ];
@endphp

<div class="container-fluid py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">
            <i class="bi bi-box-seam"></i> {{ $entrega->titulo }}
        </h1>
        <a class="btn btn-outline-secondary" href="{{ route('entregas.index') }}">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    {{-- Dados da Entrega --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">Informações da Entrega</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">TCC</small>
                    <span class="fw-medium">{{ $entrega->tcc->tema ?? '—' }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Prazo</small>
                    <span class="fw-medium">{{ $entrega->prazo->format('d/m/Y') }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Status</small>
                    <span class="badge bg-{{ $corStatus[$entrega->status] ?? 'secondary' }}">
                        {{ ucfirst($entrega->status) }}
                    </span>
                </div>
                @if($entrega->descricao)
                <div class="col-12">
                    <small class="text-muted d-block">Descrição</small>
                    <p class="mb-0">{{ $entrega->descricao }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Arquivos Enviados --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-paperclip"></i> Arquivos Enviados</span>
            <a class="btn btn-sm btn-primary" href="{{ route('arquivos_entrega.create') }}">
                <i class="bi bi-upload"></i> Enviar Arquivo
            </a>
        </div>
        <div class="card-body">
            @if($entrega->arquivos->isEmpty())
                <div class="alert alert-secondary mb-0">
                    <i class="bi bi-info-circle"></i> Nenhum arquivo enviado ainda.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Versão</th>
                            <th>Arquivo</th>
                            <th>Enviado por</th>
                            <th>Observação</th>
                            <th>Validação</th>
                            <th>Data</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entrega->arquivos as $arquivo)
                        <tr>
                            <td><span class="badge bg-secondary">v{{ $arquivo->versao }}</span></td>
                            <td>
                                <a href="{{ Storage::url($arquivo->arquivo_path) }}"
                                    target="_blank" class="text-decoration-none">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                    {{ basename($arquivo->arquivo_path) }}
                                </a>
                            </td>
                            <td>{{ $arquivo->enviadoPor->name ?? '—' }}</td>
                            <td>{{ $arquivo->observacao ? \Str::limit($arquivo->observacao, 40) : '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $corValidacao[$arquivo->status_validacao] ?? 'secondary' }}">
                                    {{ ucfirst($arquivo->status_validacao) }}
                                </span>
                            </td>
                            <td>{{ $arquivo->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('arquivos_entrega.edit', $arquivo) }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form class="d-inline"
                                    action="{{ route('arquivos_entrega.destroy', $arquivo) }}"
                                    method="POST"
                                    onsubmit="return confirm('Excluir este arquivo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
