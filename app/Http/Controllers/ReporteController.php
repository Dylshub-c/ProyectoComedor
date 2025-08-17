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

        // Obtener asistencias filtrando por fecha
        $asistencias = ListadoAsistencia::with(['estudiante.persona', 'asistencia'])
            ->whereHas('asistencia', function($query) use ($inicioMes, $finMes) {
                $query->whereBetween('fecha_hora', [$inicioMes, $finMes]);
            })
            ->get();

        // Agrupar por semana
        $semanas = $asistencias->groupBy(function($item) {
            return Carbon::parse($item->asistencia->fecha_hora)->weekOfMonth;
        });

        $pdf = Pdf::loadView('PdfReporte.ReportePdf', compact('semanas', 'mes', 'anio'));

        return $pdf->download("reporte_asistencia_{$mes}_{$anio}.pdf");
    }

public function pdf(Request $request)
{
    $fecha = $request->input('fecha'); // viene en formato YYYY-MM

    if ($fecha) {
        // Forzar el día 1 para que Carbon lo entienda como fecha completa
        $fechaCarbon = \Carbon\Carbon::createFromFormat('Y-m', $fecha)->startOfMonth();
    } else {
        $fechaCarbon = \Carbon\Carbon::now()->startOfMonth();
    }

    $mes = (int) $fechaCarbon->format('m');
    $anio = (int) $fechaCarbon->format('Y');

    $inicioMes = $fechaCarbon->copy()->startOfMonth();
    $finMes   = $fechaCarbon->copy()->endOfMonth();

    // Filtrar asistencias por rango de fecha del mes elegido
    $asistencias = \App\Models\ListadoAsistencia::with(['estudiante.persona', 'asistencia'])
        ->whereHas('asistencia', function($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_hora', [$inicioMes, $finMes]);
        })
        ->get();

    // Agrupar por semana del mes elegido
    $semanas = $asistencias->groupBy(function ($item) {
        return \Carbon\Carbon::parse($item->asistencia->fecha_hora)->weekOfMonth;
    })->sortKeys();

    $mesNombre = $fechaCarbon->locale('es')->monthName;

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'PdfReporte.ReportePdf',
        compact('semanas', 'mes', 'anio', 'mesNombre')
    );

    return $pdf->download("reporte_asistencia_{$mes}_{$anio}.pdf");
}

}
