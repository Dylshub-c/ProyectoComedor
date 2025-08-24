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
        $fechaHoy = Carbon::today();
        $tipos = ['desayuno', 'almuerzo'];
        $estados = ['presente', 'ausente'];

        // Crear todas las combinaciones tipo + estado para el día si no existen
        foreach ($tipos as $tipo) {
            foreach ($estados as $estado) {
                Asistencia::firstOrCreate(
                    [
                        'fecha_hora' => $fechaHoy,
                        'tipo_asistencia' => $tipo,
                        'estado' => $estado
                    ]
                );
            }
        }

        // Obtener todos los estudiantes con su beca
        $estudiantes = Estudiante::with('tipoBeca.propiedade')->get();

        foreach ($estudiantes as $estudiante) {
            // Normalizar el nombre de la beca
            $becaRaw = strtolower($estudiante->tipoBeca->propiedade->nombre ?? '');
            $becaRaw = preg_replace('/\s*[-–]\s*/u', '-', $becaRaw); // unifica guiones
            $becaRaw = preg_replace('/\s+/', '', $becaRaw); // elimina espacios

            // Determinar tipos permitidos
            $tiposPermitidos = [];
            if ($becaRaw === 'desayuno') $tiposPermitidos = ['desayuno'];
            elseif ($becaRaw === 'almuerzo') $tiposPermitidos = ['almuerzo'];
            elseif ($becaRaw === 'desayuno-almuerzo') $tiposPermitidos = ['desayuno', 'almuerzo'];

            foreach ($tipos as $tipo) {
                // Saltar tipos que el estudiante no tiene permitido
                if (!in_array($tipo, $tiposPermitidos)) continue;

                // Buscar asistencia "ausente" para este tipo y fecha
                $asistenciaAusente = Asistencia::whereDate('fecha_hora', $fechaHoy)
                    ->where('tipo_asistencia', $tipo)
                    ->where('estado', 'ausente')
                    ->first();

                // Crear listado si no existe
                if ($asistenciaAusente) {
                    ListadoAsistencia::firstOrCreate(
                        [
                            'estudiante_id' => $estudiante->id,
                            'asistencia_id' => $asistenciaAusente->id
                        ],
                        ['observaciones' => null]
                    );
                }
            }
        }

        return view('IngresoCom.IngresoComedor');
    }

    // BUSCAR ESTUDIANTE: Marca presente según tipo de comida y beca
  public function buscarEstudiante(Request $request)
{
$cedula = $request->input('cedula');
    $fechaHoy = Carbon::today();
    $horaActual = Carbon::now()->format('H:i');

    // Definir rangos de hora para cada turno
    $turnos = [
        'desayuno' => ['inicio' => '06:00', 'fin' => '10:00'],
        'almuerzo' => ['inicio' => '11:00', 'fin' => '15:00']
    ];

    // Detectar el turno actual
    $turnoActual = null;
    foreach ($turnos as $tipo => $rango) {
        if ($horaActual >= $rango['inicio'] && $horaActual <= $rango['fin']) {
            $turnoActual = $tipo;
            break;
        }
    }

    if (!$turnoActual) {
        return response()->json(['error' => 'No es hora de ningún turno de comida'], 400);
    }

    // Buscar estudiante por cédula
    $estudiante = Estudiante::whereHas('persona', function($q) use ($cedula) {
        $q->where('cedula', $cedula);
    })->with('tipoBeca.propiedade')->first();

    if (!$estudiante) {
        return response()->json(['error' => 'Estudiante no encontrado'], 404);
    }

    // Obtener nombre de la beca de manera exacta
    $nombreBeca = $estudiante->tipoBeca->propiedade->nombre ?? '';

    // Mapear nombres exactos de la base de datos a tipos permitidos
    $mapBecaATipo = [
        'Desayuno' => ['desayuno'],
        'Almuerzo' => ['almuerzo'],
        'Desayuno - Almuerzo' => ['desayuno', 'almuerzo']
    ];

    $tiposPermitidos = $mapBecaATipo[$nombreBeca] ?? [];

    // Verificar que el turno actual esté permitido
    if (!in_array($turnoActual, $tiposPermitidos)) {
        return response()->json(['error' => "El estudiante no tiene beca para el turno de $turnoActual"], 403);
    }

    // Buscar asistencia existente para este turno y fecha (sin importar estado)
    $asistencia = Asistencia::whereDate('fecha_hora', $fechaHoy)
        ->where('tipo_asistencia', $turnoActual)
        ->first();

    // Crear asistencia si no existe
    if (!$asistencia) {
        $asistencia = Asistencia::create([
            'fecha_hora' => $fechaHoy,
            'tipo_asistencia' => $turnoActual,
            'estado' => 'ausente'
        ]);
    }

    // Crear listado de asistencia si no existe
    ListadoAsistencia::firstOrCreate(
        [
            'estudiante_id' => $estudiante->id,
            'asistencia_id' => $asistencia->id
        ],
        ['observaciones' => null]
    );

    return response()->json(['success' => "Asistencia de $turnoActual agregada correctamente"]);
}

public function guardarAsistenciaRapida(Request $request)
{
    $validated = $request->validate([
        'fecha_hora' => 'required|date',
        'tipo_asistencia' => 'required|string',
        'estado' => 'required|string',
        'observaciones' => 'nullable|string',
    ]);

    try {
        $fecha = $request->input('fecha_hora');
        $tipo = $request->input('tipo_asistencia');
        $estado = strtolower($request->input('estado'));
        $observaciones = $request->input('observaciones');

        // Traer todos los estudiantes
        $estudiantes = Estudiante::all();

        foreach ($estudiantes as $estudiante) {
            // Tipos a procesar
            $tiposProcesar = $tipo === 'desayuno_almuerzo' ? ['desayuno', 'almuerzo'] : [$tipo];

            foreach ($tiposProcesar as $tipoProc) {
                // Buscar asistencia existente
                $asistencia = Asistencia::whereDate('fecha_hora', $fecha)
                    ->where('tipo_asistencia', $tipoProc)
                    ->first();

                if ($asistencia) {
                    // Actualizar datos existentes
                    $asistencia->update([
                        'estado' => $estado,
                        'observaciones' => $observaciones
                    ]);
                } else {
                    // Crear nueva asistencia
                    $asistencia = Asistencia::create([
                        'fecha_hora' => $fecha,
                        'tipo_asistencia' => $tipoProc,
                        'estado' => $estado,
                        'observaciones' => $observaciones
                    ]);
                }

                // Crear o actualizar listado para el estudiante
                $listado = ListadoAsistencia::where('estudiante_id', $estudiante->id)
                    ->where('asistencia_id', $asistencia->id)
                    ->first();

                if ($listado) {
                    $listado->update([
                        'observaciones' => $observaciones
                    ]);
                } else {
                    ListadoAsistencia::create([
                        'estudiante_id' => $estudiante->id,
                        'asistencia_id' => $asistencia->id,
                        'observaciones' => $observaciones
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Asistencias creadas/actualizadas correctamente'], 200);

    } catch (\Exception $e) {
        \Log::error('Error al guardar asistencia rápida: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function asistenciaRapidaIndex(Request $request)
    {
        return view('AsistenciaRapida.asistenciaRapida');
    }
}
