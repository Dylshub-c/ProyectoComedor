<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asistencia</title>

  <!-- Bootstrap desde CDN -->
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
      <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close"> 
        <i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i> 
      </button>
    </div>
    <div class="offcanvas-body">
      <div class="d-grid gap-3">
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu"></i> | Home
        </button>
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i> | Ingreso al comedor
        </button>
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i> | Agregar usuarios
        </button>
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i> | Ver lista de usuarios
        </button>
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-download fa-lg" id="icono-menu"></i> | Descargar reportes
        </button>
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-hand-holding-medical fa-lg" id="icono-menu"></i> | Becas
        </button>
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-star-half-stroke fa-lg" id="icono-menu"></i> | Asistencia rápida
        </button>
      </div>
    </div>
    <div class="offcanvas-footer p-3 border-top">
      <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
        <i class="fa-solid fa-arrow-right-to-bracket fa-lg" id="icono-menu"></i> | Cerrar sesión
      </button>
    </div>
  </div>

  <!-- Contenedor principal -->
  <div class="main-container d-flex">

    <!-- Panel izquierdo -->
    <div class="left-panel d-flex flex-column align-items-center">

<!-- Perfil del estudiante -->
<div class="card estudiante-card text-center mb-3">
  <img src="{{ asset($persona->estudiante->foto ?? 'img/FotoEstudiante.webp') }}" class="estudiante-avatar" alt="Avatar del estudiante" />
  <div class="card-body">
    <h5 class="card-title">
        {{ $persona->Nombre ?? '' }} {{ $persona->PrimerApellido ?? '' }} {{ $persona->SegundoApellido ?? '' }}
    </h5>
    <p class="card-text">
        {{ $persona->Cedula ?? '' }}
    </p>
  </div>
</div>


      <!-- Tipo de beca -->
      <div class="card tipo-beca-card text-center">
        <div class="card-header fw-bold">Tipo de beca asignada</div>
        <div class="card-body">
          <form action="{{ route('asistencia.guardar') }}" method="POST">
    @csrf
    <input type="hidden" name="estudiante_id" value="{{ $persona->estudiante->id }}">

    <div class="mb-3">
        <label for="becaSeleccionada" class="form-label fw-bold">Tipo de beca</label>
        <select id="becaSeleccionada" name="tipo_asistencia" class="form-select" required>
            <option value="">Seleccione una beca</option>
            @foreach($persona->estudiante->tipoBecas as $beca)
                <option value="{{ $beca->propiedade->nombre }}">
                    {{ $beca->propiedade->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="estadoAsistencia" class="form-label fw-bold">Marcar asistencia como:</label>
        <select id="estadoAsistencia" name="estado" class="form-select" required>
            <option value="">Seleccione una opción</option>
            <option value="presente">Presente</option>
            <option value="ausente">Ausente</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="fechaInicio" class="form-label fw-bold">Fecha de inicio</label>
        <input type="date" id="fechaInicio" name="fecha" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary fs-5 w-100">Modificar / Crear Asistencia</button>
</form>

        </div>
        
      </div>
      
    </div>

    <!-- Panel derecho -->
    <div class="right-panel card flex-grow-1">
      <div id="SegundoModulo" class="container-fluid">
        <!-- Selector de mes -->
                <div class="mb-4 form-group-horizontal">
                    <label for="mesSeleccionado" class="form-label fs-5 color1"><strong>Seleccionar mes para ver historial</strong></label>
                    <input type="month" id="mesSeleccionado" class="form-control" value="{{ date('Y-m') }}">
                </div>
                <!-- Tabla de asistencias -->
                <div class="container-fluid mb-4">
                    <h3 class="fw-bold mb-3">Historial de Asistencias</h3>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0" id="tablaAsistencias">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center">Seleccione una beca y un mes para ver las asistencias</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
      </div>
    </div>
  </div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const estudianteId = {{ $persona->estudiante->id }};
    const becaSelect = document.getElementById('becaSeleccionada');
    const mesSelect = document.getElementById('mesSeleccionado');
    const tbody = document.querySelector('#tablaAsistencias tbody');

    // Recuperar la beca guardada en localStorage
    const becaGuardada = localStorage.getItem('becaSeleccionada');
    if (becaGuardada) {
        becaSelect.value = becaGuardada;
    }

    // Función unificada para cargar asistencias
    async function cargarAsistencias() {
        const tipoBeca = becaSelect.value;
        const mes = mesSelect.value;

        if (!mes || !tipoBeca) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Seleccione una beca y un mes</td></tr>';
            return;
        }

        // Guardar la selección
        localStorage.setItem('becaSeleccionada', tipoBeca);

        try {
            const res = await fetch(`/estudiantes/${estudianteId}/asistencias?tipo_beca=${tipoBeca}&mes=${mes}`);
            const data = await res.json();

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay asistencias para este mes y beca</td></tr>';
                return;
            }

            tbody.innerHTML = data.map(a => `
                <tr>
                    <td>${a.fecha_hora}</td>
                    <td>${a.tipo_asistencia}</td>
                    <td>${a.estado}</td>
                    
                </tr>
            `).join('');
        } catch (err) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Error al cargar las asistencias</td></tr>';
            console.error(err);
        }
    }

    // Listeners
    becaSelect.addEventListener('change', cargarAsistencias);
    mesSelect.addEventListener('change', cargarAsistencias);

    // Cargar automáticamente si hay beca guardada y mes seleccionado
    if (becaGuardada && mesSelect.value) {
        cargarAsistencias();
    }
});

</script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/RevisarAsistencia.js"></script>
  <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>
</body>
</html>
