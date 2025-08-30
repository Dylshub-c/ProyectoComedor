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
        $fecha = Carbon::now('America/Costa_Rica')->startOfDay(); // Fecha actual en Costa Rica
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
                        'fecha_hora' => Carbon::now('America/Costa_Rica'),
                        'tipo_asistencia' => $tipo,
                        'estado' => $estado,
                    ]);
                }
            }
        }

        // 3️⃣ Asignar ausencias según las becas de cada estudiante
        $estudiantes = Estudiante::with('tipoBecas.propiedade')->get();

        foreach ($estudiantes as $estudiante) {
            $tiposAsignar = $estudiante->tipoBecas
                ->map(fn($beca) => strtolower(str_replace([' ', '-'], ['_', '_'], $beca->propiedade->nombre ?? '')))
                ->unique();

            foreach ($tiposAsignar as $tipo) {
                $tienePresente = ListadoAsistencia::where('estudiante_id', $estudiante->id)
                    ->whereHas('asistencia', function ($q) use ($tipo, $fecha) {
                        $q->whereDate('fecha_hora', $fecha)
                          ->where('tipo_asistencia', $tipo)
                          ->where('estado', 'presente');
                    })->exists();

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
        $persona = null; 
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

    public function asistenciaRapidaIndex(Request $request)
{
    // Si viene AJAX y se envía beca_id, devolver estudiantes
    if($request->ajax() && $request->has('beca_id')){
        $estudiantes = Estudiante::with('tipoBecas.propiedade', 'persona')
            ->whereHas('tipoBecas', function($q) use ($request){
                $q->where('tipo_beca_id', $request->beca_id);
            })->get();

        $res = $estudiantes->map(function($e){
            return [
                'id' => $e->id,
                'nombre_completo' => $e->persona->Nombre . ' ' . $e->persona->PrimerApellido . ' ' . $e->persona->SegundoApellido,
                'beca' => $e->tipoBecas->first()->propiedade->nombre ?? '-'
            ];
        });

        return response()->json($res);
    }

    $tiposBeca = TipoBeca::with('propiedade')->get();
    return view('AsistenciaRapida.asistenciaRapida', compact('tiposBeca'));

}
public function guardarAsistenciaRapida(Request $request)
{
    $request->validate([
        'fecha_hora' => 'required|date',
        'tipo_asistencia' => 'required|exists:tipo_becas,id',
        'estudiantes' => 'required|array',
        'estudiantes.*.id' => 'required|exists:estudiantes,id',
        'estudiantes.*.presente' => 'required|boolean',
        'observaciones' => 'nullable|string',
    ]);

    $fecha = Carbon::parse($request->fecha_hora)->startOfDay();
    $tipoBeca = TipoBeca::find($request->tipo_asistencia);
    $tipo_asistencia = strtolower(str_replace([' ', '-'], ['_', '_'], $tipoBeca->propiedade->nombre));

    // Crear o usar asistencias del día
    foreach (['presente', 'ausente'] as $estado) {
        Asistencia::firstOrCreate([
            'fecha_hora' => $fecha,
            'tipo_asistencia' => $tipo_asistencia,
            'estado' => $estado,
        ]);
    }

    // Obtener IDs de las asistencias para esa fecha y tipo
    $asistencias = Asistencia::whereDate('fecha_hora', $fecha)
        ->where('tipo_asistencia', $tipo_asistencia)
        ->pluck('id')
        ->toArray();

    foreach ($request->estudiantes as $estudianteData) {
        $estudianteId = $estudianteData['id'];
        $estado = $estudianteData['presente'] ? 'presente' : 'ausente';

        // Eliminar cualquier registro previo de este estudiante para estas asistencias
        ListadoAsistencia::where('estudiante_id', $estudianteId)
            ->whereIn('asistencia_id', $asistencias)
            ->delete();

        // Crear el nuevo registro solo para el estado actual
        $asistencia = Asistencia::whereDate('fecha_hora', $fecha)
            ->where('tipo_asistencia', $tipo_asistencia)
            ->where('estado', $estado)
            ->first();

        if ($asistencia) {
            ListadoAsistencia::create([
                'estudiante_id' => $estudianteId,
                'asistencia_id' => $asistencia->id,
                'observaciones' => $request->observaciones,
            ]);
        }
    }

    return response()->json(['message' => 'Asistencia registrada correctamente.']);
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

public function revisarAsistencia($persona_id)
{
    // Obtener el estudiante por persona_id
    $persona = Persona::with('estudiante.tipoBecas', 'estudiante.seccione', 'estudiante.especialidade')->findOrFail($persona_id);

    // Opcional: obtener asistencias del estudiante
    $asistencias = $persona->estudiante->asistencias ?? [];

    // Retornar la vista con los datos
    return view('estudiantes.informacion', compact('persona', 'asistencias'));
}

    

}
