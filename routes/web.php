<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\EncargadoController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');

});

Route::get('/panel', function () {
    return view('panel'); // Make sure you have a 'panel.blade.php' view
})->name('panel');



Route::get('/estudiantes', [EstudiantesController::class, 'index'])->name('estudiantes.index');
// Mostrar el formulario
Route::get('estudiantes/importar', [EstudiantesController::class, 'formImportar'])->name('estudiantes.importar.form');

// Procesar la importación
Route::post('estudiantes/importar', [EstudiantesController::class, 'importar'])->name('estudiantes.importar');



Route::get('/subir-fotos', [FotoController::class, 'showForm'])->name('subir-fotos.form');
Route::post('/subir-fotos', [FotoController::class, 'importarFotos'])->name('subir-fotos.importar');


Route::get('/admin/forgot-password', [PasswordResetController::class, 'showResetForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [PasswordResetController::class, 'reset'])->name('admin.password.reset');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Ruta protegida para el admin
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
