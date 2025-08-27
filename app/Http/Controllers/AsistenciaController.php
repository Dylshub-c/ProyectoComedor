<?php
namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\ListadoAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Persona;
use App\Models\Estudiante;

class AsistenciaController extends Controller
{
    public function index()
{
    $fecha = Carbon::today(); // Fecha actual sin hora

    $tipos = ['desayuno', 'almuerzo'];
    $estados = ['presente', 'ausente'];

    // 1️⃣ Crear asistencias del día si no existen
    foreach ($tipos as $tipo) {
        foreach ($estados as $estado) {
            $yaExiste = Asistencia::whereDate('fecha_hora', $fecha)
                ->where('tipo_asistencia', $tipo)
                ->where('estado', $estado)
                ->exists();

            if (!$yaExiste) {
                Asistencia::create([
                    'fecha_hora' => Carbon::now(),
                    'tipo_asistencia' => $tipo,
                    'estado' => $estado,
                ]);
            }
        }
    }

    // 2️⃣ Asignar ausencias a los estudiantes según su tipo de beca
    $estudiantes = Estudiante::with('tipoBeca.propiedade')->get();

    foreach ($estudiantes as $estudiante) {

        // Normalizar nombre de la beca
        $tipoBecaRaw = $estudiante->tipoBeca->propiedade->nombre ?? '';
        $tipoBeca = strtolower(str_replace([' ', '-'], ['','_'], $tipoBecaRaw));

        $tiposAsignar = [];
        switch ($tipoBeca) {
            case 'desayuno':
                $tiposAsignar = ['desayuno'];
                break;
            case 'almuerzo':
                $tiposAsignar = ['almuerzo'];
                break;
            case 'desayuno_almuerzo':
                $tiposAsignar = ['desayuno', 'almuerzo'];
                break;
        }

        foreach ($tiposAsignar as $tipo) {
            // Obtener asistencia del día correspondiente al tipo y estado 'ausente'
            $asistencia = Asistencia::whereDate('fecha_hora', $fecha)
                ->where('tipo_asistencia', $tipo)
                ->where('estado', 'ausente')
                ->first();

            if ($asistencia) {
                // Crear ListadoAsistencia solo si no existe
                $yaRegistrado = ListadoAsistencia::where('estudiante_id', $estudiante->id)
                    ->where('asistencia_id', $asistencia->id)
                    ->exists();

                if (!$yaRegistrado) {
                    ListadoAsistencia::create([
                        'estudiante_id' => $estudiante->id,
                        'asistencia_id' => $asistencia->id,
                        'observaciones' => null
                    ]);
                }
            }
        }
    }

    return view('IngresoCom.IngresoComedor');
}


