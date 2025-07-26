<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\EncargadoController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TipoBecaController;

Route::get('/', function () {
    return view('Auth/login'); // Make sure you have a 'login.blade.php' view

});


Route::get('/estudiantes', [EstudiantesController::class, 'index'])->name('estudiantes.index');
// Mostrar el formulario
Route::get('estudiantes/importar', [EstudiantesController::class, 'formImportar'])->name('estudiantes.importar.form');

// Procesar la importación
Route::post('estudiantes/importar', [EstudiantesController::class, 'importar'])->name('estudiantes.importar');


// Rutas para el controlador de estudiantes
Route::get('/estudiantes/informacion', [EstudiantesController::class, 'informacion']);
Route::post('/estudiantes/informacion', [EstudiantesController::class, 'informacion'])
    ->name('estudiantes.informacion');
Route::put('/estudiantes/{persona}', [EstudiantesController::class, 'update'])
    ->name('estudiantes.update');
Route::get('/estudiantes/create', [EstudiantesController::class, 'create'])
    ->name('estudiantes.create');
Route::post('/estudiantes', [EstudiantesController::class, 'store'])
    ->name('estudiantes.store');
Route::delete('/estudiantes/{persona}', [EstudiantesController::class, 'destroy'])
    ->name('estudiantes.destroy');


//controlador de tipoBeca
Route::get('/tipobeca', [TipoBecaController::class, 'index'])->name('tipobeca.index');
Route::get('/tipobeca/create', [TipoBecaController::class, 'create'])->name('tipobeca.create');
Route::post('/tipobeca', [TipoBecaController::class, 'store'])->name('tipobeca.store');
Route::get('/tipobeca/{id}/edit', [TipoBecaController::class, 'edit'])->name('tipobeca.edit');
Route::put('/tipobeca/{id}', [TipoBecaController::class, 'update'])->name('tipobeca.update');
Route::delete('/tipobeca/{id}', [TipoBecaController::class, 'destroy'])->name('tipobeca.destroy');


Route::get('/subir-fotos', [FotoController::class, 'showForm'])->name('subir-fotos.form');
Route::post('/subir-fotos', [FotoController::class, 'importarFotos'])->name('subir-fotos.importar');


Route::get('/admin/forgot-password', [PasswordResetController::class, 'showResetForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [PasswordResetController::class, 'reset'])->name('admin.password.reset');

Route::get('/admin/cambio-contra', [PasswordResetController::class, 'confirmReset'])
    ->name('admin.password.confirm')
    ->middleware('signed'); // Verifica que el enlace no haya sido manipulado



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Ruta protegida para el admin
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('admin.home');
});



