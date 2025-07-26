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
use App\Models\Propiedade;
use Illuminate\Support\Facades\Validator;

class EstudiantesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::with(['persona', 'especialidade.propiedade', 'seccione.propiedade', 'tipoBeca.propiedade'])->get();
        return view('estudiantes.informacion', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposBeca = TipoBeca::with('propiedade')->get();

        return view('estudiantes.create', compact('tiposBeca'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'cedula' => 'required|string|unique:personas,Cedula',
            'seccion' => 'required|string',
            'especialidad' => 'required|string',
            'tipo_beca_id' => 'required|exists:tipo_becas,id',
            'foto' => 'nullable|image|max:2048',
        ], [
        'cedula.unique' => 'El estudiante ya está registrado en el sistema.', ] );

        // Dividir nombre completo
        $partes = explode(' ', trim($request->input('nombre')));
        $nombre = '';
        $primerApellido = '';
        $segundoApellido = '';

        if (count($partes) >= 3) {
            $segundoApellido = array_pop($partes);
            $primerApellido = array_pop($partes);
            $nombre = implode(' ', $partes);
        } elseif (count($partes) == 2) {
            $primerApellido = array_pop($partes);
            $nombre = $partes[0];
            $segundoApellido = '';
        } elseif (count($partes) == 1) {
            $nombre = $partes[0];
            $primerApellido = '';
            $segundoApellido = '';
        }

        DB::beginTransaction();

        try {
            // Crear persona
            $persona = Persona::create([
                'Nombre' => $nombre,
                'PrimerApellido' => $primerApellido,
                'SegundoApellido' => $segundoApellido,
                'Cedula' => $request->cedula,
                'TipoUsuario' => 'Estudiante',
            ]);

            // Crear o encontrar propiedades (especialidad y seccion)
            $especialidadProp = Propiedade::firstOrCreate(['nombre' => $request->especialidad]);
            $seccionProp = Propiedade::firstOrCreate(['nombre' => $request->seccion]);

            // Buscar modelos relacionados
            $especialidad = Especialidade::firstOrCreate(['propiedade_id' => $especialidadProp->id]);
            $seccion = Seccione::firstOrCreate(['propiedade_id' => $seccionProp->id]);

            // Obtener tipoBeca directamente por id (no crearlo ni buscar propiedad)
            $tipoBeca = TipoBeca::findOrFail($request->tipo_beca_id);

            // Guardar foto si la hay
            $fotoRuta = null;
            if ($request->hasFile('foto')) {
                $fotoRuta = $request->file('foto')->store('fotos', 'public');
            }

            // Crear estudiante
            Estudiante::create([
                'persona_id' => $persona->id,
                'especialidade_id' => $especialidad->id,
                'seccione_id' => $seccion->id,
                'tipo_beca_id' => $tipoBeca->id,
                'foto' => $fotoRuta,
            ]);

            DB::commit();

            return redirect()->route('estudiantes.informacion')
                            ->with('success', 'Estudiante creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
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
    public function informacion(Request $request)
    {
        $editar = $request->input('editar') == 1;

        $persona = null;

        // Primero revisa si tienes persona guardada en sesión
        if (session()->has('persona_id')) {
            $persona = Persona::with([
                'estudiante.seccione.propiedade',
                'estudiante.especialidade.propiedade',
                'estudiante.tipoBeca.propiedade'
            ])->find(session('persona_id'));
        }

        // Si no hay persona en sesión, buscar por cédula o nombre (solo en POST)
        if ($request->isMethod('post') && ($request->filled('cedula') || $request->filled('nombre'))) {
            $query = Persona::with([
                'estudiante.seccione.propiedade',
                'estudiante.especialidade.propiedade',
                'estudiante.tipoBeca.propiedade'
            ]);

            $query->where('TipoUsuario', 'Estudiante');

            if ($request->filled('cedula')) {
                $query->where('Cedula', $request->cedula);
            }

            if ($request->filled('nombre')) {
                $nombre = $request->nombre;
                $query->whereRaw("CONCAT(Nombre, ' ', PrimerApellido, ' ', SegundoApellido) LIKE ?", ["%{$nombre}%"]);
            }

            $persona = $query->first();

            if ($persona) {
                // Guarda el id en sesión para futuras vistas
                session(['persona_id' => $persona->id]);
            }
        }

        // Si no hay persona, limpia la sesión para evitar mostrar info vieja
        if (!$persona) {
            session()->forget('persona_id');
        }

        $secciones = Seccione::all();
        $especialidades = Especialidade::all();
        $tiposBeca = TipoBeca::all();

        return view('estudiantes.informacion', compact('persona', 'editar', 'secciones', 'especialidades', 'tiposBeca'));
}



    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:255',
            'PrimerApellido' => 'required|string|max:255',
            'SegundoApellido' => 'nullable|string|max:255',
            'Cedula' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'especialidade_input' => 'required|string|max:255',
            'seccione_input' => 'required|string|max:255',
            'tipo_beca_id' => 'required|exists:tipo_becas,id',
        ]);
        if ($validator->fails()) {
        return redirect()->route('estudiantes.informacion', ['cedula' => $persona->Cedula, 'editar' => 1])
                         ->withErrors($validator)
                         ->withInput();
    }

        // Actualizar datos personales
        $persona->update([
            'Nombre' => $request->Nombre,
            'PrimerApellido' => $request->PrimerApellido,
            'SegundoApellido' => $request->SegundoApellido,
            'Cedula' => $request->Cedula,
        ]);

        // Buscar o crear propiedad para especialidad
        $propEspecialidad = Propiedade::firstOrCreate(['nombre' => $request->especialidade_input]);

        // Buscar o crear especialidad con ese propiedade_id
        $especialidad = Especialidade::firstOrCreate(['propiedade_id' => $propEspecialidad->id]);

        // Buscar o crear propiedad para sección
        $propSeccion = Propiedade::firstOrCreate(['nombre' => $request->seccione_input]);

        // Buscar o crear sección con ese propiedade_id
        $seccion = Seccione::firstOrCreate(['propiedade_id' => $propSeccion->id]);

        // Actualizar estudiante con los IDs correspondientes
        $estudiante = $persona->estudiante;
        $estudiante->especialidade_id = $especialidad->id;
        $estudiante->seccione_id = $seccion->id;
        $estudiante->tipo_beca_id = $request->tipo_beca_id;

        // Actualizar foto si se sube una nueva
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('fotos', 'public');
            $estudiante->foto = $foto;
        }

        $estudiante->save();

       return redirect()->route('estudiantes.informacion', ['editar' => 0])
                 ->with('guardado', true);
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

    public function eliminarLista()
    {
        // Aquí borras los estudiantes que se importaron
        // Si quieres borrar TODO de la tabla Estudiantes:
        Estudiante::truncate();

        // También puedes borrar personas relacionadas si quieres, o solo estudiantes.

        return redirect()->route('estudiantes.informacion')->with('success', 'Lista de estudiantes eliminada.');
    }

    public function recargarLista()
    {
        // Simplemente redirige o carga la vista sin estudiantes
        // Podrías pasar una colección vacía
        $estudiantes = collect();

        return view('estudiantes.informacion', compact('estudiantes'));
    }

}
