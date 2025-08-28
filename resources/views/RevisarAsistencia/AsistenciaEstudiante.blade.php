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
<link rel="Stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />

</head>
<body>
<button id="btn-Menu" class="btn ms-3 mb-3 fs-5 py-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <i class="fa-solid fa-bars fa-xl" style="color: #f7f7f7;"></i>
    </button>
     <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header justify-content-end">
            <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close"> <i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i> </button>
        </div>
        <div class="offcanvas-body">
            <div class="d-grid gap-3">
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location.href='{{ route('admin.home') }}'">
                    <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu" ></i>
                    | Home
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i>
                    | Ingreso al comedor
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                    <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i>
                    | Agregar usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.informacion') }}'">
                    <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i>
                    | Ver lista de usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('Reportes.DescargarReporte') }}'">
                    <i class="fa-solid fa-download fa-lg" id="icono-menu"></i>
                    | Descargar reportes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('tipobeca.index') }}'">
                    <i class="fa-solid fa-hand-holding-medical fa-lg" id="icono-menu"></i>
                    | Becas
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('AsistenciaRapida.asistenciaRapida') }}'">
                    <i class="fa-solid fa-star-half-stroke fa-lg" id="icono-menu"></i>
                    | Asistencia rápida
                </button>

            </div>
        </div>
        <div class="offcanvas-footer p-3 border-top">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                 <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                <i class="fa-solid fa-arrow-right-to-bracket fa-lg" id="icono-menu"></i>
                | Cerrar sesión
                </button>
            </form>

            </div>
        </div>



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
