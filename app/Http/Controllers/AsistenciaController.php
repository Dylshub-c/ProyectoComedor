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
}