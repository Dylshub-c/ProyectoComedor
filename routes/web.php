<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\EncargadoController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('Auth/login'); // Make sure you have a 'login.blade.php' view

});


Route::get('/estudiantes', [EstudiantesController::class, 'index'])->name('estudiantes.index');
// Mostrar el formulario
Route::get('estudiantes/importar', [EstudiantesController::class, 'formImportar'])->name('estudiantes.importar.form');

// Procesar la importación
Route::post('estudiantes/importar', [EstudiantesController::class, 'importar'])->name('estudiantes.importar');

Route::get('/estudiantes/informacion', [EstudiantesController::class, 'informacion'])
    ->name('estudiantes.informacion');

Route::put('/estudiantes/{persona}', [EstudiantesController::class, 'update'])
    ->name('estudiantes.update');

Route::delete('/estudiantes/{persona}', [EstudiantesController::class, 'destroy'])
    ->name('estudiantes.destroy');


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



