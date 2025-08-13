<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\EncargadoController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TipoBecaController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Kernel;
use Spatie\Permission\Middleware\PermissionMiddleware;


Route::get('/admin', function() {
    return 'Área admin';
})->middleware('permission:ver admin');


Route::get('/', function () {
    return view('Auth/login'); // Make sure you have a 'login.blade.php' view

});


Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

//------------------------------------------

// Rutas para ingreso al comedor
Route::get('/ingreso-comedor', [AsistenciaController::class, 'index'])
    ->name('IngresoCom.IngresoComedor')
    ->middleware('permission:ver ingreso comedor');

Route::get('/asistencia/buscar', [AsistenciaController::class, 'buscarEstudiante'])
    ->name('buscar.estudiante')
    ->middleware('permission:ver ingreso comedor');

Route::get('/comedor/buscar', [EstudiantesController::class, 'mostrarEnComedor'])
    ->name('comedor.buscar')
    ->middleware('permission:ver ingreso comedor');


// Estudiantes
Route::get('/estudiantes', [EstudiantesController::class, 'index'])
    ->name('estudiantes.index')
    ->middleware('permission:ver estudiantes');

// Mostrar el formulario importar estudiantes
Route::get('estudiantes/importar', [EstudiantesController::class, 'formImportar'])
    ->name('estudiantes.importar.form')
    ->middleware('permission:importar estudiantes');

// Procesar la importación
Route::post('estudiantes/importar', [EstudiantesController::class, 'importar'])
    ->name('estudiantes.importar')
    ->middleware('permission:importar estudiantes');

// Eliminar todos los estudiantes importados
Route::delete('/estudiantes/eliminar-lista', [EstudiantesController::class, 'eliminarLista'])
    ->name('estudiantes.eliminarLista')
    ->middleware('permission:eliminar estudiantes');

// Recargar lista (vista sin estudiantes)
Route::get('/estudiantes/recargar-lista', [EstudiantesController::class, 'recargarLista'])
    ->name('estudiantes.recargarLista')
    ->middleware('permission:ver estudiantes');

// Información estudiantes
Route::get('/estudiantes/informacion', [EstudiantesController::class, 'informacion'])
    ->middleware('permission:ver estudiantes');

Route::post('/estudiantes/informacion', [EstudiantesController::class, 'informacion'])
    ->name('estudiantes.informacion')
    ->middleware('permission:ver estudiantes');

// Actualizar estudiante
Route::put('/estudiantes/{persona}', [EstudiantesController::class, 'update'])
    ->name('estudiantes.update')
    ->middleware('permission:editar estudiantes');

// Crear estudiante (formulario y guardar)
Route::get('/estudiantes/create', [EstudiantesController::class, 'create'])
    ->name('estudiantes.create')
    ->middleware('permission:crear estudiantes');

Route::post('/estudiantes', [EstudiantesController::class, 'store'])
    ->name('estudiantes.store')
    ->middleware('permission:crear estudiantes');

// Eliminar estudiante
Route::delete('/estudiantes/{persona}', [EstudiantesController::class, 'destroy'])
    ->name('estudiantes.destroy')
    ->middleware('permission:eliminar estudiantes');


// Tipo Beca
Route::get('/tipobeca', [TipoBecaController::class, 'index'])
    ->name('tipobeca.index')
    ->middleware('permission:ver tipo beca');

Route::get('/tipobeca/create', [TipoBecaController::class, 'create'])
    ->name('tipobeca.create')
    ->middleware('permission:crear tipo beca');

Route::post('/tipobeca', [TipoBecaController::class, 'store'])
    ->name('tipobeca.store')
    ->middleware('permission:crear tipo beca');

Route::get('/tipobeca/{id}/edit', [TipoBecaController::class, 'edit'])
    ->name('tipobeca.edit')
    ->middleware('permission:editar tipo beca');

Route::put('/tipobeca/{id}', [TipoBecaController::class, 'update'])
    ->name('tipobeca.update')
    ->middleware('permission:editar tipo beca');

Route::delete('/tipobeca/{id}', [TipoBecaController::class, 'destroy'])
    ->name('tipobeca.destroy')
    ->middleware('permission:eliminar tipo beca');


// Fotos
Route::get('/subir-fotos', [FotoController::class, 'showForm'])
    ->name('subir-fotos.form')
    ->middleware('permission:subir fotos');

Route::post('/subir-fotos', [FotoController::class, 'importarFotos'])
    ->name('subir-fotos.importar')
    ->middleware('permission:subir fotos');



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



