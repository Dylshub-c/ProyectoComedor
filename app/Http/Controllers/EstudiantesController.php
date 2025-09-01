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
use Carbon\Carbon;
use App\Models\Asistencia;
use App\Models\ListadoAsistencia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\AdminRegisteredMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


class EstudiantesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::with([
            'persona',
            'especialidade.propiedade',
            'seccione.propiedade',
            'tipoBecas.propiedade' // <- ahora plural
        ])->get();

        return view('estudiantes.informacion', compact('estudiantes'));
    }

    public function create()
    {
        $tiposBeca = TipoBeca::with('propiedade')->get();
        $roles = Role::all();

        return view('estudiantes.create', compact('tiposBeca', 'roles'));
    }

    public function store(Request $request)
{
    // Validación general
    $request->validate([
        'nombre' => 'required|string',
        'cedula' => 'required|string|unique:personas,Cedula',
        'rol' => 'required|exists:roles,name',
        'seccion' => 'required_if:rol,Estudiante|string|nullable',
        'especialidad' => 'required_if:rol,Estudiante|string|nullable',
        'tipo_beca_id' => 'required_if:rol,Estudiante|array|nullable',
        'tipo_beca_id.*' => 'exists:tipo_becas,id',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Validar correo solo si no es estudiante
    if (strtolower($request->rol) !== 'estudiante') {
        $request->validate([
            'correo' => 'required|email'
        ]);
    }

    // Dividir nombre completo
    $partes = explode(' ', trim($request->nombre));
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
    } elseif (count($partes) == 1) {
        $nombre = $partes[0];
    }

    DB::beginTransaction();
    try {
        // Crear persona
        $persona = Persona::create([
            'Nombre' => $nombre,
            'PrimerApellido' => $primerApellido,
            'SegundoApellido' => $segundoApellido,
            'Cedula' => $request->cedula,
            'TipoUsuario' => $request->rol,
        ]);

        // Si no es estudiante, crear usuario
        if (strtolower($request->rol) !== 'estudiante') {
            $password = Str::random(10);
            $user = User::firstOrCreate(
                ['email' => $request->correo],
                [
                    'persona_id' => $persona->id,
                    'password' => bcrypt($password),
                ]
            );

            $rol = Role::firstOrCreate(['name' => $request->rol]);
            $user->syncRoles([$rol->id]);
            $user->syncPermissions($rol->permissions);

            if ($user->wasRecentlyCreated) {
                Mail::to($user->email)->send(new \App\Mail\AdminRegisteredMail(
                    $request->correo,
                    $password,
                    $persona->Nombre
                ));
            }
        }

        // Si es estudiante, crear registro en estudiantes
        if (strtolower($request->rol) === 'estudiante') {
            $fotoRuta = null;

            if ($request->hasFile('foto')) {
                // Guarda igual que en update (en disk "public")
                $fotoRuta = $request->file('foto')->store('fotos', 'public');
            }

            $especialidadProp = Propiedade::firstOrCreate(['nombre' => $request->especialidad]);
            $seccionProp = Propiedade::firstOrCreate(['nombre' => $request->seccion]);

            $especialidad = Especialidade::firstOrCreate(['propiedade_id' => $especialidadProp->id]);
            $seccion = Seccione::firstOrCreate(['propiedade_id' => $seccionProp->id]);

            $estudiante = Estudiante::create([
                'persona_id' => $persona->id,
                'especialidade_id' => $especialidad->id,
                'seccione_id' => $seccion->id,
                'foto' => $fotoRuta,
            ]);

            if ($request->has('tipo_beca_id')) {
                $estudiante->tipoBecas()->attach($request->tipo_beca_id);
            }
        }

        DB::commit();
        return redirect()->route('estudiantes.informacion')
            ->with('success', 'Registro completado correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])
            ->withInput();
    }
}


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function informacion(Request $request)
    {
        $editar = $request->input('editar') == 1;
        $persona = null;

        $roles = Role::all();

        // Si hay persona en sesión, la cargamos (para mostrar la última buscada)
        if (session()->has('persona_id')) {
            $persona = Persona::with([
                'estudiante.seccione.propiedade',
                'estudiante.especialidade.propiedade',
                'estudiante.tipoBecas.propiedade',
                'usuario.roles'
            ])->find(session('persona_id'));
        }

        // Usamos GET para búsquedas: /ruta?cedula=...&nombre=...
        if ($request->isMethod('get') && ($request->filled('cedula') || $request->filled('nombre') || $request->filled('rol'))) {
            $query = Persona::with([
                'estudiante.seccione.propiedade',
                'estudiante.especialidade.propiedade',
                'estudiante.tipoBecas.propiedade',
                'usuario.roles'
            ])->where('TipoUsuario', 'Estudiante');

            // Búsqueda parcial por cédula (LIKE)
            if ($request->filled('cedula')) {
                $cedula = trim($request->cedula);
                $query->where('Cedula', 'LIKE', "%{$cedula}%");
            }

            // Búsqueda por nombre (concatenado), insensible a mayúsculas
            if ($request->filled('nombre')) {
                $nombre = trim($request->nombre);
                // Convertimos el parámetro a minúsculas en PHP y comparamos contra LOWER(...) en SQL
                $like = '%' . mb_strtolower($nombre) . '%';
                $query->whereRaw("LOWER(CONCAT(Nombre, ' ', PrimerApellido, ' ', SegundoApellido)) LIKE ?", [$like]);
            }

            // Filtro por rol (si se envía)
            if ($request->filled('rol')) {
                $rol = $request->rol;
                $query->whereHas('usuario.roles', function ($q) use ($rol) {
                    $q->where('name', $rol);
                });
            }

            $persona = $query->first();

            if ($persona) {
                session(['persona_id' => $persona->id]);
            }
        }

        if (!$persona) {
            session()->forget('persona_id');
        }

        $secciones = Seccione::all();
        $especialidades = Especialidade::all();
        $tiposBeca = TipoBeca::all();

        return view('estudiantes.informacion', compact('persona', 'editar', 'secciones', 'especialidades', 'tiposBeca', 'roles'));
    }

    public function update(Request $request, $id)
{
    $persona = Persona::findOrFail($id);

    // Validación
    $validator = Validator::make($request->all(), [
        'Nombre' => 'required|string|max:255',
        'PrimerApellido' => 'required|string|max:255',
        'SegundoApellido' => 'nullable|string|max:255',
        'Cedula' => 'required|string|max:20',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'especialidade_input' => 'required|string|max:255',
        'seccione_input' => 'required|string|max:255',
        'tipo_beca_id' => 'required|array',
        'tipo_beca_id.*' => 'exists:tipo_becas,id',
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

    // Buscar o crear propiedades
    $propEspecialidad = Propiedade::firstOrCreate(['nombre' => $request->especialidade_input]);
    $especialidad = Especialidade::firstOrCreate(['propiedade_id' => $propEspecialidad->id]);

    $propSeccion = Propiedade::firstOrCreate(['nombre' => $request->seccione_input]);
    $seccion = Seccione::firstOrCreate(['propiedade_id' => $propSeccion->id]);

    $estudiante = $persona->estudiante;
    $estudiante->especialidade_id = $especialidad->id;
    $estudiante->seccione_id = $seccion->id;

    // Manejo de foto
    if ($request->hasFile('foto')) {
        // Eliminar foto anterior si existe
        if ($estudiante->foto && Storage::disk('public')->exists($estudiante->foto)) {
            Storage::disk('public')->delete($estudiante->foto);
        }
        // Guardar nueva foto
        $estudiante->foto = $request->file('foto')->store('fotos', 'public');
    }

    $estudiante->save();

    // Sincronizar becas (tabla pivot)
    $estudiante->tipoBecas()->sync($request->tipo_beca_id);

    return redirect()->route('estudiantes.informacion', ['editar' => 0])
        ->with('guardado', true);
}




    public function destroy($id)
    {
        try {
            $estudiante = Estudiante::findOrFail($id);

            // Eliminar relaciones tipoBecas
            $estudiante->tipoBecas()->detach();

            // Eliminar estudiante
            $estudiante->delete();

            // Eliminar persona asociada
            $persona = $estudiante->persona;
            if ($persona) {
                $persona->delete();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
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
        $estudiantes = Estudiante::with(['persona', 'especialidade.propiedade', 'tipoBecas.propiedade'])
                        ->latest()
                        ->get();

        // Pasarlos a la vista solo esta vez
        return view('estudiantes.importar', compact('estudiantes'));
    }

    public function eliminarUltimaImportacion()
{
    Estudiante::where('importado', true)->delete();

    return redirect()->back()->with('success', 'Se ha eliminado la lista de estudiantes importados desde Excel.');
}


    public function recargarLista()
{
    // Elimina la última importación de la tabla temporal
    Estudiante::where('import_batch_id', session('last_import_batch'))->delete();
    session()->forget('last_import_batch');

    $estudiantes = collect(); // lista vacía
    return view('estudiantes.informacion', compact('estudiantes'));
}
public function mostrarEnComedor(Request $request)
{
    $persona = null;
    $asistencias = [];
    $todasLasBecas = TipoBeca::with('propiedade')->get(); // 👈 aquí obtienes todas

    if ($request->filled('cedula')) {
        $persona = Persona::with([
            'estudiante.tipoBecas.propiedade'
        ])->where('cedula', $request->cedula)->first();
    }

    return view('IngresoCom.IngresoComedor', compact('persona', 'asistencias', 'todasLasBecas'));
}
public function getAsistencias(Request $request, $id)
{
    $mes = $request->query('mes'); // YYYY-MM
    $tipoBecaNombre = $request->query('tipo_beca'); // ejemplo: "Hola"

    $estudiante = \App\Models\Estudiante::findOrFail($id);

    $listado = $estudiante->listadosAsistencia()
        ->with('asistencia')
        ->when($mes, function($query) use ($mes) {
            [$anio, $mesNum] = explode('-', $mes);
            $query->whereHas('asistencia', function($q) use ($anio, $mesNum) {
                $q->whereYear('fecha_hora', $anio)
                  ->whereMonth('fecha_hora', $mesNum);
            });
        })
        ->when($tipoBecaNombre, function($query) use ($tipoBecaNombre) {
            $query->whereHas('asistencia', function($q) use ($tipoBecaNombre) {
                $q->where('tipo_asistencia', $tipoBecaNombre);
            });
        })
        ->get();

    $result = $listado->map(function($item) {
        return [
            'fecha_hora' => $item->asistencia->fecha_hora->format('d/m/Y'),
            'tipo_asistencia' => $item->asistencia->tipo_asistencia,
            'estado' => $item->asistencia->estado,
            'observaciones' => $item->observaciones ?? '',
        ];
    });

    return response()->json($result);
}

// EstudianteController.php
public function mostrarFoto($nombre)
{
    $ruta = storage_path('app/fotos/' . $nombre);

    if (!file_exists($ruta)) {
        abort(404);
    }

    return response()->file($ruta); // sirve el archivo directamente
}








}
