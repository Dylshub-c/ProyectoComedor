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
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

//----------------------------------------
// Mostrar formulario de login
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Procesar login
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Cerrar sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirigir "/" al login
Route::get('/', function() {
    return redirect()->route('login');
});


//----------------------------------------
// Admin
Route::prefix('admin')->middleware(['auth', 'permission:administrar usuarios'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('admin.home');

    Route::get('/forgot-password', [PasswordResetController::class, 'showResetForm'])->name('admin.password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'reset'])->name('admin.password.reset');
    Route::get('/cambio-contra', [PasswordResetController::class, 'confirmReset'])
        ->name('admin.password.confirm')
        ->middleware('signed');
});

Route::prefix('admin')->middleware(['auth', 'permission:administrar usuarios'])->group(function () { Route::get('/home', function () { return view('home'); })->name('admin.home'); });

// Área admin simple
Route::get('/admin', function() {
    return 'Área admin';
})->middleware('permission:ver admin');

//----------------------------------------
// Roles y Permisos
Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class)->middleware('permission:administrar roles');
    Route::resource('permissions', PermissionController::class)->middleware('permission:administrar permisos');
});

//----------------------------------------
// Asistencia / Comedor
Route::middleware(['auth'])->group(function () {
    Route::get('/ingreso-comedor', [AsistenciaController::class, 'index'])
        ->name('IngresoCom.IngresoComedor')
        ->middleware('permission:ver ingreso comedor');

    Route::get('/asistencia/buscar', [AsistenciaController::class, 'buscarEstudiante'])
        ->name('buscar.estudiante')
        ->middleware('permission:ver ingreso comedor');

    Route::get('/asistencia-rapida', [AsistenciaController::class, 'asistenciaRapidaIndex'])
        ->name('AsistenciaRapida.asistenciaRapida')
        ->middleware('permission:asistencia rápida');

    Route::post('/asistencia-rapida', [AsistenciaController::class, 'guardarAsistenciaRapida'])
        ->name('asistencia.rapida.guardar')
        ->middleware('permission:asistencia rápida');

    Route::post('/asistencia/guardar-estudiante', [AsistenciaController::class, 'guardarAsistenciaEstudiante'])
        ->name('asistencia.guardarEstudiante')
        ->middleware('permission:asistencia');

    Route::post('/asistencia/guardar', [AsistenciaController::class, 'guardarAsistenciaEstudiante'])
        ->name('asistencia.guardar')
        ->middleware('permission:asistencia');

    Route::get('/asistencia/revisar/{persona_id?}', [AsistenciaController::class, 'revisarAsistencia'])
        ->name('asistencia.revisar')
        ->middleware('permission:ver asistencia');

    Route::get('/comedor/buscar', [EstudiantesController::class, 'mostrarEnComedor'])
        ->name('comedor.buscar')
        ->middleware('permission:ver ingreso comedor');

    Route::post('/comedor/asistencia/confirmar', [AsistenciaController::class, 'confirmar'])
        ->name('asistencia.confirmar');




});

//----------------------------------------
// Estudiantes
Route::middleware(['auth'])->group(function () {
    // Vista principal
    Route::get('/estudiantes/informacion', [EstudiantesController::class, 'informacion'])
        ->name('estudiantes.informacion')
        ->middleware('permission:ver estudiantes');

    Route::post('/estudiantes/informacion', [EstudiantesController::class, 'informacion'])
        ->middleware('permission:ver estudiantes');

    // Importación
    Route::get('/estudiantes/importar', [EstudiantesController::class, 'formImportar'])
        ->name('estudiantes.importar.form')
        ->middleware('permission:importar estudiantes');

    Route::post('/estudiantes/importar', [EstudiantesController::class, 'importar'])
        ->name('estudiantes.importar')
        ->middleware('permission:importar estudiantes');

    // Crear estudiante individual
    Route::get('/estudiantes/create', [EstudiantesController::class, 'create'])
        ->name('estudiantes.create')
        ->middleware('permission:crear estudiantes');

    Route::post('/estudiantes', [EstudiantesController::class, 'store'])
        ->name('estudiantes.store')
        ->middleware('permission:crear estudiantes');

    // Actualizar estudiante
    Route::put('/estudiantes/{persona}', [EstudiantesController::class, 'update'])
        ->name('estudiantes.update')
        ->middleware('permission:editar estudiantes');

    // Eliminar estudiante individual
    Route::delete('/estudiantes/{persona}', [EstudiantesController::class, 'destroy'])
        ->name('estudiantes.destroy')
        ->middleware('permission:eliminar estudiantes');

    // Eliminar toda la lista
    Route::delete('/estudiantes/eliminar-ultima-importacion', [EstudiantesController::class, 'eliminarUltimaImportacion'])
    ->name('estudiantes.eliminar');


    // Recargar lista vacía
    Route::get('/estudiantes/recargar', [EstudiantesController::class, 'recargarLista'])
        ->name('estudiantes.recargar')
        ->middleware('permission:ver estudiantes');

    // Index general
    Route::get('/estudiantes', [EstudiantesController::class, 'index'])
        ->name('estudiantes.index')
        ->middleware('permission:ver estudiantes');

Route::get('/estudiantes/{estudiante}/asistencias', [EstudiantesController::class, 'getAsistencias'])->name('estudiantes.getAsistencias');

});

//----------------------------------------
// Tipo Beca
Route::middleware(['auth'])->group(function () {
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
});

//----------------------------------------
// Reportes
Route::middleware(['auth'])->group(function () {
    Route::get('/reporte/descargar', [ReporteController::class, 'descargar'])
        ->name('Reportes.DescargarReporte')
        ->middleware('permission:descargar reportes');

    Route::get('/reporte/asistencia/pdf', [ReporteController::class, 'mensualPdf'])
        ->name('reporte.asistencia.pdf')
        ->middleware('permission:descargar reportes');
});

//----------------------------------------
// Fotos
Route::middleware(['auth'])->group(function () {
    Route::get('/subir-fotos', [FotoController::class, 'showForm'])
        ->name('subir-fotos.form')
        ->middleware('permission:subir fotos');

    Route::post('/subir-fotos', [FotoController::class, 'importarFotos'])
        ->name('subir-fotos.importar')
        ->middleware('permission:subir fotos');
});
