<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\ListadoAsistencia;
use App\Models\Estudiante;
use App\Models\Persona;
use Carbon\Carbon;
use App\Models\TipoBeca;

class AsistenciaController extends Controller
{
public function index()
{
    $fecha = Carbon::today(); // Fecha actual sin hora
    $estados = ['presente', 'ausente'];

    // 1️⃣ Obtener dinámicamente los tipos de asistencia desde las becas
    $tipos = TipoBeca::with('propiedade')
        ->get()
        ->pluck('propiedade.nombre')
        ->map(fn($nombre) => strtolower(str_replace([' ', '-'], ['_', '_'], $nombre)))
        ->unique()
        ->toArray();

    // 2️⃣ Crear asistencias del día si no existen
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

    // 3️⃣ Asignar ausencias según las becas de cada estudiante
    $estudiantes = Estudiante::with('tipoBecas.propiedade')->get();

    foreach ($estudiantes as $estudiante) {
        // Sacar los tipos de asistencia de este estudiante
        $tiposAsignar = $estudiante->tipoBecas
            ->map(fn($beca) => strtolower(str_replace([' ', '-'], ['_', '_'], $beca->propiedade->nombre ?? '')))
            ->unique();

        foreach ($tiposAsignar as $tipo) {
            // 3.1️⃣ Verificar si el estudiante ya tiene presente
            $tienePresente = ListadoAsistencia::where('estudiante_id', $estudiante->id)
                ->whereHas('asistencia', function ($q) use ($tipo, $fecha) {
                    $q->whereDate('fecha_hora', $fecha)
                      ->where('tipo_asistencia', $tipo)
                      ->where('estado', 'presente');
                })->exists();

            // 3.2️⃣ Solo asignar ausente si no tiene presente
            if (!$tienePresente) {
                $asistenciaAusente = Asistencia::whereDate('fecha_hora', $fecha)
                    ->where('tipo_asistencia', $tipo)
                    ->where('estado', 'ausente')
                    ->first();

                if ($asistenciaAusente) {
                    $yaRegistrado = ListadoAsistencia::where('estudiante_id', $estudiante->id)
                        ->where('asistencia_id', $asistenciaAusente->id)
                        ->exists();

                    if (!$yaRegistrado) {
                        ListadoAsistencia::create([
                            'estudiante_id' => $estudiante->id,
                            'asistencia_id' => $asistenciaAusente->id,
                            'observaciones' => null
                        ]);
                    }
                }
            }
        }
    }

    $tiposBeca = TipoBeca::with('propiedade')->get();
    $persona = null; // Siempre definido, aunque sea null
    return view('IngresoCom.IngresoComedor', compact('persona', 'tiposBeca'));
}

    
    public function buscarEstudiante(Request $request)
    {
        $cedula = $request->input('cedula');

        $estudiante = Estudiante::whereHas('persona', function ($query) use ($cedula) {
            $query->where('cedula', $cedula);
        })->with(['persona', 'especialidade', 'tipoBecas.propiedade'])->first();

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

            $estudiantes = Estudiante::with('tipoBecas.propiedade')->get();

            $tiposCrear = $tipoSeleccionado === 'Desayuno - Almuerzo' ? ['Desayuno', 'Almuerzo'] : [$tipoSeleccionado];

            foreach ($tiposCrear as $tipoProc) {
                Asistencia::whereDate('fecha_hora', $fecha)
                    ->where('tipo_asistencia', $tipoProc)
                    ->delete();

                $asistencia = Asistencia::create([
                    'fecha_hora' => $fecha,
                    'tipo_asistencia' => $tipoProc,
                    'estado' => $estado,
                    'observaciones' => $observaciones
                ]);

                foreach ($estudiantes as $estudiante) {
                    $asignar = $estudiante->tipoBecas->contains(function ($b) use ($tipoProc) {
                        $nombre = strtolower($b->propiedade->nombre ?? '');
                        return ($tipoProc === 'Desayuno' && str_contains($nombre, 'desayuno'))
                            || ($tipoProc === 'Almuerzo' && str_contains($nombre, 'almuerzo'));
                    });

                    if ($asignar) {
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
        $id = $persona_id ?? $request->query('persona_id') ?? session('persona_id');

        if (!$id) {
            return redirect()->route('estudiantes.informacion')
                ->with('warning', 'Primero selecciona/busca un estudiante.');
        }

        $persona = Persona::with([
            'estudiante.tipoBecas.propiedade',
            'estudiante.listadosAsistencia.asistencia'
        ])->find($id);

        if (!$persona) {
            return redirect()->route('estudiantes.informacion')
                ->with('warning', 'Estudiante no encontrado.');
        }

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

        $estudiante = Estudiante::with('tipoBecas.propiedade')->findOrFail($request->estudiante_id);

        foreach ($request->tipo_asistencia as $tipo) {
            $asignar = $estudiante->tipoBecas->contains(function ($b) use ($tipo) {
                $nombre = strtolower($b->propiedade->nombre ?? '');
                return ($tipo === 'desayuno' && str_contains($nombre, 'desayuno'))
                    || ($tipo === 'almuerzo' && str_contains($nombre, 'almuerzo'));
            });

            if (!$asignar) {
                return back()->with('error', "El estudiante no tiene la beca correspondiente para: $tipo");
            }

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

            ListadoAsistencia::create([
                'estudiante_id' => $estudiante->id,
                'asistencia_id' => $asistencia->id
            ]);
        }

        return back()->with('success', 'Asistencia guardada correctamente.');
    }
public function confirmar(Request $request)
{
    $request->validate([
        'estudiante_id' => 'required|exists:estudiantes,id',
        'tipo_beca' => 'required|exists:tipo_becas,id',
    ]);

    $estudiante = Estudiante::with('tipoBecas')->find($request->estudiante_id);

    if (!$estudiante->tipoBecas->pluck('id')->contains($request->tipo_beca)) {
        return back()->with('error', 'El estudiante no tiene la beca seleccionada.');
    }

    $tipoBeca = TipoBeca::find($request->tipo_beca);
    $tipo_asistencia = strtolower(str_replace([' ', '-'], ['_', '_'], $tipoBeca->propiedade->nombre));

    $hoy = now()->startOfDay();

    // 1️⃣ Obtener todas las asistencias ausentes del día y tipo
    $asistenciasAusentes = Asistencia::whereDate('fecha_hora', $hoy)
        ->where('tipo_asistencia', $tipo_asistencia)
        ->where('estado', 'ausente')
        ->get();

    // Eliminar solo las relaciones en ListadoAsistencia
    foreach ($asistenciasAusentes as $asistenciaAusente) {
        ListadoAsistencia::where('estudiante_id', $estudiante->id)
            ->where('asistencia_id', $asistenciaAusente->id)
            ->delete();
    }

    // 2️⃣ Crear o usar la asistencia presente del día
    $asistenciaPresente = Asistencia::firstOrCreate([
        'fecha_hora' => $hoy,
        'tipo_asistencia' => $tipo_asistencia,
        'estado' => 'presente'
    ]);

    // 3️⃣ Crear registro en ListadoAsistencia solo si no existe
    ListadoAsistencia::firstOrCreate([
        'estudiante_id' => $estudiante->id,
        'asistencia_id' => $asistenciaPresente->id,
    ]);

    return back()->with('success', 'Asistencia registrada correctamente.');
}


    

}
