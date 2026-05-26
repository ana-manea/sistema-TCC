<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TccController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BancaController;
use App\Http\Controllers\AvaliacaoBancaController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tccs', TccController::class);


Route::resource('bancas', BancaController::class);


Route::get('/bancas/{banca}/avaliar', [AvaliacaoBancaController::class, 'criar'])->name('avaliacoes.criar');


Route::post('/bancas/{banca}/avaliar', [AvaliacaoBancaController::class, 'store'])->name('avaliacoes.store');


Route::resource('users', UserController::class);