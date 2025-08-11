<?php
namespace App\Http\Controllers;

use App\Models\Asistencia;
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
        $asistencia = Asistencia::create([
            'fecha_hora' => $validated['fecha_hora'],
            'tipo_asistencia' => $validated['tipo_asistencia'],
            'estado' => $validated['estado'],
        ]);

        $estudiantes = Estudiante::all();

        foreach ($estudiantes as $estudiante) {
            $asistencia->listadosAsistencia()->create([
                'estudiante_id' => $estudiante->id,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Asistencia guardada con éxito'], 200);

    } catch (\Exception $e) {
        \Log::error('Error al guardar asistencia rápida: '.$e->getMessage());
        return response()->json(['error' => 'Error interno del servidor'], 500);
    }



public function asistenciaRapidaIndex(Request $request) {
    return view('AsistenciaRapida.asistenciaRapida');
    
}
}