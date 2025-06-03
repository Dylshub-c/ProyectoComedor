<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EspecialidadesController;
use App\Http\Controllers\PropiedadesController;
use App\Http\Controllers\SeccionesController;


Route::get('/panel', function () {
    return view('panel'); // Make sure you have a 'panel.blade.php' view
})->name('panel');

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas para el manejo de especialidades
route::resource('especialidades', EspecialidadesController::class)->except(['show', 'edit', 'update', 'destroy']);
Route::get('especialidades', [EspecialidadesController::class, 'index'])->name('especialidades.index');
Route::get('especialidades/importar', [EspecialidadesController::class, 'formImportar'])->name('especialidades.importar.form');
Route::post('especialidades/importar', [EspecialidadesController::class, 'importar'])->name('especialidades.importar');

// Rutas para las secciones
route::resource('secciones', SeccionesController::class)->except(['show', 'edit', 'update', 'destroy']);
Route::get('secciones', [SeccionesController::class, 'index'])->name('secciones.index');
Route::get('secciones/importar', [SeccionesController::class, 'formImportar'])->name('secciones.importar.form');
Route::post('secciones/importar', [SeccionesController::class, 'importar'])->name('secciones.importar');
