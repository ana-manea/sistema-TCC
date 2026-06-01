@extends('layouts.app')

@section('title', 'Fechamento da Banca')

@section('content')
    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    Fechamento da Banca — {{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}
                </div>

                <div class="card-body">
                    <a href="{{ route('bancas.show', $banca) }}" class="btn btn-outline-secondary btn-sm mb-3">
                        ← Voltar para a banca
                    </a>

                    @if(session('erro'))
                        <div class="alert alert-danger">
                            {{ session('erro') }}
                        </div>
                    @endif

                    {{-- Resumo das avaliações --}}
                    <h3 class="h5">Avaliações dos Membros</h3>

                    @if($avaliacoes->isEmpty())
                        <div class="alert alert-warning">
                            Nenhuma avaliação lançada ainda. O fechamento não pode ser realizado.
                        </div>
                    @else
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
                                    {{-- Uma linha por avaliador (evita duplicação de duplas) --}}
                                    @foreach($avaliacoes->unique('avaliador_id') as $avaliacao)
                                        <tr>
                                            <td>{{ $avaliacao->avaliador?->name ?? 'ID ' . $avaliacao->avaliador_id }}</td>
                                            <td>{{ number_format((float) $avaliacao->nota, 2, ',', '.') }}</td>
                                            <td>{{ $avaliacao->parecer }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <p>
                            <strong>Média calculada:
                                {{ $mediaCalculada !== null ? number_format((float) $mediaCalculada, 2, ',', '.') : '—' }}
                            </strong>
                        </p>

                        {{--
                            Resultado sugerido automaticamente pelo sistema conforme critérios do documento:
                            >= 7      → Aprovado
                            5 a 6.9  → Aprovado com Ressalvas
                            < 5      → Reprovado
                        --}}
                        @if($resultadoSugerido)
                            <div class="alert alert-secondary">
                                <strong>Resultado sugerido pelo sistema:</strong>
                                @if($resultadoSugerido === 'aprovado')
                                    Aprovado (média ≥ 7)
                                @elseif($resultadoSugerido === 'aprovado_com_ressalvas')
                                    Aprovado com Ressalvas (média entre 5 e 6,9)
                                @else
                                    Reprovado (média < 5)
                                @endif
                            </div>
                        @endif
                    @endif

                    <hr>

                    <h3 class="h5">Veredito Final</h3>
                    <p>Esta ação irá encerrar a banca, salvar a nota final e disponibilizar a ata.</p>

                    <form action="{{ route('bancas.fechar', $banca->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="resultado_final" class="form-label">Resultado Final</label>
                            <select name="resultado_final" id="resultado_final" class="form-select @error('resultado_final') is-invalid @enderror" required>
                                <option value="">-- Selecione --</option>
                                <option value="aprovado" @selected(old('resultado_final', $resultadoSugerido) === 'aprovado')>
                                    Aprovado
                                </option>
                                <option value="aprovado_com_ressalvas" @selected(old('resultado_final', $resultadoSugerido) === 'aprovado_com_ressalvas')>
                                    Aprovado com Ressalvas
                                </option>
                                <option value="reprovado" @selected(old('resultado_final', $resultadoSugerido) === 'reprovado')>
                                    Reprovado
                                </option>
                            </select>
                            @error('resultado_final')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="parecer_final" class="form-label">Parecer Final / Texto da Ata</label>
                            <textarea
                                name="parecer_final"
                                id="parecer_final"
                                rows="8"
                                class="form-control @error('parecer_final') is-invalid @enderror"
                                placeholder="Registre aqui o resumo das considerações e justificativa do veredito da banca..."
                                required
                            >{{ old('parecer_final', $banca->parecer_final) }}</textarea>
                            @error('parecer_final')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button
                                type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Confirmar o fechamento da banca? Esta ação não pode ser desfeita.')"
                                @disabled($avaliacoes->isEmpty())
                            >
                                Confirmar Fechamento e Gerar Ata
                            </button>

                            <a href="{{ route('bancas.show', $banca) }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
