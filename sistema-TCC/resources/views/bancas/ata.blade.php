@extends('layouts.app')

@section('title', 'Ata da Banca')

@section('content')
    <div class="d-flex align-items-start justify-content-between mb-3">
        @include('layouts.voltar_titulo', [
            'rota' => 'bancas.show', 
            'variavel' => $banca,
            'pagAnterior' => 'aos Detalhes',
            'pagAtual' => 'Ata da Banca',
            'descricao' => $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id
        ])

        <div>
            <button class="btn btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer"></i> Imprimir
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="h4 text-center mb-4">ATA DE DEFESA DE TRABALHO DE CONCLUSÃO DE CURSO</h2>

            <h3 class="h5">Dados do TCC</h3>
            <table class="table table-bordered align-middle">
                <tr>
                    <th width="30%">Tema</th>
                    <td>{{ $banca->tcc->tema ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Descrição</th>
                    <td>{{ $banca->tcc->descricao ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Orientador</th>
                    <td>{{ $banca->tcc->orientador?->user?->name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Orientando(s)</th>
                    <td>
                        @forelse($banca->tcc->orientandos as $orientando)
                            {{ $orientando->user?->name }}@if(!$loop->last), @endif
                        @empty
                            —
                        @endforelse
                    </td>
                </tr>
            </table>

            <h3 class="h5 mt-4">Dados da Banca</h3>
            <table class="table table-bordered align-middle">
                <tr>
                    <th width="30%">Data e Hora</th>
                    <td>{{ optional($banca->data_hora)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Local / Link</th>
                    <td>{{ $banca->local }}</td>
                </tr>
            </table>

            <h3 class="h5 mt-4">Membros da Banca Avaliadora</h3>
            <ul class="list-group mb-4">
                @foreach($banca->membros as $membro)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $membro->user?->name ?? 'ID ' . $membro->user_id }}</span>
                        <strong>{{ ucfirst(str_replace('_', ' ', $membro->papel)) }}</strong>
                    </li>
                @endforeach
            </ul>

            <h3 class="h5 mt-4">Avaliações Individuais</h3>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Avaliador</th>
                            <th>Nota</th>
                            <th>Parecer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banca->avaliacoes->unique('avaliador_id') as $avaliacao)
                            <tr>
                                <td>{{ $avaliacao->avaliador?->name ?? '—' }}</td>
                                <td>{{ number_format((float) $avaliacao->nota, 2, ',', '.') }}</td>
                                <td>{{ $avaliacao->parecer }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h3 class="h5 mt-4">Resultado e Deliberação Final</h3>
            <ul class="list-group mb-4">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Nota Final</span>
                    <strong>{{ number_format((float) $banca->nota_final, 2, ',', '.') }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Resultado Final</span>
                    <strong>{{ strtoupper(str_replace('_', ' ', $banca->resultado_final)) }}</strong>
                </li>
            </ul>

            <h4 class="h6">Parecer Final / Justificativa da Banca</h4>
            <p class="border rounded p-3 bg-light">{{ $banca->parecer_final }}</p>

            <hr>
            <p class="small text-muted mb-0">
                Documento encerrado e assinado eletronicamente pelo Presidente da Banca.
            </p>
        </div>
    </div>
@endsection
