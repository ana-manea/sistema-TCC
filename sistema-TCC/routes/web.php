<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AvaliacaoBancaController;
use App\Http\Controllers\BancaController;
use App\Http\Controllers\OrientadorController;
use App\Http\Controllers\OrientandoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\TccController;
use App\Http\Controllers\SolicitacaoOrientadorController;
use App\Models\Orientador;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tccs/em-andamento', [TccController::class, 'emAndamento'])->name('tccs.em_andamento');

Route::get('/tccs/{tcc}/historico', [TccController::class, 'historico'])->name('tccs.historico');


// Sem autenticação
Route::redirect('/', '/login');

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.attempt');


// Com autenticação

// Dashboard
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
        
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->name('dashboard.admin');

    Route::get('/dashboard/orientador', [DashboardController::class, 'orientador'])
        ->name('dashboard.orientador');

    Route::get('/dashboard/orientando', [DashboardController::class, 'orientando'])
        ->name('dashboard.orientando');

    Route::get('/dashboard/banca', [DashboardController::class, 'banca'])
        ->name('dashboard.banca');
    
    Route::get('/perfil', [UserController::class, 'perfil'])
        ->name('users.perfil');

    Route::get('/perfil/editar', [UserController::class, 'editarPerfil'])
        ->name('users.perfil.edit');

    Route::put('/perfil', [UserController::class, 'atualizarPerfil'])
        ->name('users.perfil.update');
});

Route::resource('tccs', TccController::class);

Route::resource('tarefas', TarefaController::class)->except(['show']);
Route::get('/aluno/tarefas', [TarefaController::class, 'indexOrientando'])
    ->name('aluno.tarefas.index');


Route::get('/bancas/{banca}/avaliar', [AvaliacaoBancaController::class, 'criar'])->name('avaliacoes.criar');


Route::post('/bancas/{banca}/avaliar', [AvaliacaoBancaController::class, 'store'])->name('avaliacoes.store');


Route::resource('bancas', BancaController::class);


Route::get('/bancas/{banca}/fechamento', [BancaController::class, 'telaFechamento'])->name('bancas.telaFechamento');


Route::post('/bancas/{banca}/fechar', [BancaController::class, 'fecharBanca'])->name('bancas.fechar');


Route::get('/bancas/{banca}/ata', [BancaController::class, 'mostrarAta'])->name('bancas.ata');


Route::resource('users', UserController::class);

Route::resource('orientandos', OrientandoController::class)->except(['create','store']);
Route::controller(OrientadorController::class)->group(function () {

    // pega automaticamente o primeiro orientador
    Route::get('/orientador', function () {

        $orientador = Orientador::first();

        return redirect()->route(
            'orientador.dashboard',
            $orientador->id
        );

    });

    // dashboard
    Route::get('/orientador/{orientador}/dashboard', 'dashboard')
        ->name('orientador.dashboard');

    // meus orientandos
    Route::get('/orientador/{orientador}/meus-orientandos', 'meusOrientandos')
        ->name('orientador.meus_orientandos');
});

Route::resource('orientadores', OrientadorController::class)->parameters(['orientadores' => 'orientador']);;
Route::controller(SolicitacaoOrientadorController::class)->group(function () {

    // Professor responder solicitação
    Route::put(
        '/solicitacoes_orientador/{solicitacaoOrientador}/responder',
        'responder'
    )->name('solicitacoes_orientador.responder');

    // ALUNO
    Route::get(
        '/solicitacoes_orientando',
        'indexOrientando'
    )->name('solicitacoes_orientando.index');

    Route::get(
        '/solicitacoes_orientando/create',
        'createOrientando'
    )->name('solicitacoes_orientando.create');

    // PROFESSOR
    Route::get(
        '/orientador/{orientador}/solicitacoes',
        'index'
    )->name('solicitacoes_orientador.index');

    Route::post(
        '/solicitacoes_orientador',
        'store'
    )->name('solicitacoes_orientador.store');
});

// NOVO: Editar avaliação própria dentro do prazo de 48h
Route::get('/avaliacoes/{avaliacaoBanca}/editar', [AvaliacaoBancaController::class, 'edit'])->name('avaliacoes.edit');
Route::put('/avaliacoes/{avaliacaoBanca}', [AvaliacaoBancaController::class, 'update'])->name('avaliacoes.update');

// NOVO: Definir membros da banca (presidente, interno, externo)
Route::get('/bancas/{banca}/definir-membros', [BancaController::class, 'telaDefinirMembros'])->name('bancas.definirMembros');
Route::post('/bancas/{banca}/definir-membros', [BancaController::class, 'salvarMembros'])->name('bancas.salvarMembros');