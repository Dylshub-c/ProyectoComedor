<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SICAB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="Stylesheet" href="{{ asset('css/IngresoComedor.css') }}">
    <link rel="Stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
</head>

<body id="fondo" class="d-flex flex-column min-vh-100">

    <!-- MENÚ LATERAL -->
    <button id="btn-Menu" class="btn ms-3 mb-3 fs-5 py-3" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
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
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas"
                    onclick="window.location.href='{{ route('admin.home') }}'">
                    <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu"></i> | Home
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i> | Ingreso al comedor
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas"
                    onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                    <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i> | Agregar usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas"
                    onclick="window.location='{{ route('estudiantes.informacion') }}'">
                    <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i> | Ver lista de usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas"
                    onclick="window.location='{{ route('Reportes.DescargarReporte') }}'">
                    <i class="fa-solid fa-download fa-lg" id="icono-menu"></i> | Descargar reportes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas"
                    onclick="window.location='{{ route('tipobeca.index') }}'">
                    <i class="fa-solid fa-hand-holding-medical fa-lg" id="icono-menu"></i> | Becas
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas"
                    onclick="window.location='{{ route('AsistenciaRapida.asistenciaRapida') }}'">
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

    <main class="flex-grow-1">
        <div class="container-fluid">

            <!-- MODAL DE ERROR -->
            @if(session('error'))
            <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="errorModalLabel">Error</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body fs-5 colorB">
                            {{ session('error') }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row justify-content-center mt-5">
                <div class="col-lg-6 col-md-12 col-sm-12 py-5 px-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-4">

                        <!-- LADO IZQUIERDO: Información del estudiante + botones -->
                        <div class="d-flex flex-column gap-4 cont1 mb-5" style="flex: 1;">
                            @if($persona && $persona->estudiante)
                            <form id="formAsistencia" method="POST" class="px-4 py-4" action="{{ route('asistencia.confirmar') }}">
                                @csrf
                                <input type="hidden" name="estudiante_id" value="{{ $persona->estudiante->id }}">

                                <div class="mb-3">
                                    <label for="tipoBecaSelect" class="form-label fs-5 colorB">Seleccionar tipo de beca:</label>
                                    <select class="form-select" id="tipoBecaSelect" name="tipo_beca" required>
                                        <option value="" selected>-- Seleccione una beca --</option>
                                        @foreach($todasLasBecas as $beca)
                                            <option value="{{ $beca->id }}">
                                                {{ $beca->propiedade->nombre ?? 'Sin nombre' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="PrimerModulo" class="text-center justify-content-center align-items-center card">
                                    @php
                                        $foto = $persona->estudiante->foto
                                            ? asset($persona->estudiante->foto)
                                            : asset('/img/FotoEstudiante.webp');
                                    @endphp

                                    <img class="img-fluid rounded-circle mb-3" id="fotoEstudiante" src="{{ $foto }}" alt="Foto del estudiante"
                                        style="width: 180px; height: 180px; object-fit: cover; border:4px solid #032B3F;">

                                    <label id="NomEstudiante" class="mt-1 text-center fs-4">
                                        <strong>{{ $persona->Nombre }} {{ $persona->PrimerApellido }} {{ $persona->SegundoApellido }}</strong>
                                    </label>

                                    <ul id="ul-Estudiante" class="list-group mt-4 mb-3 w-75">
                                        <li class="list-group-item">
                                            <strong class="fs-5">Cédula:</strong><br />
                                            <span id="cedula">{{ $persona->Cedula }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong class="fs-5">Especialidad:</strong><br />
                                            <span id="especialidad">{{ $persona->estudiante->especialidade?->propiedade?->nombre ?? '-' }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong class="fs-5">Becas del estudiante:</strong><br />
                                            <span id="tipo-beca">
                                                @forelse($persona->estudiante->tipoBecas as $beca)
                                                    {{ $beca->propiedade->nombre }}@if(!$loop->last), @endif
                                                @empty
                                                    -
                                                @endforelse
                                            </span>
                                        </li>
                                    </ul>

                                    <div class="d-flex gap-3 justify-content-center mt-3">
                                        <button type="button" class="btn btnObservaciones fs-5 flex-fill shadow" data-bs-toggle="modal" data-bs-target="#confirmModal">
                                            Confirmar asistencia
                                        </button>
                                        <button type="button" id="btnRechazar" class="btn btnRechazar fs-5 flex-fill shadow">
                                            Rechazar asistencia
                                        </button>
                                    </div>
                                </div>

                                {{-- Modal de confirmación --}}
                                <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmModalLabel">Confirmar asistencia</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body colorB fs-5">
                                                <p>
                                                    ¿Marcar <strong>presente</strong> al estudiante
                                                    para la beca seleccionada?
                                                </p>
                                                <p class="mb-2">
                                                    <small>En caso de que ya exista alguna marca se actualizará
                                                    y se registrará la presencia.</small>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btnRechazar shadow" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btnObservaciones shadow">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal de rechazo --}}
                                <div class="modal fade" id="rechazarModal" tabindex="-1" aria-labelledby="rechazarModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="rechazarModalLabel">Rechazar asistencia</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                      </div>
                                      <div class="modal-body fs-5 colorB">
                                        ¿Está seguro que desea rechazar la asistencia del estudiante?
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btnRechazar shadow" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" id="confirmarRechazo" class="btn btnObservaciones shadow">Confirmar</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                            </form>

                            {{-- Modal de éxito --}}
                            @if(session('success'))
                            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="successModalLabel">¡Éxito!</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body fs-5 colorB">
                                            {{ session('success') }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btnObservaciones" data-bs-dismiss="modal">Aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @else
                                <div class="fs-5 py-3 text-center align-items-center justify-content-center colorB">Aqui aparecerá la información del estudiante para realizar la asistencia</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 d-flex flex-column align-items-center px-5 py-5 text-center">

                    <!-- BLOQUE ESCANER -->
                    <div class="mb-4">
                        <div class="card shadow-lg" style="width: 600px; border-radius: 15px; background-color: #f7f7f7;">
                            <div class="card-header text-center" style="background-color: #032B3F; color: #f7f7f7; border-radius: 15px 15px 0 0;">
                                <h5 class="mb-0">Escanear código de barras</h5>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center">
                                <div id="reader" style="width:380px; height:380px; border:2px solid #032B3F; border-radius:12px;"></div>
                                <button id="btnIniciarScan" class="btn mt-3 fs-5 shadow">
                                    Iniciar cámara
                                </button>
                                <small id="scanStatus" class="text-muted mt-2"></small>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE BOTON CEDULA -->
                    <div>
                        <button type="button" id="finalizarAsistencia" class="btn mt-4 fs-5 shadow border" data-bs-toggle="modal" data-bs-target="#modalBuscar">
                            <i class="fa-regular fa-address-card fa-lg me-1" style="color: #f7f7f7;"></i>
                            | Realizar asistencia por cédula
                        </button>
                    </div>

                    <!-- Modal y formulario de búsqueda -->
                    <form id="formBuscar" action="{{ route('comedor.buscar') }}" method="GET">
                        @csrf
                        <div class="modal fade" id="modalBuscar" tabindex="-1" aria-labelledby="modalBuscarLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-center fs-5" id="modalBuscarLabel">Búsqueda por cédula</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="cedulaEstudiante" class="form-label fs-5 colorB">Ingrese la cédula completa del estudiante:</label>
                                            <input type="text" class="form-control" name="cedula" id="cedulaEstudiante" >
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-cancelar fs-5" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-aceptar fs-5">Realizar búsqueda</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer id="DivFooter" class="text-dark py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center text-center">
                    <p id="footerText" class="mb-0">Copyright ©2025 Especialidad Desarrollo Web | COVAO.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const asistenciasEstudiante = @json($asistencias ?? []);
    const formAsistencia = document.getElementById('formAsistencia');

    // -------------------------------
    // Rechazar estudiante con modal
    // -------------------------------
    const btnRechazar = document.getElementById('btnRechazar');
    if (btnRechazar) {
        btnRechazar.addEventListener('click', function () {
            const modalRechazoEl = document.getElementById('rechazarModal');
            const modalRechazo = new bootstrap.Modal(modalRechazoEl);
            modalRechazo.show();
        });
    }

    const confirmarRechazo = document.getElementById('confirmarRechazo');
    if (confirmarRechazo) {
        confirmarRechazo.addEventListener('click', function () {
            if (formAsistencia) formAsistencia.reset();
            const primerModulo = document.getElementById('PrimerModulo');
            if (primerModulo) primerModulo.innerHTML = '<p class="text-center fs-5">Estudiante rechazado. Por favor, busque otro estudiante.</p>';
            const modalRechazoEl = document.getElementById('rechazarModal');
            const modalRechazo = bootstrap.Modal.getInstance(modalRechazoEl);
            modalRechazo.hide();
        });
    }

    // -------------------------------
    // QR
    // -------------------------------
    const btnIniciarScan = document.getElementById('btnIniciarScan');
    const scanStatus = document.getElementById('scanStatus');
    let html5QrCode;
    let scanning = false;

    if (btnIniciarScan) {
        btnIniciarScan.addEventListener('click', async () => {
            if (scanning) return;
            scanning = true;
            scanStatus.innerText = "Buscando cámaras...";
            html5QrCode = new Html5Qrcode("reader");
            try {
                const cameras = await Html5Qrcode.getCameras();
                if (cameras && cameras.length) {
                    scanStatus.innerText = "Cámara iniciada, apuntar QR...";
                    const cameraId = cameras[0].id;
                    html5QrCode.start(cameraId, { fps: 10, qrbox: 250 },
                        (decodedText) => {
                            scanStatus.innerText = "Código detectado: " + decodedText;
                            const inputCedula = document.getElementById('cedulaEstudiante');
                            if (inputCedula) inputCedula.value = decodedText;
                            const formBuscar = document.getElementById('formBuscar');
                            if (formBuscar) formBuscar.submit();
                            html5QrCode.stop().then(() => scanning = false);
                        },
                        (errorMessage) => {}
                    );
                } else {
                    scanStatus.innerText = "No se encontraron cámaras.";
                    scanning = false;
                }
            } catch (err) {
                const errorModalEl = document.getElementById('errorModal');
                if (errorModalEl) {
                    const errorModal = new bootstrap.Modal(errorModalEl);
                    errorModal.show();
                }
                scanning = false;
            }
        });
    }

    // -------------------------------
    // Formulario buscar y localStorage
    // -------------------------------
    const formBuscar = document.getElementById('formBuscar');
    const selectTipoAsistencia = document.getElementById('TipoAsistencia');
    if (formBuscar) {
        formBuscar.addEventListener('submit', function () {
            if (selectTipoAsistencia) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'tipo_asistencia';
                input.value = selectTipoAsistencia.value;
                this.appendChild(input);
            }
            localStorage.removeItem("tipo_beca_seleccionada");
        });
    }
    if (selectTipoAsistencia) {
        const valorGuardado = localStorage.getItem('tipo_asistencia');
        if (valorGuardado) selectTipoAsistencia.value = valorGuardado;
        selectTipoAsistencia.addEventListener('change', () => {
            localStorage.setItem('tipo_asistencia', selectTipoAsistencia.value);
        });
    }

    const selectBeca = document.getElementById("tipoBecaSelect");
    if (selectBeca) {
        const becaGuardada = localStorage.getItem("tipo_beca_seleccionada");
        if (becaGuardada) selectBeca.value = becaGuardada;
        selectBeca.addEventListener("change", function () {
            localStorage.setItem("tipo_beca_seleccionada", this.value);
        });
    }

    // -------------------------------
    // Mostrar modales éxito y error
    // -------------------------------
    @if(session('success'))
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    @endif

    @if(session('error'))
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    @endif

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
