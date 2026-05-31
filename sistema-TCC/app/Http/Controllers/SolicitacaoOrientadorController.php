<?php

namespace App\Http\Controllers;

use App\Models\SolicitacaoOrientador;
use App\Models\Orientador;
use App\Models\Orientando;
use Illuminate\Http\Request;

class SolicitacaoOrientadorController extends Controller
{
    /**
     * VISÃO DO PROFESSOR: Lista as solicitações que o professor recebeu
     */
    public function index(Orientador $orientador)
    {
        $solicitacoesOrientador = SolicitacaoOrientador::where(
        'orientador_id',
        $orientador->id
        )
        ->with('orientando.user')
        ->latest()
        ->get();

        return view(
            'solicitacoes_orientador.index',
            compact('solicitacoesOrientador', 'orientador')
        );
    } 

    /**
     * VISÃO DO ALUNO: Salva a solicitação vinda do formulário de escolha
     */
    public function store(Request $request)
    {
    // 1. Validação dos dados
    $dados = $request->validate([
        'orientando_id' => ['required', 'integer'],
        'orientador_id' => ['required', 'integer'],
        'mensagem'      => ['nullable', 'string'],
    ]);

    // 2. REGRA: Verifica se já existe uma solicitação pendente para este par aluno/professor
    $solicitacaoExistente = SolicitacaoOrientador::where('orientando_id', $dados['orientando_id'])
        ->where('orientador_id', $dados['orientador_id'])
        ->where('status', 'pendente')
        ->exists();

    if ($solicitacaoExistente) {
        return redirect()
            ->back()
            ->withErrors(['orientador_id' => 'Você já possui uma solicitação pendente para este orientador. Aguarde a resposta.']);
    }

    // 3. Salva apenas se não existir duplicidade
    SolicitacaoOrientador::create($dados);

    return redirect()
        ->route('solicitacoes_orientando.index')
        ->with('sucesso', 'Solicitação de orientação enviada com sucesso!');
    }

    /**
     * VISÃO DO PROFESSOR: Processa a decisão de Aceitar ou Recusar
     */
    public function responder(Request $request, SolicitacaoOrientador $solicitacaoOrientador)
    {
        $request->validate([
            'status'   => ['required', 'in:aceita,recusada'], // Alinhado com o ENUM do banco
            'resposta' => ['nullable', 'string', 'max:500'],
        ]);

        $solicitacaoOrientador->update([
            'status'        => $request->status,
            'resposta'      => $request->resposta,
            'respondido_em' => now(),
        ]);

        // Se aceitou, atualiza a tabela de orientandos vinculando o ID do professor
        if ($request->status === 'aceita') {
            $orientando = $solicitacaoOrientador->orientando;
            if ($orientando) {
                $orientando->update([
                    'orientador_id' => $solicitacaoOrientador->orientador_id
                ]);
            }
        }

        $mensagem = $request->status === 'aceita' 
            ? 'Solicitação aceita com sucesso!' 
            : 'Solicitação recusada com sucesso!';

        return redirect()
            ->route('solicitacoes_orientador.index')
            ->with('sucesso', $mensagem);
    }

    /**
     * VISÃO DO ALUNO: Lista o histórico de pedidos para ele acompanhar o status
     */
    public function indexOrientando()
    {
        // TEMPORÁRIO PARA TESTES: Busca o primeiro aluno do banco
        $orientando = Orientando::first();

        if (!$orientando) {
            return "Nenhum orientando encontrado no banco de dados para testar.";
        }

        // Busca as solicitações que este aluno enviou
        $solicitacoes = SolicitacaoOrientador::where('orientando_id', $orientando->id)
            ->with('orientador.user')
            ->latest()
            ->get();

        return view('solicitacoes_orientando.index', compact('solicitacoes'));
    }

    /**
     * VISÃO DO ALUNO: Exibe o formulário com professores com vagas para pedir orientação
     */
    public function createOrientando()
    {
        // Busca o aluno de teste
        $orientando = Orientando::first();

        // Carrega os orientadores calculando as vagas disponíveis em tempo real
        $orientadores = Orientador::with('user')
            ->withCount(['orientandos']) // Conta quantos alunos já estão vinculados
            ->get()
            ->filter(function ($orientador) {
                // CORRIGIDO: Usando a sua coluna real 'max_orientandos'
                $limiteMaximo = $orientador->max_orientandos;

                // MATEMÁTICA: Limite máximo menos a quantidade atual de orientandos
                $vagasDisponiveis = $limiteMaximo - $orientador->orientandos_count;
                
                // Injeta o cálculo no objeto para o Blade conseguir ler
                $orientador->vagas_disponiveis = $vagasDisponiveis;
                
                // Só joga na lista se o professor tiver pelo menos 1 vaga livre
                return $vagasDisponiveis > 0;
            });

        return view('solicitacoes_orientando.create', compact('orientando', 'orientadores'));
    }
}