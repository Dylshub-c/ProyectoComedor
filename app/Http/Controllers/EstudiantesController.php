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
    public function informacion(Request $request)
    {
        $persona = null;
        $editar = $request->has('editar') && $request->editar == 1;

        if ($request->filled('cedula') || $request->filled('nombre')) {
            $query = Persona::with([
                'estudiante.seccione.propiedade',
                'estudiante.especialidade.propiedade',
                'estudiante.tipoBeca.propiedade'
            ]);

            if ($request->filled('cedula')) {
                $query->where('Cedula', $request->cedula);
            }

            if ($request->filled('nombre')) {
                $nombre = $request->nombre;

                // Unimos los campos y los comparamos con el nombre completo ingresado
                $query->whereRaw("CONCAT(Nombre, ' ', PrimerApellido, ' ', SegundoApellido) LIKE ?", ["%{$nombre}%"]);
            }

            $persona = $query->first();
        }

        $secciones = Seccione::all();
        $especialidades = Especialidade::all();
        $tiposBeca = TipoBeca::all();

        return view('estudiantes.informacion', compact('persona', 'editar', 'secciones', 'especialidades', 'tiposBeca'));
    }

    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $request->validate([
            'Nombre' => 'required|string|max:255',
            'PrimerApellido' => 'required|string|max:255',
            'SegundoApellido' => 'nullable|string|max:255',
            'Cedula' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'especialidade_id' => 'required|exists:especialidades,id',
            'seccione_id' => 'required|exists:secciones,id',
            'tipo_beca_id' => 'required|exists:tipo_becas,id',
        ]);

        // Actualizar datos personales
        $persona->update([
            'Nombre' => $request->Nombre,
            'PrimerApellido' => $request->PrimerApellido,
            'SegundoApellido' => $request->SegundoApellido,
            'Cedula' => $request->Cedula,
        ]);

        // Actualizar estudiante
        $estudiante = $persona->estudiante;
        $estudiante->especialidade_id = $request->especialidade_id;
        $estudiante->seccione_id = $request->seccione_id;
        $estudiante->tipo_beca_id = $request->tipo_beca_id;

        // Actualizar foto si se sube una nueva
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('fotos', 'public');
            $estudiante->foto = $foto;
        }

        $estudiante->save();

        return redirect()->back()->with('success', 'Estudiante actualizado correctamente');
    }


    public function destroy(Persona $persona)
    {
        // Eliminar persona y/o datos relacionados si es necesario
        $persona->delete();

        return redirect()->route('estudiantes.informacion')
                        ->with('success', 'Estudiante eliminado correctamente.');
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
