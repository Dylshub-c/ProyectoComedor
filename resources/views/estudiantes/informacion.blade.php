<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Buscar Estudiante</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/InformacionEstudiante.css') }}">
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

    <!-- Búsqueda / Header -->
    <div class="container-fluid p-4 ps-5 pe-5 mb-4">
        <div class="row align-items-center contenedor g-3 mt-5 py-2 px-1">

            <!-- Buscar por cédula -->
            <div class="col-12 col-md-3 ms-5">
                <div class="input-icon-container d-flex align-items-center w-100">
                    <i class="fa-solid fa-address-card fa-2xl color1 me-2"></i>
                    <input type="text" name="cedula" class="input-line fs-5 form-control" placeholder="Buscar por cédula" value="{{ request('cedula') }}">
                </div>
            </div>

            <!-- Buscar por nombre -->
            <div class="col-12 col-md-3 ms-4">
                <div class="input-icon-container d-flex align-items-center w-100">
                    <i class="fa-solid fa-clipboard-user fa-2xl color1 me-2"></i>
                    <input type="text" name="nombre" class="input-line fs-5 form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
                </div>
            </div>

            <!-- Botón buscar -->
            <div class="col-6 col-md-2 ms-2">
                <form method="POST" action="{{ route('estudiantes.informacion') }}" role="search" class="d-flex h-100">
                    @csrf
                    <button type="submit" class="btn btnEditar fs-5 w-100">Buscar</button>
                </form>
            </div>

            <div class="col-1">

            </div>

            <!-- Logo -->
            <div class="col-6 col-md-2 text-center text-md-end">
                <img class="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo"/>
            </div>

        </div>
    </div>

    @if (session('success'))
      <div class="container-fluid px-5">
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert" id="alert-success">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
      </div>
    @endif

    @if($persona)
      @if(session('guardado'))
        <div class="container-fluid px-5">
          <div class="alert alert-success text-center">¡Cambios guardados correctamente!</div>
        </div>
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
                    <h1 class="fw-bold color4 mb-4 ps-3">
                        {{ $persona->Nombre }} {{ $persona->PrimerApellido }} {{ $persona->SegundoApellido }}
                    </h1>

                    <div class="mb-5 form-group-horizontal">
                        <label class="form-label form-label-fixed color1 fs-5"><strong>Cédula</strong></label>
                        <div class="form-input-flex inputColor fs-5" aria-readonly="true">{{ $persona->Cedula }}</div>
                    </div>

                    <div class="mb-5 form-group-horizontal">
                        <label class="form-label form-label-fixed color1 fs-5"><strong>Sección</strong></label>
                        <div class="form-input-flex inputColor fs-5" aria-readonly="true">{{ $persona->estudiante->seccione->propiedade->nombre ?? 'N/A' }}</div>
                    </div>

                    <div class="mb-5 form-group-horizontal">
                        <label class="form-label form-label-fixed color1 fs-5"><strong>Especialidad</strong></label>
                        <div class="form-input-flex inputColor fs-5" aria-readonly="true">{{ $persona->estudiante->especialidade->propiedade->nombre ?? 'N/A' }}</div>
                    </div>

                    <div class="mb-5 form-group-horizontal">
                        <label class="form-label form-label-fixed color1 fs-5"><strong>Tipo de beca</strong></label>
                        <div class="form-input-flex inputColor input-ancho-reducido fs-5 d-flex flex-wrap gap-2">
                            @if($persona->estudiante && $persona->estudiante->tipoBecas->count())
                                @foreach($persona->estudiante->tipoBecas as $beca)
                                    <span class="text-dark">{{ $beca->propiedade->nombre ?? 'Sin nombre' }}</span>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="mb-5 form-group-horizontal">
                        <div>
                            <button type="button" id="btnEditar" class="btnPrimario fs-5">
                                <i class="bi bi-pencil-fill"></i><strong> | Editar</strong>
                            </button>
                        </div>

                        <div class="text-center mt-2">
                          <a href="{{ route('asistencia.revisar', ['persona_id' => $persona->id]) }}" class="btnPrimario fs-5">
                              <i class="bi bi-person-badge-fill"></i><strong> | Revisar Asistencia</strong>
                          </a>
                        </div>

                        <form method="POST" action="{{ route('estudiantes.destroy', $persona->id) }}" id="formEliminar" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btnPrimario fs-5" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                <i class="bi bi-trash-fill"></i><strong> | Eliminar</strong>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- FORMULARIO EDICIÓN (contenido ocultable dentro misma columna) -->
                <div id="contenidoFormEditar" class="{{ $mostrarEditar ? '' : 'd-none' }}">
                    <form method="POST" action="{{ route('estudiantes.update', $persona->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="Nombre" class="form-label color1 fs-5"><strong>Nombre</strong></label>
                            <input type="text" id="Nombre" name="Nombre" class="form-control form-input-flex inputColor fs-5" value="{{ old('Nombre', $persona->Nombre) }}" required>
                            @error('Nombre')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="PrimerApellido" class="form-label color1 fs-5"><strong>Primer Apellido</strong></label>
                            <input type="text" id="PrimerApellido" name="PrimerApellido" class="form-control form-input-flex inputColor fs-5" value="{{ old('PrimerApellido', $persona->PrimerApellido) }}" required>
                            @error('PrimerApellido')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="SegundoApellido" class="form-label color1 fs-5"><strong>Segundo Apellido</strong></label>
                            <input type="text" id="SegundoApellido" name="SegundoApellido" class="form-control form-input-flex inputColor fs-5" value="{{ old('SegundoApellido', $persona->SegundoApellido) }}">
                            @error('SegundoApellido')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="Cedula" class="form-label color1 fs-5"><strong>Cédula</strong></label>
                            <input type="text" id="Cedula" name="Cedula" class="form-control form-input-flex inputColor fs-5" value="{{ old('Cedula', $persona->Cedula) }}" required>
                            @error('Cedula')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="especialidade_input" class="form-label color1 fs-5"><strong>Especialidad</strong></label>
                            <input type="text" id="especialidade_input" name="especialidade_input" class="form-control form-input-flex inputColor fs-5"
                                value="{{ $persona->estudiante->especialidade->propiedade->nombre ?? '' }}" required>
                            @error('especialidade_input')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="seccione_input" class="form-label color1 fs-5"><strong>Sección</strong></label>
                            <input type="text" id="seccione_input" name="seccione_input" class="form-control form-input-flex inputColor fs-5"
                                value="{{ $persona->estudiante->seccione->propiedade->nombre ?? '' }}" required>
                            @error('seccione_input')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-4 row align-items-start">
                            <label class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Tipo de beca</strong></label>
                            <div class="col-sm-9">
                                <div class="border rounded p-3" style="max-height: 220px; overflow-y: auto;">
                                    <div class="row">
                                        @foreach($tiposBeca as $index => $tipo)
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="tipo_beca_id[]" value="{{ $tipo->id }}" id="beca_{{ $tipo->id }}"
                                                        {{ $persona->estudiante->tipoBecas->contains($tipo->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="beca_{{ $tipo->id }}">
                                                        {{ $tipo->propiedade->nombre ?? 'Sin nombre' }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('tipo_beca_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label color1 fs-5"><strong>Foto</strong></label>
                            <input type="file" id="foto" name="foto" class="form-control inputColor fs-5" accept="image/*">
                            @error('foto')<small class="text-danger">{{ $message }}</small>@enderror
                            <small class="text-muted">Sube una nueva foto para actualizar (opcional)</small>
                        </div>

                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" class="btn btnPrimario btnAjustado color1 fs-5"><strong>Guardar Cambios</strong></button>
                            <button type="button" id="btnCancelar" class="btn btnPrimario btnAjustado color1 fs-5"><strong>Cancelar</strong></button>
                        </div>

                    </form>
                </div>

            </div> <!-- end col-md-8 -->

            <!-- imagen (derecha) -->
            <div class="col-md-4 text-center mb-5 align-self-start">
                <img src="{{ asset($persona->estudiante && $persona->estudiante->foto ? $persona->estudiante->foto : 'img/FotoEstudiante.webp') }}"
                     alt="Foto del estudiante"
                     class="foto-perfil-redonda">
            </div>

          </div> <!-- end row -->
        </div> <!-- end card -->
      </div> <!-- end container -->

      <!-- Modal eliminar (fuera del flujo principal para que no afecte layout) -->
      <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                  </div>
                  <div class="modal-body">
                      ¿Seguro que quieres eliminar este estudiante? Esta acción no se puede deshacer.
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btnPrimario btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                      <button type="button" class="btn btnPrimario btnCancelar" id="btnConfirmDelete">Eliminar</button>
                  </div>
              </div>
          </div>
      </div>

    @elseif(request()->hasAny(['nombre', 'cedula']))
      <div class="container mt-4 px-5">
        <div class="alert alert-warning alert-custom text-center">No se encontró ningún estudiante con los criterios indicados.</div>
      </div>
    @endif

  <!-- Scripts -->
<script>
    // Guardas referncias y protecciones por si no existen (evitar errores)
    const btnEditar = document.getElementById('btnEditar');
    const btnCancelar = document.getElementById('btnCancelar');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    const contenidoVista = document.getElementById('contenidoVistaDatos');
    const contenidoForm = document.getElementById('contenidoFormEditar');

    if (btnEditar) {
        btnEditar.addEventListener('click', function () {
            if (contenidoVista) contenidoVista.classList.add('d-none');
            if (contenidoForm) contenidoForm.classList.remove('d-none');
            const cardTop = document.querySelector('.card');
            if (cardTop) cardTop.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    if (btnCancelar) {
        btnCancelar.addEventListener('click', function () {
            if (contenidoForm) contenidoForm.classList.add('d-none');
            if (contenidoVista) contenidoVista.classList.remove('d-none');
            const cardTop = document.querySelector('.card');
            if (cardTop) cardTop.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', function () {
            const formEliminar = document.getElementById('formEliminar');
            if (formEliminar) formEliminar.submit();
        });
    }

    // auto-hide alerts (si existen)
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            // usar fade de bootstrap sería mejor, pero ocultamos para simplicidad
            alert.classList.remove('show');
            alert.classList.add('hide');
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>