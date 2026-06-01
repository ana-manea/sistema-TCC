<?php

namespace App\Http\Controllers;

use App\Models\SolicitacaoOrientador;
use App\Models\Orientador;
use Illuminate\Http\Request;

class SolicitacaoOrientadorController extends Controller
{
    /**
     * PROFESSOR:
     * Lista as solicitações recebidas (exceto recusadas)
     */
    public function index()
    {
        $orientador = auth()->user()?->orientador;

        if (!$orientador) {
            return redirect()
                ->route('dashboard')
                ->withErrors([
                    'msg' => 'Orientador não encontrado.'
                ]);
        }

        $solicitacoesOrientador = SolicitacaoOrientador::where(
                'orientador_id',
                $orientador->id
            )
            ->whereIn('status', ['pendente', 'aceita'])
            ->with('orientando.user')
            ->latest()
            ->get();

        return view(
            'solicitacoes_orientador.index',
            compact('solicitacoesOrientador', 'orientador')
        );
    }

    /**
     * ALUNO:
     * Lista histórico das solicitações
     */
    public function indexOrientando()
    {
        $orientando = auth()->user()?->orientando;

        if (!$orientando) {
            return redirect()
                ->route('dashboard.orientando')
                ->withErrors([
                    'msg' => 'Orientando não encontrado.'
                ]);
        }

        $solicitacoes = SolicitacaoOrientador::where(
                'orientando_id',
                $orientando->id
            )
            ->with('orientador.user')
            ->latest()
            ->get();

        return view(
            'solicitacoes_orientando.index',
            compact('solicitacoes')
        );
    }

    /**
     * ALUNO:
     * Formulário para solicitar orientação
     */
    public function createOrientando()
    {
        $orientando = auth()->user()?->orientando;

        if (!$orientando) {
            return redirect()
                ->route('dashboard.orientando')
                ->withErrors([
                    'msg' => 'Orientando não encontrado.'
                ]);
        }

        $orientadores = Orientador::with('user')
            ->withCount('orientandos')
            ->get()
            ->filter(function ($orientador) {
                $limiteMaximo = $orientador->max_orientandos;
                $vagasDisponiveis = $limiteMaximo - $orientador->orientandos_count;
                $orientador->vagas_disponiveis = $vagasDisponiveis;
                return $vagasDisponiveis > 0;
            });

        return view(
            'solicitacoes_orientando.create',
            compact('orientando', 'orientadores')
        );
    }

    /**
     * ALUNO:
     * Salva solicitação
     */
    public function store(Request $request)
    {
        $orientando = auth()->user()?->orientando;

        if (!$orientando) {
            return redirect()
                ->back()
                ->withErrors([
                    'msg' => 'Orientando não encontrado.'
                ]);
        }

        $dados = $request->validate([
            'orientador_id' => ['required', 'integer', 'exists:orientadores,id'],
            'mensagem'      => ['nullable', 'string'],
        ]);

        $solicitacaoExistente = SolicitacaoOrientador::where(
                'orientando_id',
                $orientando->id
            )
            ->where(
                'orientador_id',
                $dados['orientador_id']
            )
            ->exists();

        if ($solicitacaoExistente) {
            return redirect()
                ->back()
                ->withErrors([
                    'orientador_id' =>
                        'Você já enviou uma solicitação para este orientador.'
                ]);
        }

        SolicitacaoOrientador::create([
            'orientando_id' => $orientando->id,
            'orientador_id' => $dados['orientador_id'],
            'mensagem'      => $dados['mensagem'],
        ]);

        return redirect()
            ->route('solicitacoes_orientando.index')
            ->with('sucesso', 'Solicitação enviada com sucesso!');
    }

    /**
     * PROFESSOR:
     * Aceitar ou recusar solicitação
     */
    public function responder(
        Request $request,
        SolicitacaoOrientador $solicitacaoOrientador
    ) {
        $request->validate([
            'status' => [
                'required',
                'in:aceita,recusada'
            ],
            'resposta' => [
                'nullable',
                'string',
                'max:500'
            ],
        ]);

        $solicitacaoOrientador->update([
            'status'        => $request->status,
            'resposta'      => $request->resposta,
            'respondido_em' => now(),
        ]);

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
            ->route('solicitacoes_orientador.index', ['orientador' => $solicitacaoOrientador->orientador_id])
            ->with('sucesso', $mensagem);
    }
}