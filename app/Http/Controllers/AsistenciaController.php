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
}
