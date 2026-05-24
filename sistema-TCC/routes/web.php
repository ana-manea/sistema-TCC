<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BancaController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('bancas', BancaController::class);
