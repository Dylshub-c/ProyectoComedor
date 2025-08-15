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
        $roles = Role::all();

        return view('estudiantes.create', compact('tiposBeca', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validación
    $request->validate([
        'nombre' => 'required|string',
        'cedula' => 'required|string|unique:personas,Cedula',
        'rol' => 'required|exists:roles,name',
        'correo' => 'required_if:rol,!Estudiante|email',
        'seccion' => 'required_if:rol,Estudiante|string|nullable',
        'especialidad' => 'required_if:rol,Estudiante|string|nullable',
        'tipo_beca_id' => 'required_if:rol,Estudiante|nullable|exists:tipo_becas,id',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Dividir nombre completo
    $partes = explode(' ', trim($request->nombre));
    $nombre = '';
    $primerApellido = '';
    $segundoApellido = '';

    if(count($partes) >= 3){
        $segundoApellido = array_pop($partes);
        $primerApellido = array_pop($partes);
        $nombre = implode(' ', $partes);
    } elseif(count($partes) == 2){
        $primerApellido = array_pop($partes);
        $nombre = $partes[0];
    } elseif(count($partes) == 1){
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
        if(strtolower($request->rol) !== 'estudiante'){
            $password = Str::random(10);
            $user = User::firstOrCreate(
                ['email' => $request->correo],
                [
                    'persona_id' => $persona->id,
                    'password' => bcrypt($password),
                ]
            );

            // Asignar rol
            $rol = Role::firstOrCreate(['name' => $request->rol]);
            $user->syncRoles([$rol->id]);

            // Sincronizar permisos del rol al usuario
            $user->syncPermissions($rol->permissions);

            // Enviar correo si se creó el usuario
            if($user->wasRecentlyCreated){
                Mail::to($user->email)->send(new \App\Mail\AdminRegisteredMail(
                    $request->correo,
                    $password,
                    $persona->Nombre
                ));
            }
        }

        // Si es estudiante, crear registro en estudiantes
        if(strtolower($request->rol) === 'estudiante'){
            $fotoRuta = null;
            if($request->hasFile('foto')){
                $file = $request->file('foto');
                $nombreArchivo = time() . '_' . $file->getClientOriginalName();
                $rutaDestino = public_path('fotos');
                if(!file_exists($rutaDestino)) mkdir($rutaDestino, 0755, true);
                $file->move($rutaDestino, $nombreArchivo);
                $fotoRuta = 'fotos/' . $nombreArchivo;
            }

            $especialidadProp = Propiedade::firstOrCreate(['nombre' => $request->especialidad]);
            $seccionProp = Propiedade::firstOrCreate(['nombre' => $request->seccion]);

            $especialidad = Especialidade::firstOrCreate(['propiedade_id' => $especialidadProp->id]);
            $seccion = Seccione::firstOrCreate(['propiedade_id' => $seccionProp->id]);
            $tipoBeca = TipoBeca::findOrFail($request->tipo_beca_id);

            Estudiante::create([
                'persona_id' => $persona->id,
                'especialidade_id' => $especialidad->id,
                'seccione_id' => $seccion->id,
                'tipo_beca_id' => $tipoBeca->id,
                'foto' => $fotoRuta,
            ]);
        }

        DB::commit();
        return redirect()->route('estudiantes.informacion')
                         ->with('success', 'Registro completado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Error al guardar: '.$e->getMessage()])
                     ->withInput();
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

    // Obtener todos los roles para el select
    $roles = Role::all();

    // Primero revisa si tienes persona guardada en sesión
    if (session()->has('persona_id')) {
        $persona = Persona::with([
            'estudiante.seccione.propiedade',
            'estudiante.especialidade.propiedade',
            'estudiante.tipoBeca.propiedade',
            'user.roles' // traer roles del usuario
        ])->find(session('persona_id'));
    }

    // Si no hay persona en sesión, buscar por cédula, nombre o rol (solo en POST)
    if ($request->isMethod('post') && ($request->filled('cedula') || $request->filled('nombre') || $request->filled('rol'))) {
        $query = Persona::with([
            'estudiante.seccione.propiedade',
            'estudiante.especialidade.propiedade',
            'estudiante.tipoBeca.propiedade',
            'user.roles'
        ])->where('TipoUsuario', 'Estudiante');

        if ($request->filled('cedula')) {
            $query->where('Cedula', $request->cedula);
        }

        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->whereRaw("CONCAT(Nombre, ' ', PrimerApellido, ' ', SegundoApellido) LIKE ?", ["%{$nombre}%"]);
        }

        if ($request->filled('rol')) {
            $rol = $request->rol;
            $query->whereHas('usuario.roles', function($q) use ($rol) {
                $q->where('name', $rol);
            });
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

    return view('estudiantes.informacion', compact('persona', 'editar', 'secciones', 'especialidades', 'tiposBeca', 'roles'));
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

   public function mostrarEnComedor(Request $request)
{
    $persona = null;
    $asistencias = [];

    if ($request->filled('cedula')) {
        $persona = Persona::with([
            'estudiante.seccione.propiedade',
            'estudiante.especialidade.propiedade',
            'estudiante.tipoBeca.propiedade'
        ])
        ->where('TipoUsuario', 'Estudiante')
        ->where('Cedula', $request->cedula)
        ->first();

        if ($persona && $persona->estudiante) {
            $hora = \Illuminate\Support\Carbon::now()->format('H');
            $tipoAsistencia = $hora < 12 ? 'desayuno' : 'almuerzo';

            $asistencia = Asistencia::whereDate('fecha_hora', \Illuminate\Support\Carbon::today())
                ->where('tipo_asistencia', $tipoAsistencia)
                ->where('estado', 'presente')
                ->first();

            if ($asistencia) {
                $yaRegistrado = ListadoAsistencia::where('estudiante_id', $persona->estudiante->id)
                    ->where('asistencia_id', $asistencia->id)
                    ->exists();

                if (!$yaRegistrado) {
                    ListadoAsistencia::create([
                        'estudiante_id' => $persona->estudiante->id,
                        'asistencia_id' => $asistencia->id,
                        'observaciones' => null
                    ]);
                }
            }

            // Obtener asistencias del estudiante para el mes actual
            $inicioMes = \Illuminate\Support\Carbon::now()->startOfMonth();
            $finMes = \Illuminate\Support\Carbon::now()->endOfMonth();

            $listado = ListadoAsistencia::with('asistencia')
                ->where('estudiante_id', $persona->estudiante->id)
                ->whereHas('asistencia', function ($query) use ($inicioMes, $finMes) {
                    $query->whereBetween('fecha_hora', [$inicioMes, $finMes]);
                })->get();

            // Preparar arreglo con fecha => estado
            foreach ($listado as $item) {
                $fecha = $item->asistencia->fecha_hora->format('Y-m-d');
                $asistencias[$fecha] = $item->asistencia->estado; // 'presente' o 'ausente'
            }
        }
    }

    return view('IngresoCom.IngresoComedor', compact('persona', 'asistencias'));
}




}
