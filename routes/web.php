<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\EncargadoController;

Route::get('/', function () {
    return view('welcome');

});


Route::resources(['personas'=> PersonaController::class]);
Route::resources(['encargados'=> EncargadoController::class]);
