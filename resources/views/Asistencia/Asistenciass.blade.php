<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asistencia</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="{{ asset('css/Asistencia.css') }}">
</head>
<body>
  @if(!$persona)
    <div class="container mt-4">
      <div class="alert alert-warning text-center">
        No hay estudiante seleccionado.  
        <a href="{{ route('estudiantes.informacion') }}" class="btn btn-link">Volver</a>
      </div>
    </div>
  @else
  <div class="main-container d-flex">

    <!-- Panel izquierdo -->
    <div class="left-panel d-flex flex-column align-items-center">

      <!-- Perfil del estudiante -->
      <div class="card estudiante-card text-center mb-3">
        <img src="{{ asset($persona->estudiante->foto ?? 'img/FotoEstudiante.webp') }}"
             class="estudiante-avatar"
             alt="Avatar del estudiante" />
        <div class="card-body">
          <h5 class="card-title">
            {{ $persona->Nombre }} {{ $persona->PrimerApellido }} {{ $persona->SegundoApellido }}
          </h5>
          <p class="card-text">{{ $persona->Cedula }}</p>
        </div>
      </div>

      <!-- Tipo de beca -->
      <div class="card tipo-beca-card text-center">
        <div class="card-header fw-bold">Tipo de beca asignada</div>
        <div class="card-body">
          @php
            $beca = $persona->estudiante->tipoBeca->propiedade->nombre ?? '';
          @endphp
          <div class="form-check">
            <input class="form-check-input" type="checkbox" disabled
                   {{ in_array($beca, ['Desayuno', 'Desayuno - Almuerzo']) ? 'checked' : '' }}>
            <label class="form-check-label">Beca de Desayuno</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" disabled
                   {{ in_array($beca, ['Almuerzo', 'Desayuno - Almuerzo']) ? 'checked' : '' }}>
            <label class="form-check-label">Beca de Almuerzo</label>
          </div>
        </div>
        <img src="{{ asset('img/LogoCovao.webp') }}" alt="COVAO logo" class="logo-covao" />
      </div>
    </div>

    <!-- Panel derecho -->
    <div class="right-panel card flex-grow-1">
      <div class="card-body">
        <h5 class="mb-3 fw-bold">Rango de fechas</h5>
        <div class="row mb-4">
          <div class="col">
            <label for="fechaInicio" class="form-label">Fecha de inicio</label>
            <input type="date" id="fechaInicio" class="form-control">
          </div>
          <div class="col">
            <label for="fechaFinal" class="form-label">Fecha final</label>
            <input type="date" id="fechaFinal" class="form-control">
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              @if(isset($listados) && $listados->count())
                @foreach($listados as $listado)
                  <tr>
                    <td>{{ \Carbon\Carbon::parse($listado->asistencia->fecha_hora)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($listado->asistencia->tipo_asistencia) }}</td>
                    <td>{{ ucfirst($listado->asistencia->estado) }}</td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="3" class="text-muted">No hay registros de asistencia.</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
  @endif

  <script src="{{ asset('js/Asistencia.js') }}"></script>
</body>
</html>
