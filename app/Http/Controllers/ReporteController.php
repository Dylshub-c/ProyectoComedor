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
    // Permitir recibir 'fecha' (YYYY-MM) o mes/anio por separado
    $fecha = $request->input('fecha');
    if (!empty($fecha) && strpos($fecha, '-') !== false) {
        [$anio, $mes] = array_map('intval', explode('-', $fecha));
    } else {
        $mes = (int) $request->input('mes', \Carbon\Carbon::now()->month);
        $anio = (int) $request->input('anio', \Carbon\Carbon::now()->year);
    }

    $inicioMes = \Carbon\Carbon::create($anio, $mes, 1)->startOfMonth();
    $finMes    = \Carbon\Carbon::create($anio, $mes, 1)->endOfMonth();

    // Cargar estudiantes + asistencias SOLO del mes/año seleccionado
    $estudiantes = \App\Models\Estudiante::with([
        'persona',
        'tipoBecas.propiedade',
        'listadosAsistencia.asistencia' => function ($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_hora', [$inicioMes, $finMes]);
        },
    ])->get();

    // Inicializar por tipos de beca existentes (dinámico)
    $tiposBeca = \App\Models\TipoBeca::with('propiedade')->get();
    $resumenGeneral = [];
    foreach ($tiposBeca as $tipo) {
        $nombreTipo = $tipo->propiedade->nombre ?? 'Sin Beca';
        $resumenGeneral[$nombreTipo] = [];
    }

    foreach ($estudiantes as $estudiante) {
        $persona = $estudiante->persona;
        $nombreEst = $persona
            ? trim("{$persona->Nombre} {$persona->PrimerApellido} {$persona->SegundoApellido}")
            : 'Sin Nombre';

        foreach ($estudiante->tipoBecas as $beca) {
            $tipoBecaNombre = optional($beca->propiedade)->nombre ?? 'Sin Beca';

            // Asegurar clave del tipo de beca aunque no estuviera preinicializada
            if (!array_key_exists($tipoBecaNombre, $resumenGeneral)) {
                $resumenGeneral[$tipoBecaNombre] = [];
            }

            $bloques = [];

            foreach ($estudiante->listadosAsistencia as $listado) {
                $asistencia = $listado->asistencia;
                if (!$asistencia) continue;

                // Doble validación del mes/año (por si llega algo fuera del rango)
                $f = \Carbon\Carbon::parse($asistencia->fecha_hora);
                if ((int)$f->month !== (int)$mes || (int)$f->year !== (int)$anio) continue;

                // Contabilizar SOLO si coincide el tipo de beca
                if (mb_strtolower($asistencia->tipo_asistencia) !== mb_strtolower($tipoBecaNombre)) continue;

                // Bloque de 10 días
                $dia = (int) $f->day;              // 1..31
                $bloque = (int) ceil($dia / 10);   // 1..4

                if (!isset($bloques[$bloque])) {
                    $bloques[$bloque] = ['presente' => 0, 'ausente' => 0];
                }

                if ($asistencia->estado === 'presente') {
                    $bloques[$bloque]['presente']++;
                } else {
                    $bloques[$bloque]['ausente']++;
                }
            }

            if (!empty($bloques)) {
                ksort($bloques); // Para imprimir en orden 1,2,3,4
                $resumenGeneral[$tipoBecaNombre][] = [
                    'nombre'  => $nombreEst,
                    'bloques' => $bloques,
                ];
            }
        }
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'PdfReporte.ReportePdf',
        compact('resumenGeneral', 'mes', 'anio')
    );

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
