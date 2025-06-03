<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidade;
use App\Models\Propiedade;
use App\Http\Requests\StoreEspecialidadesRequest;
use App\Http\Requests\UpdateEspecialidadesRequest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EspecialidadesImport;
use Exception;

class EspecialidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $especialidades = Especialidade::with('propiedade')->get();
        return view('especialidades.index', compact('especialidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('especialidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEspecialidadesRequest $request)
    {
       try {
        DB::beginTransaction();

        $validated = $request->validated();

        $propiedade = Propiedade::create([
            'nombre' => $validated['nombre'],
        ]);

        // Crear la especialidad relacionada automáticamente
        $propiedade->especialidade()->create();

        DB::commit();

        return redirect()->route('especialidades.index')->with('success', 'Especialidad creada correctamente.');
        } catch (Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Error al crear especialidad: ' . $e->getMessage()]);
        }
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

    /**
     * Importar especialidades desde un archivo Excel.
     */

    public function formImportar()
    {
        return view('especialidades.importar'); // Vista con el formulario para subir archivo Excel
    }



    public function importar(Request $request)
    {
        $request->validate([
        'archivo' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new EspecialidadesImport, $request->file('archivo'));

        return redirect()->back()->with('success', 'Especialidades importadas correctamente.');
    }
}