    public function buscarEstudiante(Request $request)
    {
        $cedula = $request->input('cedula');

        $estudiante = Estudiante::whereHas('persona', function ($query) use ($cedula) {
            $query->where('cedula', $cedula);
        })->with(['persona', 'especialidade', 'tipoBeca'])->first();

        return view('IngresoCom.IngresoComedor', compact('estudiante'));
    }
public function guardarAsistenciaRapida(Request $request)
{
    $validated = $request->validate([
        'fecha_hora' => 'required|date',
        'tipo_asistencia' => 'required|string|in:Desayuno,Almuerzo,Desayuno - Almuerzo',
        'estado' => 'required|string|in:Presente,Ausente',
        'observaciones' => 'nullable|string',
    ]);

    try {
        $fecha = $request->input('fecha_hora');
        $tipoSeleccionado = $request->input('tipo_asistencia');
        $estado = $request->input('estado');
        $observaciones = $request->input('observaciones');

        // Traer todos los estudiantes con su tipo de beca y propiedad
        $estudiantes = Estudiante::with('tipoBeca.propiedade')->get();

        // Determinar tipos de asistencia a crear
        $tiposCrear = $tipoSeleccionado === 'Desayuno - Almuerzo' ? ['Desayuno', 'Almuerzo'] : [$tipoSeleccionado];

        foreach ($tiposCrear as $tipoProc) {

            // Eliminar cualquier asistencia existente para esa fecha y tipo
            Asistencia::whereDate('fecha_hora', $fecha)
                ->where('tipo_asistencia', $tipoProc)
                ->delete();

            // Crear nueva asistencia
            $asistencia = Asistencia::create([
                'fecha_hora' => $fecha,
                'tipo_asistencia' => $tipoProc,
                'estado' => $estado,
                'observaciones' => $observaciones
            ]);

            foreach ($estudiantes as $estudiante) {

                // Obtener el nombre de la beca desde la propiedad
                $beca = $estudiante->tipoBeca && $estudiante->tipoBeca->propiedade
                        ? $estudiante->tipoBeca->propiedade->nombre
                        : null;

                if (!$beca) continue; // saltar estudiantes sin beca o propiedad

                $asignar = false;

                if ($tipoProc === 'Desayuno') {
                    $asignar = in_array($beca, ['Desayuno', 'Desayuno - Almuerzo']);
                } elseif ($tipoProc === 'Almuerzo') {
                    $asignar = in_array($beca, ['Almuerzo', 'Desayuno - Almuerzo']);
                }

                if ($asignar) {
                    // Crear o actualizar ListadoAsistencia
                    ListadoAsistencia::updateOrCreate(
                        [
                            'estudiante_id' => $estudiante->id,
                            'asistencia_id' => $asistencia->id
                        ],
                        [
                            'observaciones' => $observaciones
                        ]
                    );
                }
            }
        }

        return response()->json(['message' => 'Asistencias creadas y asignadas correctamente'], 200);

    } catch (\Exception $e) {
        \Log::error('Error al guardar asistencia rápida: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}




    public function asistenciaRapidaIndex(Request $request)
    {
        return view('AsistenciaRapida.asistenciaRapida');
    }
public function revisarAsistencia(Request $request, $persona_id = null)
    {
        // Prioridad: parámetro de ruta > querystring > sesión
        $id = $persona_id ?? $request->query('persona_id') ?? session('persona_id');

        if (!$id) {
            return redirect()->route('estudiantes.informacion')
                ->with('warning', 'Primero selecciona/busca un estudiante.');
        }

        $persona = Persona::with([
            'estudiante.tipoBeca.propiedade',
            'estudiante.listadosAsistencia.asistencia'
        ])->find($id);

        if (!$persona) {
            return redirect()->route('estudiantes.informacion')
                ->with('warning', 'Estudiante no encontrado.');
        }

        // Si quieres listar asistencias en la vista:
        $listados = $persona->estudiante->listadosAsistencia
            ->sortByDesc(fn($l) => $l->asistencia->fecha_hora);

        return view('Asistencia.Asistenciass', compact('persona', 'listados'));
    }

public function guardarAsistenciaEstudiante(Request $request)
{
    $request->validate([
        'estudiante_id' => 'required|exists:estudiantes,id',
        'fecha_hora' => 'required|date',
        'estado' => 'required|in:presente,ausente',
        'tipo_asistencia' => 'required|array',
        'tipo_asistencia.*' => 'in:desayuno,almuerzo',
    ]);

    $estudiante = Estudiante::with('tipoBeca.propiedade')->findOrFail($request->estudiante_id);
    $beca = $estudiante->tipoBeca->propiedade->nombre ?? '';

    foreach ($request->tipo_asistencia as $tipo) {
        // Validar que la beca del estudiante incluya el tipo seleccionado
        if (!in_array($tipo, ['desayuno', 'almuerzo']) || 
            ($tipo === 'desayuno' && !str_contains(strtolower($beca), 'desayuno')) ||
            ($tipo === 'almuerzo' && !str_contains(strtolower($beca), 'almuerzo'))
        ) {
            return back()->with('error', "El estudiante no tiene la beca correspondiente para: $tipo");
        }

        // Borrar asistencia del día para ese estudiante y tipo
        $asistencia = Asistencia::whereDate('fecha_hora', $request->fecha_hora)
            ->where('tipo_asistencia', $tipo)
            ->first();

        if ($asistencia) {
            ListadoAsistencia::where('estudiante_id', $estudiante->id)
                ->where('asistencia_id', $asistencia->id)
                ->delete();
        } else {
            $asistencia = Asistencia::create([
                'fecha_hora' => $request->fecha_hora,
                'tipo_asistencia' => $tipo,
                'estado' => $request->estado
            ]);
        }

        // Crear nueva asistencia para el estudiante
        ListadoAsistencia::create([
            'estudiante_id' => $estudiante->id,
            'asistencia_id' => $asistencia->id
        ]);
    }

    return back()->with('success', 'Asistencia guardada correctamente.');
}




}
