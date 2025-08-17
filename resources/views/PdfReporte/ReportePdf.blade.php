<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia - {{ $mes }}/{{ $anio }}</title>
    <style>
        body { font-family: Arial; font-size: 12px; margin: 20px; }
        h1, h2, h3 { text-align: center; margin: 0; }
        .reporte-header { margin-bottom: 20px; }
        .semana { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: center; }
        th { background-color: #eaeaea; }
        .resumen { margin-top: 30px; }
        .promedio { margin-top: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="reporte-header">
        <h1>Comedor Estudiantil</h1>
        <h2>Reporte Mensual de Asistencia</h2>
        <p style="text-align: center">Mes: {{ $mes }} - Año: {{ $anio }}</p>
    </div>

    @php
        $resumenGeneral = [];
    @endphp

    @foreach($semanas as $numSemana => $asistenciasSemana)
        <div class="semana">
            <h3>Semana {{ $numSemana }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Desayuno (Presente)</th>
                        <th>Desayuno (Ausente)</th>
                        <th>% Desayuno (P)</th>
                        <th>Almuerzo (Presente)</th>
                        <th>Almuerzo (Ausente)</th>
                        <th>% Almuerzo (P)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $estudiantesSemana = [];
                    @endphp

                    @foreach($asistenciasSemana as $registro)
                        @php
                            $estudianteId = $registro->estudiante->id;
                            $persona = $registro->estudiante->persona;

                            // Nombre completo con fallback
                            $nombre = trim(($persona->Nombre ?? '') . ' ' . ($persona->PrimerApellido ?? '') . ' ' . ($persona->SegundoApellido ?? ''));
                            if(empty($nombre)) $nombre = 'Sin Nombre';

                            $tipo = $registro->asistencia->tipo_asistencia;
                            $estado = $registro->asistencia->estado;

                            if(!isset($estudiantesSemana[$estudianteId])){
                                $estudiantesSemana[$estudianteId] = [
                                    'nombre' => $nombre,
                                    'desayuno_asist' => 0,
                                    'desayuno_ausente' => 0,
                                    'almuerzo_asist' => 0,
                                    'almuerzo_ausente' => 0
                                ];
                            }

                            if($tipo == 'desayuno'){
                                $estado == 'presente' ? $estudiantesSemana[$estudianteId]['desayuno_asist']++ : $estudiantesSemana[$estudianteId]['desayuno_ausente']++;
                            } elseif($tipo == 'almuerzo'){
                                $estado == 'presente' ? $estudiantesSemana[$estudianteId]['almuerzo_asist']++ : $estudiantesSemana[$estudianteId]['almuerzo_ausente']++;
                            }

                            // Resumen general mensual
                            if(!isset($resumenGeneral[$estudianteId])){
                                $resumenGeneral[$estudianteId] = [
                                    'nombre' => $nombre,
                                    'desayuno_asist' => 0,
                                    'desayuno_ausente' => 0,
                                    'almuerzo_asist' => 0,
                                    'almuerzo_ausente' => 0
                                ];
                            }
                            if($tipo == 'desayuno'){
                                $estado == 'presente' ? $resumenGeneral[$estudianteId]['desayuno_asist']++ : $resumenGeneral[$estudianteId]['desayuno_ausente']++;
                            } elseif($tipo == 'almuerzo'){
                                $estado == 'presente' ? $resumenGeneral[$estudianteId]['almuerzo_asist']++ : $resumenGeneral[$estudianteId]['almuerzo_ausente']++;
                            }
                        @endphp
                    @endforeach

                    @foreach($estudiantesSemana as $totales)
                        @php
                            $totalDesayuno = $totales['desayuno_asist'] + $totales['desayuno_ausente'];
                            $porcDesayuno = $totalDesayuno > 0 ? round(($totales['desayuno_asist'] / $totalDesayuno) * 100, 2) : 0;

                            $totalAlmuerzo = $totales['almuerzo_asist'] + $totales['almuerzo_ausente'];
                            $porcAlmuerzo = $totalAlmuerzo > 0 ? round(($totales['almuerzo_asist'] / $totalAlmuerzo) * 100, 2) : 0;
                        @endphp
                        <tr>
                            <td>{{ $totales['nombre'] }}</td>
                            <td>{{ $totales['desayuno_asist'] }}</td>
                            <td>{{ $totales['desayuno_ausente'] }}</td>
                            <td>{{ $porcDesayuno }}%</td>
                            <td>{{ $totales['almuerzo_asist'] }}</td>
                            <td>{{ $totales['almuerzo_ausente'] }}</td>
                            <td>{{ $porcAlmuerzo }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @php
                // Promedio de asistencia por tipo en la semana
                $promedioDesayunoSemana = round(collect($estudiantesSemana)->avg(function($totales){
                    $totalDesayuno = $totales['desayuno_asist'] + $totales['desayuno_ausente'];
                    return $totalDesayuno > 0 ? ($totales['desayuno_asist'] / $totalDesayuno) * 100 : 0;
                }), 2);

                $promedioAlmuerzoSemana = round(collect($estudiantesSemana)->avg(function($totales){
                    $totalAlmuerzo = $totales['almuerzo_asist'] + $totales['almuerzo_ausente'];
                    return $totalAlmuerzo > 0 ? ($totales['almuerzo_asist'] / $totalAlmuerzo) * 100 : 0;
                }), 2);
            @endphp
            <p class="promedio">Promedio Total Desayuno Semana: {{ $promedioDesayunoSemana }}% | Promedio Total Almuerzo Semana: {{ $promedioAlmuerzoSemana }}%</p>
        </div>
    @endforeach

    <div class="resumen">
        <h3>Resumen General del Mes</h3>
        <table>
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Total Desayuno (Presente)</th>
                    <th>Total Desayuno (Ausente)</th>
                    <th>% Desayuno</th>
                    <th>Total Almuerzo (Presente)</th>
                    <th>Total Almuerzo (Ausente)</th>
                    <th>% Almuerzo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resumenGeneral as $totales)
                    @php
                        $totalDesayuno = $totales['desayuno_asist'] + $totales['desayuno_ausente'];
                        $porcDesayuno = $totalDesayuno > 0 ? round(($totales['desayuno_asist'] / $totalDesayuno) * 100, 2) : 0;

                        $totalAlmuerzo = $totales['almuerzo_asist'] + $totales['almuerzo_ausente'];
                        $porcAlmuerzo = $totalAlmuerzo > 0 ? round(($totales['almuerzo_asist'] / $totalAlmuerzo) * 100, 2) : 0;
                    @endphp
                    <tr>
                        <td>{{ $totales['nombre'] }}</td>
                        <td>{{ $totales['desayuno_asist'] }}</td>
                        <td>{{ $totales['desayuno_ausente'] }}</td>
                        <td>{{ $porcDesayuno }}%</td>
                        <td>{{ $totales['almuerzo_asist'] }}</td>
                        <td>{{ $totales['almuerzo_ausente'] }}</td>
                        <td>{{ $porcAlmuerzo }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php
            // Promedio total mensual por tipo
            $promedioDesayunoMes = round(collect($resumenGeneral)->avg(function($totales){
                $total = $totales['desayuno_asist'] + $totales['desayuno_ausente'];
                return $total > 0 ? ($totales['desayuno_asist'] / $total) * 100 : 0;
            }), 2);

            $promedioAlmuerzoMes = round(collect($resumenGeneral)->avg(function($totales){
                $total = $totales['almuerzo_asist'] + $totales['almuerzo_ausente'];
                return $total > 0 ? ($totales['almuerzo_asist'] / $total) * 100 : 0;
            }), 2);
        @endphp

        <p class="promedio">
            Promedio Total Desayuno Mes: {{ $promedioDesayunoMes }}% | 
            Promedio Total Almuerzo Mes: {{ $promedioAlmuerzoMes }}%
        </p>
    </div>
</body>
</html>
