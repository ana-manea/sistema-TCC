<?php

namespace App\Http\Controllers;

use App\Models\SolicitacaoOrientador;
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
        return view('dashboard.admin', ['user' => Auth::user()]);
    }

    public function orientador()
    {
        return view('dashboard.orientador', ['user' => Auth::user()]);
    }

    public function orientando()
    {
        $user = Auth::user();

        $solicitacao = null;

        if ($user->orientando) {
            $solicitacao = SolicitacaoOrientador::where('orientando_id', $user->orientando->id)
                ->latest()
                ->first();
        }

        return view('dashboard.orientando', compact('user', 'solicitacao'));
    }

    public function banca()
    {
        return view('dashboard.banca', ['user' => Auth::user()]);
    }
}