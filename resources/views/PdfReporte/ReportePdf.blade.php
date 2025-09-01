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
        .bloque-title { margin-top: 15px; font-weight: bold; }
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

@foreach($resumenGeneral as $tipoBeca => $estudiantesBeca)
    <div class="tipo-beca">
        <h2>Becados de {{ $tipoBeca }}</h2>

        @if(count($estudiantesBeca) === 0)
            <p>No hay registros de asistencias para esta categoría de beca.</p>
        @else
            @php
                $bloquesAgrupados = [];
                $mesTotalPresente = 0;
                $mesTotalAusente = 0;

                foreach($estudiantesBeca as $estudiante) {
                    foreach($estudiante['bloques'] as $numBloque => $datos) {
                        $bloquesAgrupados[$numBloque][] = [
                            'nombre' => $estudiante['nombre'],
                            'presente' => $datos['presente'],
                            'ausente' => $datos['ausente'],
                        ];
                        $mesTotalPresente += $datos['presente'];
                        $mesTotalAusente += $datos['ausente'];
                    }
                }
            @endphp

            {{-- Imprimir por bloques de 10 días --}}
            @foreach($bloquesAgrupados as $numBloque => $estudiantesBloque)
                @php
                    $inicio = ($numBloque - 1) * 10 + 1;
                    $fin = min($numBloque * 10, \Carbon\Carbon::create($anio, $mes, 1)->daysInMonth);
                @endphp
                <p class="bloque-title">Días {{ $inicio }} - {{ $fin }}</p>
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
                            $totalPresenteBloque = 0;
                            $totalAusenteBloque = 0;
                        @endphp

                        @foreach($estudiantesBloque as $datos)
                            @php
                                $total = $datos['presente'] + $datos['ausente'];
                                $porcentaje = $total > 0 ? round(($datos['presente'] / $total) * 100, 2) : 0;
                                $totalPresenteBloque += $datos['presente'];
                                $totalAusenteBloque += $datos['ausente'];
                            @endphp
                            <tr>
                                <td>{{ $datos['nombre'] }}</td>
                                <td>{{ $datos['presente'] }}</td>
                                <td>{{ $datos['ausente'] }}</td>
                                <td>{{ $porcentaje }}%</td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <td>Total / % General</td>
                            <td>{{ $totalPresenteBloque }}</td>
                            <td>{{ $totalAusenteBloque }}</td>
                            <td>
                                {{ ($totalPresenteBloque + $totalAusenteBloque) > 0
                                    ? round(($totalPresenteBloque / ($totalPresenteBloque + $totalAusenteBloque)) * 100, 2)
                                    : 0 }}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endforeach

            {{-- Totales mensuales por estudiante --}}
            <div class="resumen-final">
                <h3>Resumen Mensual por Estudiante - {{ $tipoBeca }}</h3>
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
                        @foreach($estudiantesBeca as $estudiante)
                            @php
                                $p = array_sum(array_column($estudiante['bloques'], 'presente'));
                                $a = array_sum(array_column($estudiante['bloques'], 'ausente'));
                                $total = $p + $a;
                                $porcentaje = $total > 0 ? round(($p / $total) * 100, 2) : 0;
                            @endphp
                            <tr>
                                <td>{{ $estudiante['nombre'] }}</td>
                                <td>{{ $p }}</td>
                                <td>{{ $a }}</td>
                                <td>{{ $porcentaje }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endforeach

</body>
</html>
