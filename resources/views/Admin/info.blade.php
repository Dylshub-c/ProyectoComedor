<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Información Encargado</title>
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
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location.href='{{ route('admin.home') }}'">
                    <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu" ></i>
                    | Home
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('IngresoCom.IngresoComedor') }}'">
                    <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i>
                    | Ingreso al comedor
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                    <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i>
                    | Agregar estudiantes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.informacion') }}'">
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
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('tipobeca.index') }}'">
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
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                 <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                <i class="fa-solid fa-arrow-right-to-bracket fa-lg" id="icono-menu"></i>
                | Cerrar sesión
                </button>
            </form>

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

            <form method="POST" action="{{ route('encargados.informacion') }}" class="d-flex gap-3 w-100" role="search">
                @csrf
                <div class="input-icon-container d-flex align-items-center flex-grow-1">
                    <i class="fa-solid fa-address-card fa-2xl color1 me-2"></i>
                    <input type="text" name="cedula" class="input-line fs-5 form-control" placeholder="Buscar por cédula" value="{{ request('cedula') }}">
                </div>
                <div class="input-separator d-none d-md-block mx-2"></div>
                <div class="input-icon-container d-flex align-items-center flex-grow-1">
                    <i class="fa-solid fa-clipboard-user fa-2xl color1 me-2"></i>
                    <input type="text" name="nombre" class="input-line fs-5 form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
                </div>
                <button type="submit" class="btnPrimario fs-5 ms-2">Buscar</button>
            </form>
            <div class="ms-2 d-flex align-items-center">
                <a href="{{ route('encargados.create') }}" class="btnPrimario fs-5" style="width: 250px; text-decoration: none;">
                    <i class="fa-solid fa-user-plus me-2"></i> Crear encargado
                </a>
            </div>

            <!--Logo-->
            <div class="ms-md-auto text-center text-md-end">
                <img class="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo"/>
            </div>

        </div>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show mt-3" role="alert" id="alert-success">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
</div>
@endif

@if($encargado)
@php
    $mostrarEditar = $editar ?? false || $errors->any();
@endphp
<div class="container-fluid h-100 overflow-hidden d-flex justify-content-center align-items-center p-5 pt-0 mb-4">
    <div class="card rounded-4 shadow p-3 w-100">
        <div class="row g-3 align-items-center">

            <!-- Vista Normal -->
            <div class="col-md-8 p-2" id="vistaDatos" style="{{ $mostrarEditar ? 'display:none;' : '' }}">
                <h1 class="fw-bold color4 mb-4 ps-3">
                    {{ $encargado->persona->Nombre }} {{ $encargado->persona->PrimerApellido }} {{ $encargado->persona->SegundoApellido }}
                </h1>

                <div class="mb-5 form-group-horizontal">
                    <label class="form-label form-label-fixed color1 fs-5"><strong>Cédula</strong></label>
                    <div class="form-input-flex inputColor fs-5" aria-readonly="true">{{ $encargado->persona->Cedula }}</div>
                </div>

                <div class="mb-5 form-group-horizontal">
                    <label class="form-label form-label-fixed color1 fs-5"><strong>Correo</strong></label>
                    <div class="form-input-flex inputColor fs-5" aria-readonly="true">{{ $encargado->correo }}</div>
                </div>

                <div class="mb-5 form-group-horizontal">
                    <div>
                        <button type="button" id="btnEditar" class="btnPrimario fs-5">
                            <i class="bi bi-pencil-fill"></i><strong> | Editar</strong>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('encargados.destroy', $encargado->id) }}" id="formEliminar">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btnPrimario fs-5" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                            <i class="bi bi-trash-fill"></i><strong> | Eliminar</strong>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal Confirmar eliminación -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            ¿Seguro que quieres eliminar este encargado? Esta acción no se puede deshacer.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" id="btnConfirmDelete">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario Edición oculto -->
            <div class="col-md-8 p-2" id="formEditar" style="{{ $mostrarEditar ? '' : 'display:none;' }}">
                <form method="POST" action="{{ route('encargados.update', $encargado->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="Nombre" class="form-label">Nombre</label>
                        <input type="text" id="Nombre" name="Nombre" class="form-control" value="{{ old('Nombre', $encargado->persona->Nombre) }}" required>
                        @error('Nombre')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="PrimerApellido" class="form-label">Primer Apellido</label>
                        <input type="text" id="PrimerApellido" name="PrimerApellido" class="form-control" value="{{ old('PrimerApellido', $encargado->persona->PrimerApellido) }}" required>
                        @error('PrimerApellido')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="SegundoApellido" class="form-label">Segundo Apellido</label>
                        <input type="text" id="SegundoApellido" name="SegundoApellido" class="form-control" value="{{ old('SegundoApellido', $encargado->persona->SegundoApellido) }}">
                        @error('SegundoApellido')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="Cedula" class="form-label">Cédula</label>
                        <input type="text" id="Cedula" name="Cedula" class="form-control" value="{{ old('Cedula', $encargado->persona->Cedula) }}" required>
                        @error('Cedula')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" id="correo" name="correo" class="form-control" value="{{ old('correo', $encargado->correo) }}" required>
                        @error('correo')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                    <button type="button" id="btnCancelar" class="btn btn-secondary w-100 mt-2">Cancelar</button>
                </form>
            </div>

        </div>
    </div>
</div>

@elseif(request()->hasAny(['nombre', 'cedula']))
<!-- Mensaje si no encontró resultado -->
<div class="container mt-4">
    <div class="alert alert-warning text-center">
        No se encontró ningún encargado con los criterios indicados.
    </div>
</div>
@endif

<!-- Scripts -->
<script>
    document.getElementById('btnEditar').addEventListener('click', function () {
        document.getElementById('vistaDatos').style.display = 'none';
        document.getElementById('formEditar').style.display = 'block';
    });
    document.getElementById('btnCancelar').addEventListener('click', function () {
        document.getElementById('formEditar').style.display = 'none';
        document.getElementById('vistaDatos').style.display = 'block';
    });

    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 3000);

    document.getElementById('btnConfirmDelete').addEventListener('click', function () {
        document.getElementById('formEliminar').submit();
    });

    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
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
