<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoBanca;
use App\Models\Banca;
use App\Models\Entrega;
use App\Models\Orientador;
use App\Models\Orientando;
use App\Models\Reuniao;
use App\Models\SolicitacaoOrientador;
use App\Models\Tarefa;
use App\Models\Tcc;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->funcao) {
            'admin' => redirect()->route('dashboard.admin'),
            'orientador' => redirect()->route('dashboard.orientador'),
            'orientando' => redirect()->route('dashboard.orientando'),
            'membro_banca' => redirect()->route('dashboard.banca'),
            default => abort(403),
        };
    }

    public function admin()
    {
        $user = User::findOrFail(Auth::id());

        $indicadores = [
            'usuarios' => User::count(),
            'orientadores' => Orientador::count(),
            'orientandos' => Orientando::count(),
            'tccs' => Tcc::count(),
            'bancas' => Banca::count(),
            'entregas_pendentes' => Entrega::whereIn('status', ['pendente', 'atrasada'])->count(),
        ];

        $opcoes = $this->opcoesAdmin();

        return view('dashboard.admin', compact('user', 'indicadores', 'opcoes'));
    }

    public function orientador()
    {
        $user = User::findOrFail(Auth::id());
        $orientador = $user->orientador;

        $indicadores = [
            'orientandos' => $orientador ? $orientador->orientandos()->count() : 0,
            'tccs' => $orientador ? $orientador->tccs()->count() : 0,
            'solicitacoes' => $orientador
                ? SolicitacaoOrientador::where('orientador_id', $orientador->id)
                    ->where('status', 'pendente')
                    ->count()
                : 0,
            'tarefas' => $orientador
                ? Tarefa::whereHas('tcc', fn ($q) => $q->where('orientador_id', $orientador->id))
                    ->whereIn('status', ['pendente', 'em_andamento', 'atrasada'])
                    ->count()
                : 0,
        ];

        $opcoes = $this->opcoesOrientador();

        return view('dashboard.orientador', compact('user', 'orientador', 'indicadores', 'opcoes'));
    }

    public function orientando()
    {
        $user = User::findOrFail(Auth::id());
        $orientando = $user->orientando;

        $solicitacao = $orientando
            ? SolicitacaoOrientador::where('orientando_id', $orientando->id)->latest()->first()
            : null;

        $tccIds = $orientando ? $orientando->tccs()->pluck('tccs.id') : collect();

        $indicadores = [
            'tccs' => $tccIds->count(),
            'tarefas' => $tccIds->isNotEmpty()
                ? Tarefa::whereIn('tcc_id', $tccIds)
                    ->whereIn('status', ['pendente', 'em_andamento', 'atrasada'])
                    ->count()
                : 0,
            'entregas' => $tccIds->isNotEmpty()
                ? Entrega::whereIn('tcc_id', $tccIds)->count()
                : 0,
            'reunioes' => $tccIds->isNotEmpty()
                ? Reuniao::whereIn('tcc_id', $tccIds)->count()
                : 0,
        ];

        $opcoes = $this->opcoesOrientando();

        return view('dashboard.orientando', compact('user', 'orientando', 'solicitacao', 'indicadores', 'opcoes'));
    }

    public function banca()
    {
        $user = User::findOrFail(Auth::id());

        $indicadores = [
            'bancas' => $user->bancaMembros()->count(),
            'avaliacoes' => AvaliacaoBanca::where('avaliador_id', $user->id)->count(),
        ];

        $opcoes = $this->opcoesBanca();

        return view('dashboard.banca', compact('user', 'indicadores', 'opcoes'));
    }

    private function opcoesAdmin(): array
    {
        return [
            ['opcao' => 'Usuários', 'rota' => route('users.index'), 'routeName' => 'users', 'icone' => 'bi bi-people'],
            ['opcao' => 'TCCs', 'rota' => route('tccs.index'), 'routeName' => 'tccs', 'icone' => 'bi bi-journal-text'],
            ['opcao' => 'TCCs em andamento', 'rota' => route('tccs.em_andamento'), 'routeName' => 'tccs.em_andamento', 'icone' => 'bi bi-hourglass-split'],
            ['opcao' => 'Bancas', 'rota' => route('bancas.index'), 'routeName' => 'bancas', 'icone' => 'bi bi-award'],
            ['opcao' => 'Reuniões', 'rota' => route('reunioes.index'), 'routeName' => 'reunioes', 'icone' => 'bi bi-calendar-event'],
            //['opcao' => 'Entregas', 'rota' => route('entregas.index'), 'routeName' => 'entregas', 'icone' => 'bi bi-folder'],
            ['opcao' => 'Tarefas', 'rota' => route('tarefas.index'), 'routeName' => 'tarefas', 'icone' => 'bi bi-check2-square'],
        ];
    }

    private function opcoesOrientador(): array
    {
        $orientador = Auth::user()->orientador;

        return [
            ['opcao' => 'Meus orientandos', 'rota' => $orientador ? route('orientador.meus_orientandos', $orientador) : route('dashboard.orientador'), 'routeName' => 'orientador.meus_orientandos', 'icone' => 'bi bi-people'],
            ['opcao' => 'TCCs orientados', 'rota' => route('tccs.index'), 'routeName' => 'tccs', 'icone' => 'bi bi-journal-text'],
            ['opcao' => 'TCCs em andamento', 'rota' => route('tccs.em_andamento'), 'routeName' => 'tccs.em_andamento', 'icone' => 'bi bi-hourglass-split'],
            ['opcao' => 'Reuniões', 'rota' => route('orientador.reunioes.index'), 'routeName' => 'orientador.reunioes', 'icone' => 'bi bi-calendar-event'],
            ['opcao' => 'Tarefas', 'rota' => route('tarefas.index'), 'routeName' => 'tarefas', 'icone' => 'bi bi-check2-square'],
            ['opcao' => 'Solicitações', 'rota' => $orientador ? route('solicitacoes_orientador.index', $orientador) : route('dashboard.orientador'), 'routeName' => 'solicitacoes_orientador', 'icone' => 'bi bi-envelope'],
        ];
    }

    private function opcoesOrientando(): array
    {
        return [
            ['opcao' => 'Meu TCC', 'rota' => route('tccs.index'), 'routeName' => 'tccs', 'icone' => 'bi bi-journal-text'],
            ['opcao' => 'TCCs em andamento', 'rota' => route('tccs.em_andamento'), 'routeName' => 'tccs.em_andamento', 'icone' => 'bi bi-hourglass-split'],
            ['opcao' => 'Tarefas', 'rota' => route('aluno.tarefas.index'), 'routeName' => 'aluno.tarefas', 'icone' => 'bi bi-check2-square'],
            ['opcao' => 'Reuniões', 'rota' => route('aluno.reunioes.index'), 'routeName' => 'aluno.reunioes', 'icone' => 'bi bi-calendar-event'],
            //['opcao' => 'Entregas', 'rota' => route('aluno.entregas.index'), 'routeName' => 'aluno.entregas', 'icone' => 'bi bi-folder'],
            ['opcao' => 'Solicitar orientador', 'rota' => route('solicitacoes_orientando.index'), 'routeName' => 'solicitacoes_orientando', 'icone' => 'bi bi-person-plus'],
        ];
    }

    private function opcoesBanca(): array
    {
        return [
            ['opcao' => 'Minhas bancas', 'rota' => route('bancas.index'), 'routeName' => 'bancas', 'icone' => 'bi bi-award'],
            ['opcao' => 'TCCs recebidos', 'rota' => route('tccs.index'), 'routeName' => 'tccs', 'icone' => 'bi bi-file-earmark-text'],
            ['opcao' => 'Avaliações', 'rota' => route('bancas.index'), 'routeName' => 'bancas', 'icone' => 'bi bi-clipboard-check'],
        ];
    }
}
