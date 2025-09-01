<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SICAB</title>

    <!-- Bootstrap desde CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/EstiloRevisarAs.css') }}">
    <link rel="Stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />
    <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Menú lateral (sin cambios) -->
    <button id="btn-Menu" class="btn ms-3 mb-3 fs-5 py-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <i class="fa-solid fa-bars fa-xl" style="color: #f7f7f7;"></i>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header justify-content-end">
            <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close"> <i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i> </button>
        </div>
        <div class="offcanvas-body">
            <div class="d-grid gap-3">
                <!-- Botones del menú -->
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location.href='{{ route('admin.home') }}'">
                    <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu" ></i> | Home
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('IngresoCom.IngresoComedor') }}'">
                    <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i> | Ingreso al comedor
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                    <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i> | Agregar usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.informacion') }}'">
                    <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i> | Ver lista de usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-download fa-lg" id="icono-menu"></i> | Descargar reportes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-calendar-check fa-lg" id="icono-menu"></i> | Gestionar asistencias
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('tipobeca.index') }}'">
                    <i class="fa-solid fa-hand-holding-medical fa-lg" id="icono-menu"></i> | Becas
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-star-half-stroke fa-lg" id="icono-menu"></i> | Asistencia rápida
                </button>
            </div>
        </div>
        <div class="offcanvas-footer p-3 border-top">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                 <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                <i class="fa-solid fa-arrow-right-to-bracket fa-lg" id="icono-menu"></i> | Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <!-- Contenedor principal -->
    <main class="d-flex flex-grow-1 mt-5 py-5 px-5">

        <!-- Panel izquierdo -->
        <div class="left-panel d-flex flex-column align-items-center">

            <!-- Perfil del estudiante -->
            <div class="card estudiante-card text-center mb-3">
                <img src="{{ optional($persona->estudiante)->foto
                        ? asset('storage/' . optional($persona->estudiante)->foto)
                        : asset('img/FotoEstudiante.webp') }}"
                        alt="Foto del estudiante"
                        class="foto-perfil-redonda"
                    />
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
                <div class="card-header fw-bold">Cree o modifique asistencias</div>
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
                            <label for="fechaInicio" class="form-label fw-bold">Fecha de la asistencia</label>
                            <input type="date" id="fechaInicio" name="fecha" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btnEditar fs-5 w-100">Modificar / Crear Asistencia</button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Panel derecho -->
        <div class="right-panel card flex-grow-1">
            <div id="SegundoModulo" class="container-fluid py-3 px-4">
                <!-- Selector de mes -->
                <div class="mb-4 form-group-horizontal">
                    <label for="mesSeleccionado" class="form-label fs-3 color1"><strong>Seleccionar mes para ver historial</strong></label>
                    <input type="month" id="mesSeleccionado" class="form-control" value="{{ date('Y-m') }}">
                </div>
                <!-- Tabla de asistencias -->
                <div class="container-fluid mb-4">
                    <h3 class="fw-bold mb-3">Historial de Asistencias</h3>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0 text-center" id="tablaAsistencias">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
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
    </main>

    <!-- Modal de Observación -->
    <div class="modal fade" id="modalObservacion" tabindex="-1" aria-labelledby="modalObservacionLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Observaciones</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="formObservacion">
              @csrf
              <input type="hidden" id="listadoId" name="id">
              <textarea id="textoObservacion" name="observaciones" class="form-control" rows="4"></textarea>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" form="formObservacion" class="btn btnEditar">Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de resultado -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <p id="resultMessage" class="fs-5 mb-0 py-2">
                        @if(session('success'))
                            Acción realizada con éxito
                        @endif
                    </p>
                    <button type="button" class="btn btnEditar mt-3" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer id="DivFooter" class="text-dark py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center text-center align-items-center">
                    <p id="footerText" class="mb-0">Copyright ©2025 Especialidad Desarrollo Web | COVAO.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const estudianteId = {{ $persona->estudiante->id }};
    const becaSelect = document.getElementById('becaSeleccionada');
    const mesSelect = document.getElementById('mesSeleccionado');
    const tbody = document.querySelector('#tablaAsistencias tbody');

    // Restaurar selección de beca
    const becaGuardada = localStorage.getItem('becaSeleccionada');
    if (becaGuardada) becaSelect.value = becaGuardada;

    // Función para parsear fechas dd/mm/yyyy
    function parseDMY(dateStr) {
        if (!dateStr) return new Date(NaN);
        const datePart = dateStr.split(' ')[0];
        const parts = datePart.split('/');
        if (parts.length !== 3) return new Date(dateStr);
        return new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
    }

    // Función para cargar asistencias
    async function cargarAsistencias() {
        const tipoBeca = becaSelect.value;
        const mes = mesSelect.value;
        if (!mes || !tipoBeca) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Seleccione una beca y un mes</td></tr>';
            return;
        }
        localStorage.setItem('becaSeleccionada', tipoBeca);

        try {
            const res = await fetch(`/estudiantes/${estudianteId}/asistencias?tipo_beca=${encodeURIComponent(tipoBeca)}&mes=${encodeURIComponent(mes)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const data = await res.json();
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay asistencias para este mes y beca</td></tr>';
                return;
            }

            // Ordenar por fecha
            data.sort((a, b) => parseDMY(a.fecha_hora) - parseDMY(b.fecha_hora));

            // Renderizar filas
            tbody.innerHTML = data.map(a => `
                <tr>
                    <td>${a.fecha_hora}</td>
                    <td>${a.tipo_asistencia}</td>
                    <td>${a.estado}</td>
                    <td>
                        <button class="btn btnEditar btn-sm editar-observacion"
                            data-id="${a.listado_id || ''}"
                            data-observacion="${a.observaciones || ''}"
                            data-bs-toggle="modal"
                            data-bs-target="#modalObservacion">
                            Observaciones
                        </button>
                    </td>
                </tr>
            `).join('');

            // Listener para abrir modal
            document.querySelectorAll('.editar-observacion').forEach(boton => {
                boton.addEventListener('click', function() {
                    const listadoId = this.dataset.id;
                    document.getElementById('listadoId').value = listadoId || '';
                    document.getElementById('textoObservacion').value = this.dataset.observacion || '';
                });
            });

        } catch (err) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Error al cargar las asistencias</td></tr>';
            console.error('Error al cargar asistencias:', err);
        }
    }

    // Eventos de cambio
    becaSelect.addEventListener('change', cargarAsistencias);
    mesSelect.addEventListener('change', cargarAsistencias);
    if (becaGuardada && mesSelect.value) cargarAsistencias();

    // Mostrar modal de resultado si hay éxito
    @if(session('success'))
        new bootstrap.Modal(document.getElementById('resultModal')).show();
    @endif

    // Guardar observación vía AJAX
    const formObs = document.getElementById('formObservacion');
    formObs.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('listadoId').value;
        const observacion = document.getElementById('textoObservacion').value;

        if (!id) {
            alert('ID de asistencia no válido');
            return;
        }

        try {
            const res = await fetch(`/asistencia/${id}/observacion`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ observaciones: observacion })
            });

            if (!res.ok) {
                const text = await res.text();
                console.error('Error al guardar observación:', res.status, text);
                alert('Error al guardar la observación (revisa la consola)');
                return;
            }

            const json = await res.json();
            console.log('Observación actualizada:', json);
            alert('Observación guardada ✅');
            bootstrap.Modal.getInstance(document.getElementById('modalObservacion')).hide();
            cargarAsistencias();

        } catch(err) {
            console.error('Error de conexión al guardar observación:', err);
            alert('Error al guardar la observación (ver consola)');
        }
    });
});
</script>
</body>
</html>
