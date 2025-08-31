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

public function mensualPdf(Request $request)
{
    $mes = $request->input('mes', Carbon::now()->month);
    $anio = $request->input('anio', Carbon::now()->year);

    $inicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
    $finMes = Carbon::create($anio, $mes, 1)->endOfMonth();

    $estudiantes = Estudiante::with([
        'persona',
        'tipoBecas.propiedade',
        'listadosAsistencia.asistencia' => function($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_hora', [$inicioMes, $finMes]);
        }
    ])->get();

    $tiposBeca = \App\Models\TipoBeca::with('propiedade')->get();
    $resumenGeneral = [];
    foreach ($tiposBeca as $tipo) {
        $resumenGeneral[$tipo->propiedade->nombre] = [];
    }

    foreach ($estudiantes as $estudiante) {
        $persona = $estudiante->persona;
        $nombre = $persona ? trim("{$persona->Nombre} {$persona->PrimerApellido} {$persona->SegundoApellido}") : 'Sin Nombre';

        foreach ($estudiante->tipoBecas as $beca) {
            $tipoBecaNombre = optional($beca->propiedade)->nombre ?? 'Sin Beca';
            $bloques = [];

            foreach ($estudiante->listadosAsistencia as $listado) {
                $asistencia = $listado->asistencia;
                if (!$asistencia) continue;

                $fecha = Carbon::parse($asistencia->fecha_hora);
                $dia = $fecha->day;
                $bloque = ceil($dia / 10); // Bloques de 10 días

                if (!isset($bloques[$bloque])) {
                    $bloques[$bloque] = ['presente' => 0, 'ausente' => 0];
                }

                if (strtolower($asistencia->tipo_asistencia) == strtolower($tipoBecaNombre)) {
                    $asistencia->estado === 'presente'
                        ? $bloques[$bloque]['presente']++
                        : $bloques[$bloque]['ausente']++;
                }
            }

            if (count($bloques) > 0) {
                $resumenGeneral[$tipoBecaNombre][] = [
                    'nombre' => $nombre,
                    'bloques' => $bloques
                ];
            }
        }
    }

    $pdf = Pdf::loadView('PdfReporte.ReportePdf', compact('resumenGeneral', 'mes', 'anio'));
    return $pdf->download("reporte_asistencia_{$mes}_{$anio}.pdf");
}

public function pdf(Request $request)
{
 // Recibir fecha en formato YYYY-MM
    $fechaInput = $request->input('fecha', Carbon::now()->format('Y-m'));
    [$anio, $mes] = explode('-', $fechaInput);

    $inicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
    $finMes = Carbon::create($anio, $mes, 1)->endOfMonth();

    // Tu código para obtener estudiantes, asistencias y generar $resumenGeneral...
    $estudiantes = Estudiante::with([
        'persona',
        'tipoBeca.propiedade',
        'listadosAsistencia.asistencia' => function($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_hora', [$inicioMes, $finMes]);
        }
    ])->get();

    $resumenGeneral = [
        'Almuerzo' => [],
        'Desayuno' => [],
        'Desayuno - Almuerzo' => []
    ];

    foreach ($estudiantes as $estudiante) {
        $persona = $estudiante->persona;
        $nombre = $persona ? trim("{$persona->Nombre} {$persona->PrimerApellido} {$persona->SegundoApellido}") : 'Sin Nombre';
        
        $tipoBecaNombre = optional($estudiante->tipoBeca->propiedade)->nombre ?? 'Sin Beca';
        if ($tipoBecaNombre == 'Almuerzo') $tipoBeca = 'Almuerzo';
        elseif ($tipoBecaNombre == 'Desayuno') $tipoBeca = 'Desayuno';
        else $tipoBeca = 'Desayuno - Almuerzo';

        $semanas = [];

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
                $asistencia->estado == 'presente' ? $semanas[$semana]['desayuno_asist']++ : $semanas[$semana]['desayuno_ausente']++;
            } elseif ($asistencia->tipo_asistencia == 'almuerzo') {
                $asistencia->estado == 'presente' ? $semanas[$semana]['almuerzo_asist']++ : $semanas[$semana]['almuerzo_ausente']++;
            }
        }

        if (count($semanas) > 0) {
            $resumenGeneral[$tipoBeca][] = [
                'nombre' => $nombre,
                'semanas' => $semanas
            ];
        }
    }

    $pdf = Pdf::loadView('PdfReporte.ReportePdf', compact('resumenGeneral', 'mes', 'anio'));

    return $pdf->download("reporte_asistencia_{$mes}_{$anio}.pdf");
}

}
