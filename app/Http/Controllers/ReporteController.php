<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
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
class ReporteController extends Controller
{
     public function descargar()
    {
         $estudiantes = Estudiante::with(['persona', 'listadosAsistencia.asistencia'])->get();
        return view('Reportes.DescargarReporte', compact('estudiantes'));
    }
    public function mensual(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->month);
        $anio = $request->input('anio', Carbon::now()->year);

        // Fechas de inicio y fin del mes
        $inicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
        $finMes = Carbon::create($anio, $mes, 1)->endOfMonth();

        // Traer asistencias con estudiante y asistencia
        $asistencias = ListadoAsistencia::with(['estudiante.persona', 'asistencia'])
            ->whereBetween('asistencia.fecha_hora', [$inicioMes, $finMes])
            ->get();

        // Agrupar por semana y tipo
        $semanas = $asistencias->groupBy(function ($item) {
            return Carbon::parse($item->asistencia->fecha_hora)->weekOfMonth;
        });

        return view('reportes.mensual', compact('semanas', 'mes', 'anio'));
    }
public function mensualPdf(Request $request)
{
    $mes = $request->input('mes', Carbon::now()->month);
    $anio = $request->input('anio', Carbon::now()->year);

    $inicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
    $finMes = Carbon::create($anio, $mes, 1)->endOfMonth();

    // Obtener todos los estudiantes con sus relaciones necesarias
    $estudiantes = Estudiante::with([
        'persona',
        'tipoBeca.propiedade',
        'listadosAsistencia.asistencia' => function($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_hora', [$inicioMes, $finMes]);
        }
    ])->get();

    // Inicializar resumen
    $resumenGeneral = [
        'Almuerzo' => [],
        'Desayuno' => [],
        'Desayuno - Almuerzo' => []
    ];

    foreach ($estudiantes as $estudiante) {
        $persona = $estudiante->persona;
        $nombre = $persona ? trim("{$persona->Nombre} {$persona->PrimerApellido} {$persona->SegundoApellido}") : 'Sin Nombre';

        // Obtener nombre de la beca y normalizarlo
        $tipoBecaNombre = optional($estudiante->tipoBeca->propiedade)->nombre ?? 'Sin Beca';
        if ($tipoBecaNombre == 'Almuerzo') {
            $tipoBeca = 'Almuerzo';
        } elseif ($tipoBecaNombre == 'Desayuno') {
            $tipoBeca = 'Desayuno';
        } else {
            $tipoBeca = 'Desayuno - Almuerzo';
        }

        $semanas = [];

        // Contar asistencias por semana
        foreach ($estudiante->listadosAsistencia as $listado) {
            $asistencia = $listado->asistencia;
            if (!$asistencia) continue;

            $semana = Carbon::parse($asistencia->fecha_hora)->weekOfMonth;

            if (!isset($semanas[$semana])) {
                $semanas[$semana] = [
                    'desayuno_asist' => 0,
                    'desayuno_ausente' => 0,
                    'almuerzo_asist' => 0,
                    'almuerzo_ausente' => 0
                ];
            }

            if ($asistencia->tipo_asistencia == 'desayuno') {
                $asistencia->estado == 'presente' 
                    ? $semanas[$semana]['desayuno_asist']++ 
                    : $semanas[$semana]['desayuno_ausente']++;
            } elseif ($asistencia->tipo_asistencia == 'almuerzo') {
                $asistencia->estado == 'presente' 
                    ? $semanas[$semana]['almuerzo_asist']++ 
                    : $semanas[$semana]['almuerzo_ausente']++;
            }
        }

        // Solo agregar si hubo asistencias en el mes
        if (count($semanas) > 0) {
            $resumenGeneral[$tipoBeca][] = [
                'nombre' => $nombre,
                'semanas' => $semanas
            ];
        }
    }

    // Asegurarse que siempre se pase la variable aunque esté vacía
    if (!isset($resumenGeneral)) {
        $resumenGeneral = [
            'Almuerzo' => [],
            'Desayuno' => [],
            'Desayuno - Almuerzo' => []
        ];
    }

    // Generar PDF
    $pdf = Pdf::loadView('PdfReporte.ReportePdf', compact('resumenGeneral', 'mes', 'anio'));

    return $pdf->download("reporte_asistencia_{$mes}_{$anio}.pdf");
}





public function pdf(Request $request)
{
    $mes = $request->input('mes', Carbon::now()->month);
    $anio = $request->input('anio', Carbon::now()->year);

    $inicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
    $finMes = Carbon::create($anio, $mes, 1)->endOfMonth();

    // Obtener todos los estudiantes con sus relaciones necesarias
    $estudiantes = Estudiante::with([
        'persona',
        'tipoBeca.propiedade',
        'listadosAsistencia.asistencia' => function($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_hora', [$inicioMes, $finMes]);
        }
    ])->get();

    // Inicializar resumen
    $resumenGeneral = [
        'Almuerzo' => [],
        'Desayuno' => [],
        'Desayuno - Almuerzo' => []
    ];

    foreach ($estudiantes as $estudiante) {
        $persona = $estudiante->persona;
        $nombre = $persona ? trim("{$persona->Nombre} {$persona->PrimerApellido} {$persona->SegundoApellido}") : 'Sin Nombre';

        // Obtener nombre de la beca y normalizarlo
        $tipoBecaNombre = optional($estudiante->tipoBeca->propiedade)->nombre ?? 'Sin Beca';
        if ($tipoBecaNombre == 'Almuerzo') {
            $tipoBeca = 'Almuerzo';
        } elseif ($tipoBecaNombre == 'Desayuno') {
            $tipoBeca = 'Desayuno';
        } else {
            $tipoBeca = 'Desayuno - Almuerzo';
        }

        $semanas = [];

        // Contar asistencias por semana
        foreach ($estudiante->listadosAsistencia as $listado) {
            $asistencia = $listado->asistencia;
            if (!$asistencia) continue;

            $semana = Carbon::parse($asistencia->fecha_hora)->weekOfMonth;

            if (!isset($semanas[$semana])) {
                $semanas[$semana] = [
                    'desayuno_asist' => 0,
                    'desayuno_ausente' => 0,
                    'almuerzo_asist' => 0,
                    'almuerzo_ausente' => 0
                ];
            }

            if ($asistencia->tipo_asistencia == 'desayuno') {
                $asistencia->estado == 'presente' 
                    ? $semanas[$semana]['desayuno_asist']++ 
                    : $semanas[$semana]['desayuno_ausente']++;
            } elseif ($asistencia->tipo_asistencia == 'almuerzo') {
                $asistencia->estado == 'presente' 
                    ? $semanas[$semana]['almuerzo_asist']++ 
                    : $semanas[$semana]['almuerzo_ausente']++;
            }
        }

        // Solo agregar si hubo asistencias en el mes
        if (count($semanas) > 0) {
            $resumenGeneral[$tipoBeca][] = [
                'nombre' => $nombre,
                'semanas' => $semanas
            ];
        }
    }

    // Asegurarse que siempre se pase la variable aunque esté vacía
    if (!isset($resumenGeneral)) {
        $resumenGeneral = [
            'Almuerzo' => [],
            'Desayuno' => [],
            'Desayuno - Almuerzo' => []
        ];
    }

    // Generar PDF
    $pdf = Pdf::loadView('PdfReporte.ReportePdf', compact('resumenGeneral', 'mes', 'anio'));

    return $pdf->download("reporte_asistencia_{$mes}_{$anio}.pdf");
}

}
