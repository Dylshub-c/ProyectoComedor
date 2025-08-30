<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia - {{ $mes }}/{{ $anio }}</title>
    <style>
        body { font-family: Arial; font-size: 12px; margin: 20px; }
        h1, h2, h3 { text-align: center; margin: 0; }
        .reporte-header { margin-bottom: 20px; }
        .tipo-beca { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: center; }
        th { background-color: #eaeaea; }
        .semana-title { margin-top: 15px; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f2f2f2; }
        .resumen-final { margin-top: 40px; }
    </style>
</head>
<body>

<div class="reporte-header">
    <h1>Comedor Estudiantil</h1>
    <h2>Reporte Mensual de Asistencia</h2>
    <p style="text-align: center">Mes: {{ $mes }} - Año: {{ $anio }}</p>
</div>

{{-- Recorrer tipos de beca dinámicos --}}
@foreach($resumenGeneral as $tipoBeca => $estudiantesBeca)
    <div class="tipo-beca">
        <h2>Becados de {{ $tipoBeca }}</h2>

        @if(count($estudiantesBeca) === 0)
            <p>No hay registros de asistencias para esta categoría de beca.</p>
        @else
            @php
                $semanasAgrupadas = [];
                $mesTotalPresente = 0;
                $mesTotalAusente = 0;

                foreach($estudiantesBeca as $estudiante) {
                    foreach($estudiante['semanas'] as $numSemana => $datos) {
                        $semanasAgrupadas[$numSemana][] = [
                            'nombre' => $estudiante['nombre'],
                            'presente' => $datos['presente'],
                            'ausente' => $datos['ausente'],
                        ];
                        $mesTotalPresente += $datos['presente'];
                        $mesTotalAusente += $datos['ausente'];
                    }
                }
            @endphp

            {{-- Imprimir por semanas --}}
            @foreach($semanasAgrupadas as $numSemana => $estudiantesSemana)
                <p class="semana-title">Semana {{ $numSemana }}</p>
                <table>
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Presente</th>
                            <th>Ausente</th>
                            <th>% Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPresenteSemana = 0;
                            $totalAusenteSemana = 0;
                        @endphp

                        @foreach($estudiantesSemana as $datos)
                            @php
                                $totalSemana = $datos['presente'] + $datos['ausente'];
                                $porcentaje = $totalSemana > 0 ? round(($datos['presente'] / $totalSemana) * 100, 2) : 0;
                                $totalPresenteSemana += $datos['presente'];
                                $totalAusenteSemana += $datos['ausente'];
                            @endphp
                            <tr>
                                <td>{{ $datos['nombre'] }}</td>
                                <td>{{ $datos['presente'] }}</td>
                                <td>{{ $datos['ausente'] }}</td>
                                <td>{{ $porcentaje }}%</td>
                            </tr>
                        @endforeach

                        {{-- Totales por semana --}}
                        @php
                            $porcGeneralSemana = ($totalPresenteSemana + $totalAusenteSemana) > 0 
                                ? round(($totalPresenteSemana / ($totalPresenteSemana + $totalAusenteSemana)) * 100, 2) 
                                : 0;
                        @endphp
                        <tr class="total-row">
                            <td>Total / % General</td>
                            <td>{{ $totalPresenteSemana }}</td>
                            <td>{{ $totalAusenteSemana }}</td>
                            <td>{{ $porcGeneralSemana }}%</td>
                        </tr>
                    </tbody>
                </table>
            @endforeach

            {{-- Totales mensuales por tipo de beca --}}
            <div class="resumen-final">
                <h3>Porcentaje Mensual General - {{ $tipoBeca }}</h3>
                @php
                    $totalMes = $mesTotalPresente + $mesTotalAusente;
                    $porcMes = $totalMes > 0 ? round(($mesTotalPresente / $totalMes) * 100, 2) : 0;
                @endphp
                <table>
                    <thead>
                        <tr>
                            <th>Presente</th>
                            <th>Ausente</th>
                            <th>% Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="total-row">
                            <td>{{ $mesTotalPresente }}</td>
                            <td>{{ $mesTotalAusente }}</td>
                            <td>{{ $porcMes }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endforeach

</body>
</html>
