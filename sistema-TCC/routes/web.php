<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AvaliacaoBancaController;
use App\Http\Controllers\BancaController;
use App\Http\Controllers\OrientadorController;
use App\Http\Controllers\OrientandoController;
use App\Http\Controllers\TccController;
use App\Http\Controllers\SolicitacaoOrientadorController;


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


Route::get('/bancas/{banca}/avaliar', [AvaliacaoBancaController::class, 'criar'])->name('avaliacoes.criar');


Route::post('/bancas/{banca}/avaliar', [AvaliacaoBancaController::class, 'store'])->name('avaliacoes.store');


Route::resource('bancas', BancaController::class);


Route::get('/bancas/{banca}/fechamento', [BancaController::class, 'telaFechamento'])->name('bancas.telaFechamento');


Route::post('/bancas/{banca}/fechar', [BancaController::class, 'fecharBanca'])->name('bancas.fechar');


Route::get('/bancas/{banca}/ata', [BancaController::class, 'mostrarAta'])->name('bancas.ata');


Route::resource('users', UserController::class);

Route::resource('orientandos', OrientandoController::class)->except(['create','store']);
Route::resource('orientadores', OrientadorController::class)
    ->parameters(['orientadores' => 'orientador'])
    ->except(['create', 'store']);
Route::resource('solicitacoes_orientador', SolicitacaoOrientadorController::class);