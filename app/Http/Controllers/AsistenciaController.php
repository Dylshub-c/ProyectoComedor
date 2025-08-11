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
            // Definir los tipos a crear según si es desayuno_almuerzo o uno solo
            $tiposCrear = $tipo === 'desayuno_almuerzo' ? ['desayuno', 'almuerzo'] : [$tipo];

            foreach ($tiposCrear as $tipoCrear) {
                // Validar si ya existe una asistencia para ese estudiante, fecha y tipo
                $existeAsistencia = ListadoAsistencia::where('estudiante_id', $estudiante->id)
                    ->whereHas('asistencia', function($query) use ($fecha, $tipoCrear) {
                        $query->whereDate('fecha_hora', $fecha)
                              ->where('tipo_asistencia', $tipoCrear);
                    })->exists();

                if (!$existeAsistencia) {
                    // Crear asistencia
                    $asistencia = Asistencia::create([
                        'fecha_hora' => $fecha,
                        'tipo_asistencia' => $tipoCrear,
                        'estado' => $estado,
                        'observaciones' => $observaciones,
                    ]);

                    // Asociar en listado_asistencias
                    ListadoAsistencia::create([
                        'estudiante_id' => $estudiante->id,
                        'asistencia_id' => $asistencia->id,
                        'observaciones' => $observaciones,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Asistencias guardadas y asignadas sin duplicados'], 200);

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
