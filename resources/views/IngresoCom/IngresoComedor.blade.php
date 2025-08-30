<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.17/locales/es.global.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="Stylesheet" href="{{ asset('css/IngresoComedor.css') }}">
    <link rel="Stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />
    <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
    <title>Desarrollo Web</title>
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
        {{-- Mensajes de error --}}
        @if(session('error'))
            <div class="alert alert-danger fs-5">
                {{ session('error') }}
            </div>
        @endif

        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-10">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-4">

                    <!-- LADO IZQUIERDO: Información del estudiante + botones -->
                    <div class="d-flex flex-column gap-4" style="flex: 1;">
                        @if($persona && $persona->estudiante)
                        <form id="formAsistencia" method="POST" action="{{ route('asistencia.confirmar') }}">
                            @csrf
                            <input type="hidden" name="estudiante_id" value="{{ $persona->estudiante->id }}">

                            <div class="mb-3">
                                <label for="tipoBecaSelect" class="form-label fs-5">Seleccionar tipo de beca:</label>
                                <select class="form-select" id="tipoBecaSelect" name="tipo_beca" required>
                                    <option value="" selected>-- Seleccione una beca --</option>
                                    @foreach($todasLasBecas as $beca)
                                        <option value="{{ $beca->id }}">
                                            {{ $beca->propiedade->nombre ?? 'Sin nombre' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="PrimerModulo" class="text-center card shadow-lg p-3" style="border-radius: 15px; background-color: #f7f7f7;">
                                @php
                                    $foto = $persona->estudiante->foto
                                        ? asset($persona->estudiante->foto)
                                        : asset('/img/FotoEstudiante.webp');
                                @endphp

                                <img class="img-fluid rounded-circle mb-3" id="fotoEstudiante" src="{{ $foto }}" alt="Foto del estudiante"
                                     style="width: 180px; height: 180px; object-fit: cover; border:2px solid #032B3F;">

                                <label id="NomEstudiante" class="mt-1 text-center fs-4">
                                    <strong>{{ $persona->Nombre }} {{ $persona->PrimerApellido }} {{ $persona->SegundoApellido }}</strong>
                                </label>

                                <ul id="ul-Estudiante" class="list-group mt-4 mb-3">
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
                                    <button type="button" class="btn btn-success fs-5 flex-fill" style="border-radius: 8px;"
                                            data-bs-toggle="modal" data-bs-target="#confirmModal">
                                        Confirmar asistencia
                                    </button>
                                    <button type="button" class="btn btn-danger fs-5 flex-fill" style="border-radius: 8px;">
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
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                ¿Marcar <strong>presente</strong> al estudiante
                                                para la beca seleccionada?
                                            </p>
                                            <p class="mb-0">
                                                <small>Se eliminará la ausencia del día para ese tipo (si existe)
                                                y se registrará la presencia.</small>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- Modal de éxito --}}
                        {{-- Modal de éxito --}}
                        @if(session('success'))
                        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-success">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="successModalLabel">¡Éxito!</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ session('success') }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                successModal.show();
                            });
                        </script>
                        @endif

                        {{-- JS para select y modal éxito --}}
                        

                        @else
                            <div class="alert alert-info fs-5">Por favor, busque un estudiante por cédula para mostrar la información.</div>
                        @endif

                        <!-- Botón Buscar por cédula -->
                        <button type="button" id="finalizarAsistencia" class="btn mt-4 fs-5" data-bs-toggle="modal" data-bs-target="#modalBuscar">
                            <i class="fa-regular fa-address-card fa-lg me-1" style="color: #f7f7f7;"></i>
                            | Buscar por cédula
                        </button>

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
                                                <label for="cedulaEstudiante" class="form-label fs-5">Ingrese la cédula completa del estudiante:</label>
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

                    <!-- LADO DERECHO: Activar cámara -->
                    <div class="d-flex flex-column align-items-center mt-3" style="flex: 1;">
                        <div class="card shadow-lg" style="width: 320px; border-radius: 15px; background-color: #f7f7f7;">
                            <div class="card-header text-center" style="background-color: #032B3F; color: #f7f7f7; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                <h5 class="mb-0">Activar cámara</h5>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center">
                                <video id="video" width="280" height="280" style="border:2px solid #032B3F; border-radius: 12px;" autoplay muted></video>
                                <button id="btnActivarCamara" class="btn btn-primary mt-3 fs-5" style="background-color: #0A5386; border: none; border-radius: 8px; padding: 8px 20px;">
                                    Activar cámara
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
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
    <script>
        const asistenciasEstudiante = @json($asistencias ?? []);
    </script>
    <script>
    const form = document.getElementById('formAsistencia');
    const selectModal = document.getElementById('tipoBecaSelectModal');
    const tipoBecaId = document.getElementById('tipoBecaId');

    selectModal.addEventListener('change', () => {
        tipoBecaId.value = selectModal.value;
    });
</script>

    <script>
        document.getElementById('formBuscar').addEventListener('submit', function(e) {
            const select = document.getElementById('TipoAsistencia');

            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'tipo_asistencia';
            input.value = select.value;
            this.appendChild(input);
        });

        // Seleccionamos el dropdown
        const select = document.getElementById('TipoAsistencia');

        // Restaurar la selección anterior al cargar la página
        const valorGuardado = localStorage.getItem('tipo_asistencia');
        if (valorGuardado) {
            select.value = valorGuardado;
        }

        // Guardar la opción seleccionada cada vez que cambie
        select.addEventListener('change', () => {
            localStorage.setItem('tipo_asistencia', select.value);
        });

        // Activar cámara
        const btnCamara = document.getElementById('btnActivarCamara');
        const video = document.getElementById('video');

        btnCamara.addEventListener('click', async () => {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = stream;
                } catch (err) {
                    alert('No se pudo acceder a la cámara: ' + err);
                }
            } else {
                alert('Su navegador no soporta cámara.');
            }
        });
    </script>
    <script>
                            const selectBeca = document.getElementById("tipoBecaSelect");

                            // Cargar selección previa
                            const becaGuardada = localStorage.getItem("tipo_beca_seleccionada");
                            if (becaGuardada) {
                                selectBeca.value = becaGuardada;
                            }

                            // Guardar cuando cambie
                            selectBeca.addEventListener("change", function () {
                                localStorage.setItem("tipo_beca_seleccionada", this.value);
                            });

                            // Limpiar selección al buscar nuevo estudiante
                            const formBuscar = document.getElementById("formBuscar");
                            formBuscar.addEventListener("submit", function () {
                                localStorage.removeItem("tipo_beca_seleccionada");
                            });

                            // Abrir modal de éxito si existe
                            @if(session('success'))
                                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                successModal.show();
                            @endif
                        </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>

</body>

</html>
