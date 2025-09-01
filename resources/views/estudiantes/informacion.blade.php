<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>SICAB</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/InformacionEstudiante.css') }}">
        <link rel="Stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />
        <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
        <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
    </head>

<body class="d-flex flex-column min-vh-100">
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
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location.href='{{ route('admin.home') }}'">
                    <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu" ></i> | Home
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i> | Ingreso al comedor
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                    <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i> | Agregar usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.informacion') }}'">
                    <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i> | Ver lista de usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('Reportes.DescargarReporte') }}'">
                    <i class="fa-solid fa-download fa-lg" id="icono-menu"></i> | Descargar reportes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('tipobeca.index') }}'">
                    <i class="fa-solid fa-hand-holding-medical fa-lg" id="icono-menu"></i> | Becas
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('AsistenciaRapida.asistenciaRapida') }}'">
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

    <!-- Fondo fijo -->
    <div class="position-fixed top-0 start-0 w-100 h-100 z-n1" aria-hidden="true">
        <img src="{{ asset('img/FondoPrincipal.webp') }}" class="w-100 h-100" alt="Fondo">
    </div>

    <main class="flex-grow-1 mt-5">
        <!-- Búsqueda / Header -->
        <div class="container-fluid p-4 ps-5 pe-5 mb-4">
            <div class="row align-items-center contenedor g-3 mt-5 py-2 px-1">

                <form method="GET" action="{{ route('estudiantes.informacion') }}" class="row g-3 align-items-center w-100">

                    <!-- Buscar por cédula -->
                    <div class="col-3 ms-5">
                        <div class="input-icon-container d-flex align-items-center w-100">
                            <i class="fa-solid fa-address-card fa-2xl color1 me-2"></i>
                            <input type="text" name="cedula" class="input-line fs-5 form-control" placeholder="Buscar por cédula" value="{{ request('cedula') }}">
                        </div>
                    </div>

                    <!-- Buscar por nombre -->
                    <div class="col-3 ms-4">
                        <div class="input-icon-container d-flex align-items-center w-100">
                            <i class="fa-solid fa-clipboard-user fa-2xl color1 me-2"></i>
                            <input type="text" name="nombre" class="input-line fs-5 form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
                        </div>
                    </div>

                    <!-- Botón buscar -->
                    <div class="col-4 ms-2">
                        <button type="submit" class="btn btnEditar fs-5 w-50">Realizar busqueda</button>
                    </div>

                    <div class="col-1 mt-0">
                        <img class="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo"/>
                    </div>

                </form>
            </div>
        </div>

        <!-- MODALS DE MENSAJES (reemplazan alerts) -->
        @if (session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-success">
                    <div class="modal-header">
                        <h5 class="modal-title">¡Éxito!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        {{ session('success') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnCancelar" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($persona && session('guardado'))
        <div class="modal fade" id="guardadoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-success">
                    <div class="modal-header">
                        <h5 class="modal-title">¡Cambios guardados!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center">
                        ¡Cambios guardados correctamente!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnCancelar" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(request()->hasAny(['nombre', 'cedula']) && !$persona)
        <div class="modal fade" id="warningModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-warning">
                    <div class="modal-header">
                        <h5 class="modal-title">¡Atención!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center">
                        No se encontró ningún estudiante con los criterios indicados.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnCancelar" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($persona)
        @if(session('guardado'))
            {{-- Ya está manejado por modal guardadoModal --}}
        @endif

        <!-- Contenedor principal (permitimos crecer) -->
        <div class="container-fluid py-5 px-5">
            <div class="card rounded-4 shadow p-3 w-100">
            <div class="row g-3 align-items-start"> <!-- align-items-start para permitir crecimiento -->

                @php
                    $mostrarEditar = $editar || $errors->any();
                @endphp

                <!-- Columna izquierda (contendrá ambos: vista y formulario) -->
                <div class="col-md-8 p-2">

                    <!-- VISTA NORMAL (contenido) -->
                    <div id="contenidoVistaDatos" class="{{ $mostrarEditar ? 'd-none' : '' }}">
                        <h1 class="fw-bold color4 mb-3 ps-3">
                            {{ $persona->Nombre }} {{ $persona->PrimerApellido }} {{ $persona->SegundoApellido }}
                        </h1>

                        <div class="mb-4 form-group-horizontal">
                            <label for="CedulaV" class="form-label color1 fs-5"><strong>Cédula</strong></label>
                            <div type="text" id="CedulaV" name="CedulaV" class="form-control vista fs-5" aria-readonly="true">{{ $persona->Cedula }}</div>
                        </div>

                        <div class="mb-4 form-group-horizontal">
                            <label for="SeccionV" class="form-label color1 fs-5"><strong>Sección</strong></label>
                            <div type="text" id="SeccionV" name="SeccionV" class="form-control vista fs-5" aria-readonly="true">{{ $persona->estudiante->seccione->propiedade->nombre ?? 'N/A' }}</div>
                        </div>

                        <div class="mb-4 form-group-horizontal">
                            <label for="EspecialidadV" class="form-label color1 fs-5"><strong>Especialidad</strong></label>
                            <div type="text" id="EspecialidadV" name="EspecialidadV" class="form-control vista fs-5" aria-readonly="true">{{ $persona->estudiante->especialidade->propiedade->nombre ?? 'N/A' }}</div>
                        </div>

                        <div class="mb-5 form-group-horizontal w-50">
                            <label class="form-label color1 fs-5"><strong>Tipo de beca</strong></label>
                            <div class="form-control vista fs-5 d-flex flex-wrap gap-2">
                                @if($persona->estudiante && $persona->estudiante->tipoBecas->count())
                                    @foreach($persona->estudiante->tipoBecas as $beca)
                                        <span class="text-dark">{{ $beca->propiedade->nombre ?? 'Sin nombre' }}</span><span>|</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 form-group-horizontal d-flex">
                            <div>
                                <button type="button" id="btnEditar" class="btn btnCrearRol shadow fs-5">
                                    <i class="bi bi-pencil-fill"></i><strong> | Editar</strong>
                                </button>
                            </div>

                            <div class="text-center">
                            <a href="{{ route('asistencia.revisar', ['persona_id' => $persona->id]) }}" class="btn btnCrearRol shadow fs-5">
                                <i class="bi bi-person-badge-fill"></i><strong> | Revisar Asistencia</strong>
                            </a>
                            </div>

                            <form method="POST" action="{{ route('estudiantes.destroy', $persona->id) }}" id="formEliminar">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btnEliminar shadow fs-5" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    <i class="bi bi-trash-fill"></i><strong> | Eliminar</strong>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- FORMULARIO EDICIÓN -->
                    <div id="contenidoFormEditar" class="{{ $mostrarEditar ? '' : 'd-none' }}">
                        <form method="POST" action="{{ route('estudiantes.update', $persona->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="Nombre" class="form-label color1 fs-5"><strong>Nombre</strong></label>
                                <input type="text" id="Nombre" name="Nombre" class="form-control editor fs-5" value="{{ old('Nombre', $persona->Nombre) }}" required>
                                @error('Nombre')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="PrimerApellido" class="form-label color1 fs-5"><strong>Primer Apellido</strong></label>
                                <input type="text" id="PrimerApellido" name="PrimerApellido" class="form-control editor fs-5" value="{{ old('PrimerApellido', $persona->PrimerApellido) }}" required>
                                @error('PrimerApellido')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="SegundoApellido" class="form-label color1 fs-5"><strong>Segundo Apellido</strong></label>
                                <input type="text" id="SegundoApellido" name="SegundoApellido" class="form-control editor fs-5" value="{{ old('SegundoApellido', $persona->SegundoApellido) }}">
                                @error('SegundoApellido')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="Cedula" class="form-label color1 fs-5"><strong>Cédula</strong></label>
                                <input type="text" id="Cedula" name="Cedula" class="form-control editor fs-5" value="{{ old('Cedula', $persona->Cedula) }}" required>
                                @error('Cedula')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="especialidade_input" class="form-label color1 fs-5"><strong>Especialidad</strong></label>
                                <input type="text" id="especialidade_input" name="especialidade_input" class="form-control editor fs-5"
                                    value="{{ $persona->estudiante->especialidade->propiedade->nombre ?? '' }}" required>
                                @error('especialidade_input')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="seccione_input" class="form-label color1 fs-5"><strong>Sección</strong></label>
                                <input type="text" id="seccione_input" name="seccione_input" class="form-control editor fs-5"
                                    value="{{ $persona->estudiante->seccione->propiedade->nombre ?? '' }}" required>
                                @error('seccione_input')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-4 row align-items-start text-start d-block">
                                <label class="col-sm-3 col-form-label text-start color1 fs-5 mt-2"><strong>Tipo de beca</strong></label>
                                <div class="borde rounded p-3 w-75 ms-3" style="max-height: 220px; overflow-y: auto;">
                                    <div class="row">
                                        @foreach($tiposBeca as $index => $tipo)
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input fs-5" type="checkbox" name="tipo_beca_id[]" value="{{ $tipo->id }}" id="beca_{{ $tipo->id }}" {{ $persona->estudiante->tipoBecas->contains($tipo->id) ? 'checked' : '' }}>
                                                <label class="form-check-label fs-5" for="beca_{{ $tipo->id }}">
                                                    {{ $tipo->propiedade->nombre ?? '' }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label color1 fs-5"><strong>Foto</strong></label>
                                <input type="file" id="foto" name="foto" class="form-control editor fs-5" accept="image/*">
                                @error('foto')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="d-flex gap-2 justify-content-start mb-3">
                                <button type="submit" class="btn btnEliminar shadow fs-5">Guardar cambios</button>
                                <button type="button" id="btnCancelar" class="btn btnCancelar shadow fs-5">Cancelar</button>
                            </div>
                        </form>
                    </div>

                </div> <!-- end col-md-8 -->

                <!-- imagen (derecha) -->
                <div class="col-md-4 text-center mt-5 align-self-start">
                    <img src="{{ asset($persona->estudiante && $persona->estudiante->foto ? $persona->estudiante->foto : 'img/FotoEstudiante.webp') }}"
                        alt="Foto del estudiante"
                        class="foto-perfil-redonda mt-5 ">
                </div>

            </div> <!-- end row -->
            </div> <!-- end card -->
        </div> <!-- end container -->

        <!-- Modal eliminar -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        ¿Seguro que quieres eliminar este estudiante? Esta acción no se puede deshacer.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnCancelar fs-5" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btnEliminar fs-5" id="btnConfirmDelete">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        @elseif(request()->hasAny(['nombre', 'cedula']))
        <!-- Antes: alert; ahora modal se muestra si no hay $persona -->
        {{-- handled above by warningModal --}}
        @endif
    </main>

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
<script>
    const btnEditar = document.getElementById('btnEditar');
    const btnCancelar = document.getElementById('btnCancelar');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    const contenidoVista = document.getElementById('contenidoVistaDatos');
    const contenidoForm = document.getElementById('contenidoFormEditar');

    if (btnEditar) {
        btnEditar.addEventListener('click', function () {
            if (contenidoVista) contenidoVista.classList.add('d-none');
            if (contenidoForm) contenidoForm.classList.remove('d-none');
            document.querySelector('.card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function () {
            if (contenidoForm) contenidoForm.classList.add('d-none');
            if (contenidoVista) contenidoVista.classList.remove('d-none');
            document.querySelector('.card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', function () {
            document.getElementById('formEliminar').submit();
        });
    }

    // Abrir modals automáticamente si existen (success / guardado / warning)
    document.addEventListener('DOMContentLoaded', () => {
        const successModalEl = document.getElementById('successModal');
        if (successModalEl) {
            try { new bootstrap.Modal(successModalEl).show(); } catch(e) {}
        }

        const guardadoModalEl = document.getElementById('guardadoModal');
        if (guardadoModalEl) {
            try { new bootstrap.Modal(guardadoModalEl).show(); } catch(e) {}
        }

        const warningModalEl = document.getElementById('warningModal');
        if (warningModalEl) {
            try { new bootstrap.Modal(warningModalEl).show(); } catch(e) {}
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>