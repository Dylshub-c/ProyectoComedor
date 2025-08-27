<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asistencia</title>

  {{-- Bootstrap desde CDN --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/EstiloRevisarAs.css') }}">
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
            <input class="form-check-input" type="checkbox" 
                   {{ in_array($beca, ['Desayuno', 'Desayuno - Almuerzo']) ? 'checked' : '' }}>
            <label class="form-check-label">Beca de Desayuno</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" 
                   {{ in_array($beca, ['Almuerzo', 'Desayuno - Almuerzo']) ? 'checked' : '' }}>
            <label class="form-check-label">Beca de Almuerzo</label>
          </div>
        </div>
        <label for="fechaInicio" class="form-label">Fecha de inicio</label>
            <input type="date" id="fechaInicio" class="form-control">
      </div>
    </div>

    <!-- Panel derecho -->
    <div class="right-panel card flex-grow-1">
  <div id="SegundoModulo" class="container-fluid">
    <div id="calendar"></div>
  </div>
</div>
    </div>
  </div>
  @endif
<script>
    window.asistenciasEstudiante = @json($asistenciasEstudiante ?? []);
</script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src={{ asset('js/RevisarAsistencia.js') }}></script>
    <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>
</body>
</html>
