<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AvaliacaoBancaController;
use App\Http\Controllers\BancaController;
use App\Http\Controllers\OrientadorController;
use App\Http\Controllers\OrientandoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\TccController;
use App\Http\Controllers\SolicitacaoOrientadorController;
use App\Http\Controllers\ReuniaoController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\ArquivoEntregaController;
use App\Models\Orientador;

// Sem autenticação
Route::redirect('/', '/login');

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.attempt');

Route::get('/esqueci-senha', [PasswordResetController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/esqueci-senha', [PasswordResetController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/redefinir-senha/{token}', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/redefinir-senha', [PasswordResetController::class, 'resetPassword'])
    ->name('password.update');


// Com autenticação
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/orientador', [DashboardController::class, 'orientador'])->name('dashboard.orientador');
    Route::get('/dashboard/orientando', [DashboardController::class, 'orientando'])->name('dashboard.orientando');
    Route::get('/dashboard/banca', [DashboardController::class, 'banca'])->name('dashboard.banca');

    Route::get('/perfil', [UserController::class, 'perfil'])->name('users.perfil');
    Route::get('/perfil/editar', [UserController::class, 'editarPerfil'])->name('users.perfil.edit');
    Route::put('/perfil', [UserController::class, 'atualizarPerfil'])->name('users.perfil.update');

    // ── Reuniões ──────────────────────────────────────────────────────────────
    Route::resource('reunioes', ReuniaoController::class);

    Route::get('/orientador/reunioes', [ReuniaoController::class, 'indexOrientador'])
        ->name('orientador.reunioes.index');

    Route::get('/aluno/reunioes', [ReuniaoController::class, 'indexOrientando'])
        ->name('aluno.reunioes.index');

    // ── Entregas ──────────────────────────────────────────────────────────────
    Route::resource('entregas', EntregaController::class);

    Route::resource('arquivos_entrega', ArquivoEntregaController::class);

    Route::get('/aluno/entregas', [EntregaController::class, 'indexOrientando'])
        ->name('aluno.entregas.index');

    // ── Arquivos de Entrega (ADICIONADO) ──────────────────────────────────────
    Route::resource('arquivos_entrega', ArquivoEntregaController::class);

    // ── Bancas e Avaliações ───────────────────────────────────────────────────
    Route::resource('bancas', BancaController::class);

    Route::post('/bancas/{banca}/confirmar-realizada', [BancaController::class, 'confirmarRealizada'])
        ->name('bancas.confirmarRealizada');

    Route::get('/bancas/{banca}/fechamento', [BancaController::class, 'telaFechamento'])
        ->name('bancas.telaFechamento');

    Route::post('/bancas/{banca}/fechar', [BancaController::class, 'fecharBanca'])
        ->name('bancas.fechar');

    Route::get('/bancas/{banca}/ata', [BancaController::class, 'mostrarAta'])
        ->name('bancas.ata');

    Route::get('/bancas/{banca}/avaliar', [AvaliacaoBancaController::class, 'criar'])
        ->name('avaliacoes.criar');

    Route::post('/bancas/{banca}/avaliar', [AvaliacaoBancaController::class, 'store'])
        ->name('avaliacoes.store');

    Route::get('/avaliacoes/{avaliacaoBanca}/editar', [AvaliacaoBancaController::class, 'edit'])
        ->name('avaliacoes.edit');

    Route::put('/avaliacoes/{avaliacaoBanca}', [AvaliacaoBancaController::class, 'update'])
        ->name('avaliacoes.update');

    Route::get('/bancas/{banca}/definir-membros', [BancaController::class, 'telaDefinirMembros'])
        ->name('bancas.definirMembros');

    Route::post('/bancas/{banca}/definir-membros', [BancaController::class, 'salvarMembros'])
        ->name('bancas.salvarMembros');
});

Route::get('/tccs/em-andamento', [TccController::class, 'emAndamento'])
    ->name('tccs.em_andamento');

Route::get('/tccs/{tcc}/historico', [TccController::class, 'historico'])
    ->name('tccs.historico');

Route::resource('tccs', TccController::class);

Route::resource('tarefas', TarefaController::class)->except(['show']);

Route::get('/aluno/tarefas', [TarefaController::class, 'indexOrientando'])
    ->name('aluno.tarefas.index');

Route::resource('users', UserController::class);

Route::resource('orientandos', OrientandoController::class)->except(['create', 'store']);

Route::controller(OrientadorController::class)->group(function () {
    Route::get('/orientador', function () {
        $orientador = Orientador::first();
        return redirect()->route('orientador.dashboard', $orientador->id);
    });

    Route::get('/orientador/{orientador}/dashboard', 'dashboard')
        ->name('orientador.dashboard');

    Route::get('/orientador/{orientador}/meus-orientandos', 'meusOrientandos')
        ->name('orientador.meus_orientandos');
});

Route::resource('orientadores', OrientadorController::class)
    ->parameters(['orientadores' => 'orientador']);

Route::controller(SolicitacaoOrientadorController::class)->group(function () {
    Route::put('/solicitacoes_orientador/{solicitacaoOrientador}/responder', 'responder')
        ->name('solicitacoes_orientador.responder');

    Route::get('/solicitacoes_orientando', 'indexOrientando')
        ->name('solicitacoes_orientando.index');

    Route::get('/solicitacoes_orientando/create', 'createOrientando')
        ->name('solicitacoes_orientando.create');

    Route::get('/orientador/{orientador}/solicitacoes', 'index')
        ->name('solicitacoes_orientador.index');

    Route::post('/solicitacoes_orientador', 'store')
        ->name('solicitacoes_orientador.store');
});
