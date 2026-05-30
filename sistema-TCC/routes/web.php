<?php

use App\Http\Controllers\AvaliacaoBancaController;
use App\Http\Controllers\BancaController;
use App\Http\Controllers\OrientadorController;
use App\Http\Controllers\OrientandoController;
use App\Http\Controllers\TccController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SolicitacaoOrientadorController;
use App\Models\Orientador;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('tccs', TccController::class);




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