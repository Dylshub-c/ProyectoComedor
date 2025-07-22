<?php

namespace App\Http\Controllers;

use App\Imports\EstudiantesImport;
use Illuminate\Http\Request;
use App\Imports\ImportEstudiantes;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Estudiante;
use App\Models\Especialidade;
use App\Models\Seccione;
use App\Models\TipoBeca;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class EstudiantesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::with(['persona', 'especialidade.propiedade', 'seccione.propiedade', 'tipoBeca.propiedade'])->get();
        return view('estudiantes.index', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function formImportar()
    {
        return view('estudiantes.importar');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        // Conteo antes
        $countAntes = Estudiante::count();

        // Importar
        Excel::import(new EstudiantesImport, $request->file('archivo'));

        // Conteo después
        $countDespues = Estudiante::count();

        // Calcular los nuevos importados
        $cantidadImportados = $countDespues - $countAntes;

        // Obtener los nuevos estudiantes
        $estudiantes = Estudiante::with(['persona', 'especialidade.propiedade', 'tipoBeca.propiedade'])
                        ->latest()
                        ->take($cantidadImportados)
                        ->get();

        // Pasarlos a la vista solo esta vez
        return view('estudiantes.importar', compact('estudiantes'));
    }

    
    
}
