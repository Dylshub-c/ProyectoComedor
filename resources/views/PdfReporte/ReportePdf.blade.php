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

{{-- Recorrer tipos de beca --}}
@foreach(['Almuerzo', 'Desayuno', 'Desayuno - Almuerzo'] as $tipoBeca)
    <div class="tipo-beca">
        <h2>Becados de {{ $tipoBeca }}</h2>

        @if(count($resumenGeneral[$tipoBeca]) === 0)
            <p>No hay registros de asistencias para esta categoría de beca.</p>
        @else
            @php
                $semanasAgrupadas = [];
                $mesTotalDesayunoAsist = 0;
                $mesTotalDesayunoAus = 0;
                $mesTotalAlmuerzoAsist = 0;
                $mesTotalAlmuerzoAus = 0;

                foreach($resumenGeneral[$tipoBeca] as $estudiante) {
                    foreach($estudiante['semanas'] as $numSemana => $datos) {
                        $semanasAgrupadas[$numSemana][] = [
                            'nombre' => $estudiante['nombre'],
                            'desayuno_asist' => $datos['desayuno_asist'],
                            'desayuno_ausente' => $datos['desayuno_ausente'],
                            'almuerzo_asist' => $datos['almuerzo_asist'],
                            'almuerzo_ausente' => $datos['almuerzo_ausente'],
                        ];

                        // Totales mensuales
                        $mesTotalDesayunoAsist += $datos['desayuno_asist'];
                        $mesTotalDesayunoAus += $datos['desayuno_ausente'];
                        $mesTotalAlmuerzoAsist += $datos['almuerzo_asist'];
                        $mesTotalAlmuerzoAus += $datos['almuerzo_ausente'];
                    }
                }
            @endphp

            @foreach($semanasAgrupadas as $numSemana => $estudiantesSemana)
                <p class="semana-title">Semana {{ $numSemana }}</p>
                <table>
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            @if($tipoBeca == 'Almuerzo')
                                <th>Almuerzo (P)</th>
                                <th>Almuerzo (A)</th>
                                <th>% Almuerzo</th>
                            @elseif($tipoBeca == 'Desayuno')
                                <th>Desayuno (P)</th>
                                <th>Desayuno (A)</th>
                                <th>% Desayuno</th>
                            @else
                                <th>Desayuno (P)</th>
                                <th>Desayuno (A)</th>
                                <th>% Desayuno</th>
                                <th>Almuerzo (P)</th>
                                <th>Almuerzo (A)</th>
                                <th>% Almuerzo</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDesayunoAsist = 0;
                            $totalDesayunoAus = 0;
                            $totalAlmuerzoAsist = 0;
                            $totalAlmuerzoAus = 0;
                        @endphp

                        @foreach($estudiantesSemana as $totales)
                            <tr>
                                <td>{{ $totales['nombre'] }}</td>
                                @if($tipoBeca == 'Almuerzo')
                                    @php
                                        $totalAlmuerzoAsist += $totales['almuerzo_asist'];
                                        $totalAlmuerzoAus += $totales['almuerzo_ausente'];
                                        $totalAlmuerzo = $totales['almuerzo_asist'] + $totales['almuerzo_ausente'];
                                        $porcAlmuerzo = $totalAlmuerzo > 0 ? round(($totales['almuerzo_asist'] / $totalAlmuerzo) * 100, 2) : 0;
                                    @endphp
                                    <td>{{ $totales['almuerzo_asist'] }}</td>
                                    <td>{{ $totales['almuerzo_ausente'] }}</td>
                                    <td>{{ $porcAlmuerzo }}%</td>
                                @elseif($tipoBeca == 'Desayuno')
                                    @php
                                        $totalDesayunoAsist += $totales['desayuno_asist'];
                                        $totalDesayunoAus += $totales['desayuno_ausente'];
                                        $totalDesayuno = $totales['desayuno_asist'] + $totales['desayuno_ausente'];
                                        $porcDesayuno = $totalDesayuno > 0 ? round(($totales['desayuno_asist'] / $totalDesayuno) * 100, 2) : 0;
                                    @endphp
                                    <td>{{ $totales['desayuno_asist'] }}</td>
                                    <td>{{ $totales['desayuno_ausente'] }}</td>
                                    <td>{{ $porcDesayuno }}%</td>
                                @else
                                    @php
                                        $totalDesayunoAsist += $totales['desayuno_asist'];
                                        $totalDesayunoAus += $totales['desayuno_ausente'];
                                        $totalAlmuerzoAsist += $totales['almuerzo_asist'];
                                        $totalAlmuerzoAus += $totales['almuerzo_ausente'];
                                        $totalDesayuno = $totales['desayuno_asist'] + $totales['desayuno_ausente'];
                                        $porcDesayuno = $totalDesayuno > 0 ? round(($totales['desayuno_asist'] / $totalDesayuno) * 100, 2) : 0;
                                        $totalAlmuerzo = $totales['almuerzo_asist'] + $totales['almuerzo_ausente'];
                                        $porcAlmuerzo = $totalAlmuerzo > 0 ? round(($totales['almuerzo_asist'] / $totalAlmuerzo) * 100, 2) : 0;
                                    @endphp
                                    <td>{{ $totales['desayuno_asist'] }}</td>
                                    <td>{{ $totales['desayuno_ausente'] }}</td>
                                    <td>{{ $porcDesayuno }}%</td>
                                    <td>{{ $totales['almuerzo_asist'] }}</td>
                                    <td>{{ $totales['almuerzo_ausente'] }}</td>
                                    <td>{{ $porcAlmuerzo }}%</td>
                                @endif
                            </tr>
                        @endforeach

                        {{-- Fila de porcentaje general por semana --}}
                        <tr class="total-row">
                            <td>Total / % General</td>
                            @if($tipoBeca == 'Almuerzo')
                                @php
                                    $totalGeneral = $totalAlmuerzoAsist + $totalAlmuerzoAus;
                                    $porcGeneral = $totalGeneral > 0 ? round(($totalAlmuerzoAsist / $totalGeneral) * 100, 2) : 0;
                                @endphp
                                <td>{{ $totalAlmuerzoAsist }}</td>
                                <td>{{ $totalAlmuerzoAus }}</td>
                                <td>{{ $porcGeneral }}%</td>
                            @elseif($tipoBeca == 'Desayuno')
                                @php
                                    $totalGeneral = $totalDesayunoAsist + $totalDesayunoAus;
                                    $porcGeneral = $totalGeneral > 0 ? round(($totalDesayunoAsist / $totalGeneral) * 100, 2) : 0;
                                @endphp
                                <td>{{ $totalDesayunoAsist }}</td>
                                <td>{{ $totalDesayunoAus }}</td>
                                <td>{{ $porcGeneral }}%</td>
                            @else
                                @php
                                    $totalGeneralDesayuno = $totalDesayunoAsist + $totalDesayunoAus;
                                    $porcGeneralDesayuno = $totalGeneralDesayuno > 0 ? round(($totalDesayunoAsist / $totalGeneralDesayuno) * 100, 2) : 0;
                                    $totalGeneralAlmuerzo = $totalAlmuerzoAsist + $totalAlmuerzoAus;
                                    $porcGeneralAlmuerzo = $totalGeneralAlmuerzo > 0 ? round(($totalAlmuerzoAsist / $totalGeneralAlmuerzo) * 100, 2) : 0;
                                @endphp
                                <td>{{ $totalDesayunoAsist }}</td>
                                <td>{{ $totalDesayunoAus }}</td>
                                                                <td>{{ $porcGeneralDesayuno }}%</td>
                                <td>{{ $totalAlmuerzoAsist }}</td>
                                <td>{{ $totalAlmuerzoAus }}</td>
                                <td>{{ $porcGeneralAlmuerzo }}%</td>
                            @endif
                        </tr>

                    </tbody>
                </table>
            @endforeach

            {{-- Porcentaje mensual general por tipo de beca --}}
            <div class="resumen-final">
                <h3>Porcentaje Mensual General - {{ $tipoBeca }}</h3>
                <table>
                    <thead>
                        <tr>
                            @if($tipoBeca != 'Almuerzo')
                                <th>Desayuno (P)</th>
                                <th>Desayuno (A)</th>
                                <th>% Desayuno</th>
                            @endif
                            @if($tipoBeca != 'Desayuno')
                                <th>Almuerzo (P)</th>
                                <th>Almuerzo (A)</th>
                                <th>% Almuerzo</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="total-row">
                            @if($tipoBeca != 'Almuerzo')
                                @php
                                    $porcMensualDesayuno = ($mesTotalDesayunoAsist + $mesTotalDesayunoAus) > 0
                                        ? round(($mesTotalDesayunoAsist / ($mesTotalDesayunoAsist + $mesTotalDesayunoAus)) * 100, 2)
                                        : 0;
                                @endphp
                                <td>{{ $mesTotalDesayunoAsist }}</td>
                                <td>{{ $mesTotalDesayunoAus }}</td>
                                <td>{{ $porcMensualDesayuno }}%</td>
                            @endif
                            @if($tipoBeca != 'Desayuno')
                                @php
                                    $porcMensualAlmuerzo = ($mesTotalAlmuerzoAsist + $mesTotalAlmuerzoAus) > 0
                                        ? round(($mesTotalAlmuerzoAsist / ($mesTotalAlmuerzoAsist + $mesTotalAlmuerzoAus)) * 100, 2)
                                        : 0;
                                @endphp
                                <td>{{ $mesTotalAlmuerzoAsist }}</td>
                                <td>{{ $mesTotalAlmuerzoAus }}</td>
                                <td>{{ $porcMensualAlmuerzo }}%</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>

        @endif
    </div>
@endforeach

</body>
</html>

