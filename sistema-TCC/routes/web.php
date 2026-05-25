<?php

use App\Http\Controllers\TccController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tccs', TccController::class);


