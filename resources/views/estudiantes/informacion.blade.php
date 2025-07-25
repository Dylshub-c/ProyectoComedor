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
            <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close"> <i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i> </button>
        </div>
        <div class="offcanvas-body">
            <div class="d-grid gap-3">
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i>
                    | Ingreso al comedor
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                    <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i>
                    | Agregar estudiantes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i>
                    | Ver lista de estudiantes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-download fa-lg" id="icono-menu"></i>
                    | Descargar reportes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-calendar-check fa-lg" id="icono-menu"></i>
                    | Gestionar asistencias
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-hand-holding-medical fa-lg" id="icono-menu"></i>
                    | Becas
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-star-half-stroke fa-lg" id="icono-menu"></i>
                    | Asistencia rápida
                </button>

            </div>
        </div>
        <div class="offcanvas-footer p-3 border-top">
            <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                <i class="fa-solid fa-arrow-right-to-bracket fa-lg" id="icono-menu"></i>
                | Cerrar sesión
            </button>
            </div>
        </div>

    <!-- Fondo fijo -->
    <div class="position-fixed top-0 start-0 w-100 h-100 z-n1">
        <img src="{{ asset('img/FondoPrincipal.webp') }}" class="w-100 h-100" alt="Fondo">
    </div>
    <div class="container-fluid p-4 ps-5 pe-5 mb-4">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3">

            <!-- Contenedor principal -->
            <div class="header d-flex flex-column flex-md-row align-items-stretch align-items-md-center flex-grow-1 gap-3 shadow-sm">

                <form method="POST" action="{{ route('estudiantes.informacion') }}" class="d-flex gap-3 w-100" role="search">
                    @csrf
                    <!-- Buscar por cédula -->
                    <div class="input-icon-container d-flex align-items-center flex-grow-1">
                        <i class="fa-solid fa-address-card fa-2xl color1 me-2"></i>
                        <input type="text" name="cedula" class="input-line fs-5 form-control" placeholder="Buscar por cédula" value="{{ request('cedula') }}">
                    </div>

                    <!-- Separador -->
                    <div class="input-separator d-none d-md-block mx-2"></div>

                    <!-- Buscar por nombre -->
                    <div class="input-icon-container d-flex align-items-center flex-grow-1">
                        <i class="fa-solid fa-clipboard-user fa-2xl color1 me-2"></i>
                        <input type="text" name="nombre" class="input-line fs-5 form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
                    </div>

                    <button type="submit" class="btn btn-primary fs-5 ms-2">Buscar</button>
                </form>

                <!--Logo-->
                <div class="ms-md-auto text-center text-md-end">
                    <img class="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo"/>
                </div>

            </div>
        </div>
    </div>

    @if($persona)
    <div class="container-fluid h-100 overflow-hidden d-flex justify-content-center align-items-center p-5 pt-0 mb-4">
        <div class="card rounded-4 shadow p-3 w-100">
            <div class="row g-3 align-items-center">

                <!-- Columna de texto: VISTA NORMAL -->
                <div class="col-md-8 p-2" id="vistaDatos">
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
                        <div class="form-input-flex inputColor input-ancho-reducido fs-5" aria-readonly="true">{{ $persona->estudiante->tipoBeca->propiedade->nombre ?? 'N/A' }}</div>
                    </div>

                    <div class="mb-5 form-group-horizontal">
                        <div>
                            <button type="button" id="btnEditar" class="btnPrimario fs-5">
                                <i class="bi bi-pencil-fill"></i><strong> | Editar</strong>
                            </button>
                        </div>
                        <div class="text-center mt-2">
                            <button type="button" class="btnPrimario fs-5">
                                <i class="bi bi-person-badge-fill"></i><strong> | Revisar Asistencia</strong>
                            </button>
                        </div>
                        <div class="text-center mt-2">
                            <form method="POST" action="{{ route('estudiantes.destroy', $persona->id) }}" onsubmit="return confirm('¿Seguro que quieres eliminar este estudiante?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btnPrimario fs-5">
                                    <i class="bi bi-trash-fill"></i><strong> | Eliminar</strong>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            <!-- FORMULARIO EDICIÓN OCULTO -->
            <div class="col-md-8 p-2" id="formEditar" style="display:none;">
                <form method="POST" action="{{ route('estudiantes.update', $persona->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="Nombre" class="form-label">Nombre</label>
                        <input type="text" id="Nombre" name="Nombre" class="form-control" value="{{ old('Nombre', $persona->Nombre) }}" required>
                        @error('Nombre')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="PrimerApellido" class="form-label">Primer Apellido</label>
                        <input type="text" id="PrimerApellido" name="PrimerApellido" class="form-control" value="{{ old('PrimerApellido', $persona->PrimerApellido) }}" required>
                        @error('PrimerApellido')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="SegundoApellido" class="form-label">Segundo Apellido</label>
                        <input type="text" id="SegundoApellido" name="SegundoApellido" class="form-control" value="{{ old('SegundoApellido', $persona->SegundoApellido) }}">
                        @error('SegundoApellido')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="Cedula" class="form-label">Cédula</label>
                        <input type="text" id="Cedula" name="Cedula" class="form-control" value="{{ old('Cedula', $persona->Cedula) }}" required>
                        @error('Cedula')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="especialidade_input" class="form-label">Especialidad</label>
                        <input type="text" id="especialidade_input" name="especialidade_input" class="form-control"
                            value="{{ $persona->estudiante->especialidade->propiedade->nombre ?? '' }}" required>
                        <small class="text-muted">Escribe una nueva especialidad o deja la que aparece</small>
                        @error('especialidade_input')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="seccione_input" class="form-label">Sección</label>
                        <input type="text" id="seccione_input" name="seccione_input" class="form-control"
                            value="{{ $persona->estudiante->seccione->propiedade->nombre ?? '' }}" required>
                        <small class="text-muted">Escribe una nueva sección o deja la que aparece</small>
                        @error('seccione_input')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="tipo_beca_id" class="form-label">Tipo de beca</label>
                        <select id="tipo_beca_id" name="tipo_beca_id" class="form-select" required>
                            <option value="">Seleccione un tipo de beca</option>
                            @foreach($tiposBeca as $tipo)
                                <option value="{{ $tipo->id }}"
                                    {{ $persona->estudiante->tipo_beca_id == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->propiedade->nombre ?? 'Sin nombre' }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_beca_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                        @error('foto')<small class="text-danger">{{ $message }}</small>@enderror
                        <small class="text-muted">Sube una nueva foto para actualizar (opcional)</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                    <button type="button" id="btnCancelar" class="btn btn-secondary w-100 mt-2">Cancelar</button>
                </form>
            </div>

            <!-- imagen -->
            <div class="col-md-4 text-center mb-5">
                <img src="{{ asset($persona->estudiante->foto ?? '../img/FotoEstudiante.webp') }}" alt="Foto del estudiante" class="foto-perfil rounded">
            </div>

        </div>
    </div>
    @elseif(request()->hasAny(['nombre', 'cedula']))
    <!-- Mensaje si no encontró resultado -->
    <div class="container mt-4">
        <div class="alert alert-warning text-center">
            No se encontró ningún estudiante con los criterios indicados.
        </div>
    </div>
    @endif

  <!-- Scripts -->
    <script>
    const btnEditar = document.getElementById('btnEditar');
    const vistaDatos = document.getElementById('vistaDatos');
    const formEditar = document.getElementById('formEditar');
    const btnCancelar = document.getElementById('btnCancelar');

    btnEditar.addEventListener('click', () => {
        vistaDatos.style.display = 'none';
        formEditar.style.display = 'block';
    });

    btnCancelar.addEventListener('click', () => {
        formEditar.style.display = 'none';
        vistaDatos.style.display = 'block';
    });


</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>
