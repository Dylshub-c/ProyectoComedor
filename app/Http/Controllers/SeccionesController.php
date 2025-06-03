<?php

namespace App\Http\Controllers;

use App\Models\Seccione;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSeccionesRequest;
use App\Http\Requests\UpdateSeccionesRequest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SeccionesImport;
use Exception;
use App\Models\Propiedade;

class SeccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $secciones = Seccione::with('propiedade')->get();
        return view('secciones.index', compact('secciones'));
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
        return view('secciones.importar'); // Vista con el formulario para subir archivo Excel
    }



    public function importar(Request $request)
    {
        $request->validate([
        'archivo' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new SeccionesImport, $request->file('archivo'));

        return redirect()->back()->with('success', 'Secciones importadas correctamente.');
    }
}
