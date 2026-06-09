<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Tcc;
use App\Notifications\FeedbackRecebidoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class FeedbackController extends Controller
{
    /**
     * LISTAGEM DO ORIENTADOR
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->funcao === 'orientando') {
            return $this->indexOrientando();
        }

        if ($user->funcao !== 'orientador' || !$user->orientador) {
            abort(403, 'Somente orientadores visualizam feedbacks enviados.');
        }

        $feedbacks = Feedback::with(['tcc', 'orientador.user'])
            ->where('orientador_id', $user->orientador->id)
            ->latest()
            ->get();

        return view('feedbacks.index', [
            'feedbacks' => $feedbacks,
            'modo'      => 'orientador',
        ]);
    }

    /**
     * LISTAGEM DO ORIENTANDO
     */
    public function indexOrientando()
    {
        $user = Auth::user();

        if ($user->funcao !== 'orientando' || !$user->orientando) {
            abort(403, 'Somente orientandos visualizam feedbacks recebidos.');
        }

        $tccIds = $user->orientando->tccs()->pluck('tccs.id');

        $feedbacks = Feedback::with(['tcc', 'orientador.user'])
            ->whereIn('tcc_id', $tccIds)
            ->latest()
            ->get();

        return view('feedbacks.index', [
            'feedbacks' => $feedbacks,
            'modo'      => 'orientando',
        ]);
    }

    /**
     * FORMULÁRIO DE CRIAÇÃO
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->funcao !== 'orientador' || !$user->orientador) {
            abort(403, 'Somente orientadores podem criar feedbacks.');
        }

        $tccId = $request->query('tcc_id');

        // Se o valor estiver nulo, o formulário não saberá qual TCC está recebendo o feedback
        if (!$tccId) {
            // Log para ajudar a debugar
            Log::error('Tentativa de criar feedback sem tcc_id', [
            'user_id' => $user->id,
            'url'     => $request->fullUrl(),
            'query'   => $request->query(),
        ]);
            return redirect()->back()->withErrors(['msg' => 'TCC não identificado.']);
        }

        $tcc = Tcc::findOrFail($tccId);

        if ((int) $tcc->orientador_id !== (int) $user->orientador->id) {
            abort(403, 'Você só pode criar feedback para TCCs orientados por você.');
        }

        return view('feedbacks.create', compact('tccId'));
    }
    
    /**
     * SALVAR FEEDBACK
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->funcao !== 'orientador' || !$user->orientador) {
            abort(403, 'Somente orientadores podem criar feedbacks.');
        }

        $dados = $request->validate([
            'tcc_id'    => ['required', 'integer', 'exists:tccs,id'],
            'descricao' => ['required', 'string'],
        ]);

        $tcc = Tcc::findOrFail($dados['tcc_id']);

        if ((int) $tcc->orientador_id !== (int) $user->orientador->id) {
            abort(403, 'Você só pode criar feedback para TCCs orientados por você.');
        }

        $dados['orientador_id'] = $user->orientador->id;

        $feedback = Feedback::create($dados);
        $feedback->load(['tcc.orientandos.user', 'orientador.user']);

        foreach ($feedback->tcc?->orientandos ?? [] as $orientando) {
            if ($orientando->user) {
                $orientando->user->notify(new FeedbackRecebidoNotification($feedback));
            }
        }

        return redirect()
            ->route('tccs.show', $dados['tcc_id'])
            ->with('sucesso', 'Feedback enviado com sucesso!');
    }

    /**
     * FORMULÁRIO DE EDIÇÃO
     */
    public function edit(Feedback $feedback)
    {
        $this->autorizarOrientadorDoFeedback($feedback);

        return view('feedbacks.edit', compact('feedback'));
    }

    /**
     * ATUALIZAR FEEDBACK
     */
    public function update(Request $request, Feedback $feedback)
    {
        $this->autorizarOrientadorDoFeedback($feedback);

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
        $this->autorizarOrientadorDoFeedback($feedback);

        $tccId = $feedback->tcc_id;
        $feedback->delete();

        return redirect()
            ->route('tccs.show', $tccId)
            ->with('sucesso', 'Feedback removido!');
    }

    // Limita quem pode editar o Feedback
    private function autorizarOrientadorDoFeedback(Feedback $feedback): void
    {
        $user = Auth::user();

        if ($user->funcao !== 'orientador' || !$user->orientador || (int) $feedback->orientador_id !== (int) $user->orientador->id) {
            abort(403, 'Você só pode alterar os próprios feedbacks.');
        }
    }
}