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
        $hoy = Carbon::now('America/Costa_Rica')->startOfDay(); // fecha consistente
        $estados = ['presente', 'ausente'];

        // Obtener tipos de asistencia
        $tipos = TipoBeca::with('propiedade')
            ->get()
            ->pluck('propiedade.nombre')
            ->map(fn($nombre) => strtolower(str_replace([' ', '-'], ['_', '_'], $nombre)))
            ->unique()
            ->toArray();

        // Crear asistencias si no existen
        foreach ($tipos as $tipo) {
            foreach ($estados as $estado) {
                Asistencia::firstOrCreate([
                    'fecha_hora' => $hoy,
                    'tipo_asistencia' => $tipo,
                    'estado' => $estado,
                ]);
            }
        }

        // Asignar ausencias segÃºn becas
        $estudiantes = Estudiante::with('tipoBecas.propiedade')->get();

        foreach ($estudiantes as $estudiante) {
            $tiposAsignar = $estudiante->tipoBecas
                ->map(fn($beca) => strtolower(str_replace([' ', '-'], ['_', '_'], $beca->propiedade->nombre ?? '')))
                ->unique();

            foreach ($tiposAsignar as $tipo) {
                $tienePresente = ListadoAsistencia::where('estudiante_id', $estudiante->id)
                    ->whereHas('asistencia', function ($q) use ($tipo, $hoy) {
                        $q->whereDate('fecha_hora', $hoy)
                          ->where('tipo_asistencia', $tipo)
                          ->where('estado', 'presente');
                    })->exists();

                if (!$tienePresente) {
                    $asistenciaAusente = Asistencia::whereDate('fecha_hora', $hoy)
                        ->where('tipo_asistencia', $tipo)
                        ->where('estado', 'ausente')
                        ->first();

                    if ($asistenciaAusente) {
                        ListadoAsistencia::firstOrCreate([
                            'estudiante_id' => $estudiante->id,
                            'asistencia_id' => $asistenciaAusente->id,
                            'observaciones' => null
                        ]);
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
        if ($request->ajax() && $request->has('beca_id')) {
            $estudiantes = Estudiante::with('tipoBecas.propiedade', 'persona')
                ->whereHas('tipoBecas', function ($q) use ($request) {
                    $q->where('tipo_beca_id', $request->beca_id);
                })->get();

            $res = $estudiantes->map(function ($e) {
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

        foreach (['presente', 'ausente'] as $estado) {
            Asistencia::firstOrCreate([
                'fecha_hora' => $fecha,
                'tipo_asistencia' => $tipo_asistencia,
                'estado' => $estado,
            ]);
        }

        $asistencias = Asistencia::whereDate('fecha_hora', $fecha)
            ->where('tipo_asistencia', $tipo_asistencia)
            ->pluck('id')
            ->toArray();

        foreach ($request->estudiantes as $estudianteData) {
            $estudianteId = $estudianteData['id'];
            $estado = $estudianteData['presente'] ? 'presente' : 'ausente';

            ListadoAsistencia::where('estudiante_id', $estudianteId)
                ->whereIn('asistencia_id', $asistencias)
                ->delete();

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

        $fecha = Carbon::parse($request->fecha_hora)->startOfDay();
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

            $asistencia = Asistencia::firstOrCreate([
                'fecha_hora' => $fecha,
                'tipo_asistencia' => $tipo,
                'estado' => $request->estado
            ]);

            ListadoAsistencia::firstOrCreate([
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
        $hoy = Carbon::now('America/Costa_Rica')->startOfDay();

        $asistenciasAusentes = Asistencia::whereDate('fecha_hora', $hoy)
            ->where('tipo_asistencia', $tipo_asistencia)
            ->where('estado', 'ausente')
            ->get();

        foreach ($asistenciasAusentes as $asistenciaAusente) {
            ListadoAsistencia::where('estudiante_id', $estudiante->id)
                ->where('asistencia_id', $asistenciaAusente->id)
                ->delete();
        }

        $asistenciaPresente = Asistencia::firstOrCreate([
            'fecha_hora' => $hoy,
            'tipo_asistencia' => $tipo_asistencia,
            'estado' => 'presente'
        ]);

        ListadoAsistencia::firstOrCreate([
            'estudiante_id' => $estudiante->id,
            'asistencia_id' => $asistenciaPresente->id,
        ]);

        return back()->with('success', 'Asistencia registrada correctamente.');
    }

    public function revisarAsistencia($persona_id)
    {
        $persona = Persona::with('estudiante.tipoBecas', 'estudiante.seccione', 'estudiante.especialidade')->findOrFail($persona_id);
        $asistencias = $persona->estudiante->asistencias ?? [];
        return view('estudiantes.informacion', compact('persona', 'asistencias'));
    }

    public function revisar($persona_id)
    {
        $persona = Persona::with('estudiante')->findOrFail($persona_id);
        return view('RevisarAsistencia.AsistenciaEstudiante', compact('persona'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'tipo_asistencia' => 'required|string',
            'estado' => 'required|in:presente,ausente',
            'fecha' => 'required|date',
        ]);

        $fecha = Carbon::parse($request->fecha)->startOfDay();

        $asistencia = Asistencia::firstOrCreate([
            'fecha_hora' => $fecha,
            'tipo_asistencia' => $request->tipo_asistencia,
            'estado' => $request->estado,
        ]);

        ListadoAsistencia::updateOrCreate(
            ['estudiante_id' => $request->estudiante_id, 'asistencia_id' => $asistencia->id],
            ['observaciones' => null]
        );

        return redirect()->back()->with('success', 'Asistencia creada o modificada correctamente.');
    }

    public function guardarObservacion(Request $request, $id)
    {
        $request->validate(['observaciones' => 'nullable|string']);
        $listado = ListadoAsistencia::findOrFail($id);
        $listado->observaciones = $request->input('observaciones');
        $listado->save();
        return response()->json(['message' => 'ObservaciÃ³n actualizada']);
    }
}