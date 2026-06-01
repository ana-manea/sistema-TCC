<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Tcc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * LISTAGEM DO ORIENTADOR
     */
    public function index()
    {
        $user = Auth::user();

        $orientador = $user?->orientador;

        if (!$orientador) {
            return redirect()
                ->route('dashboard')
                ->withErrors(['msg' => 'Orientador não encontrado.']);
        }

        $feedbacks = Feedback::with([
                'tcc',
                'orientador.user'
            ])
            ->where('orientador_id', $orientador->id)
            ->latest()
            ->get();

        return view('feedbacks.index', [
            'feedbacks' => $feedbacks,
            'modo' => 'orientador',
        ]);
    }

    /**
     * LISTAGEM DO ORIENTANDO
     */
    public function indexOrientando()
    {
        $user = Auth::user();

        $orientando = $user?->orientando;

        if (!$orientando) {
            return redirect()
                ->route('dashboard.orientando')
                ->withErrors(['msg' => 'Orientando não encontrado.']);
        }

        $tccIds = $orientando->tccs()->pluck('tccs.id');

        $feedbacks = Feedback::with([
                'tcc',
                'orientador.user'
            ])
            ->whereIn('tcc_id', $tccIds)
            ->latest()
            ->get();

        return view('feedbacks.index', [
            'feedbacks' => $feedbacks,
            'modo' => 'orientando',
            'tccId'     => $tccId,
        ]);
    }

    /**
     * FORMULÁRIO DE CRIAÇÃO
     */
    public function create(Request $request)
    {
        $tccId = $request->query('tcc_id'); // Ou $request->input('tcc_id')

        // Se o valor estiver nulo, o formulário não saberá qual TCC está recebendo o feedback
        if (!$tccId) {
            // Log para ajudar a debugar
            \Log::error('Tentativa de criar feedback sem tcc_id');
            return redirect()->back()->withErrors(['msg' => 'TCC não identificado.']);
        }

        return view('feedbacks.create', compact('tccId'));
    }
    /**
     * SALVAR FEEDBACK
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $orientador = $user?->orientador;

        if (!$orientador) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'Orientador não encontrado.']);
        }

        $dados = $request->validate([
            'tcc_id'    => ['required', 'integer', 'exists:tccs,id'],
            'descricao' => ['required', 'string'],
        ]);

        $dados['orientador_id'] = $orientador->id;

        Feedback::create($dados);

        return redirect()
            ->route('tccs.show', $dados['tcc_id'])
            ->with('sucesso', 'Feedback enviado com sucesso!');
    }

    /**
     * FORMULÁRIO DE EDIÇÃO
     */
    public function edit(Feedback $feedback)
    {
        return view('feedbacks.edit', compact('feedback'));
    }

    /**
     * ATUALIZAR FEEDBACK
     */
    public function update(Request $request, Feedback $feedback)
    {
        $dados = $request->validate([
            'descricao' => ['required', 'string'],
        ]);

        $feedback->update($dados);

        return redirect()
            ->route('feedbacks.index')
            ->with('sucesso', 'Feedback atualizado!');
    }

    /**
     * EXCLUIR FEEDBACK
     */
    public function destroy(Feedback $feedback)
    {
        $tccId = $feedback->tcc_id;

        $feedback->delete();

        return redirect()
            ->route('tccs.show', $tccId)
            ->with('sucesso', 'Feedback removido!');
    }
}