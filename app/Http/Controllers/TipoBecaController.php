<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoBeca;
use App\Models\Propiedade;
use App\Models\Especialidade;
use App\Models\Seccione;

class TipoBecaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cargar tipos de beca junto con su propiedade
        $tiposBeca = TipoBeca::with('propiedade')->get();

        return view('beca.Index', compact('tiposBeca'));
    }

    // Mostrar formulario para crear nuevo tipo de beca
    public function create()
    {
        return view('beca.Index');
    }

    // Guardar nuevo tipo de beca
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:propiedades,nombre',
        ]);

        // Crear o buscar la propiedad con ese nombre
        $propiedad = Propiedade::firstOrCreate([
            'nombre' => trim(ucwords(strtolower($request->nombre))),
        ]);

        // Crear tipo de beca con esa propiedad
        TipoBeca::create([
            'propiedade_id' => $propiedad->id,
        ]);

        return redirect()->back()->with('success', 'Tipo de beca creado');
    }

    // Mostrar formulario para editar tipo de beca
    public function edit($id)
    {
        $tipoBeca = TipoBeca::with('propiedade')->findOrFail($id);
        return view('beca.Index', compact('tipoBeca'));
    }

    // Actualizar tipo de beca
    public function update(Request $request, $id)
    {
        $tipoBeca = TipoBeca::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255|unique:propiedades,nombre,' . $tipoBeca->propiedade_id,
        ]);

        $nombreFormateado = trim(ucwords(strtolower($request->nombre)));

        // Actualizar nombre en la tabla propiedades
        $propiedad = $tipoBeca->propiedade;
        $propiedad->nombre = $nombreFormateado;
        $propiedad->save();

        return redirect()->back()->with('success', 'Tipo de beca actualizado');
    }

    // Eliminar tipo de beca
    public function destroy($id)
    {
        $tipoBeca = TipoBeca::findOrFail($id);

        // Primero eliminar la propiedad asociada
        $propiedad = $tipoBeca->propiedade;

        // Eliminar tipo de beca
        $tipoBeca->delete();

        // Eliminar propiedad solo si no está usada por otras entidades
        $usadaEnOtros = TipoBeca::where('propiedade_id', $propiedad->id)->exists()
            || Especialidade::where('propiedade_id', $propiedad->id)->exists()
            || Seccione::where('propiedade_id', $propiedad->id)->exists();

        if (!$usadaEnOtros) {
            $propiedad->delete();
        }

         return redirect()->back()->with('success', 'Tipo de beca eliminado');
    }
}
