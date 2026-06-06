<div>
    <div class="d-flex justify-content-start gap-4 align-items-center mb-3">
        <a href="{{ route($rota, $variavel ?? []) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar {{ $pagAnterior }}
        </a>
        <h1 class="h3 mb-0 text-gray-800">{{ $pagAtual}}</h1>
    </div>
    <small class="text-muted">{!! $descricao ?? '' !!}</small>
</div>