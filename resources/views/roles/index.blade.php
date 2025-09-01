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
    <!-- BOTÓN MENÚ -->
    <button id="btn-Menu" class="btn ms-3 mb-3 fs-5 py-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <i class="fa-solid fa-bars fa-xl" style="color: #f7f7f7;"></i>
    </button>

    <!-- MENÚ LATERAL -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header justify-content-end">
            <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="d-grid gap-3">
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location.href='{{ route('admin.home') }}'">
                    <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu" ></i>
                    | Home
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location.href='{{ route('IngresoCom.IngresoComedor') }}'">
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
                 <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('roles.index') }}'">
                   <i class="fa-solid fa-user-shield fa-lg" id="icono-menu"></i> | Gestionar roles
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

    <main class="flex-grow-1 mt-5">

        <div class="container-fluid p-4 ps-5 pe-5 mb-4 mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold color4">Gestión de Roles</h1>
                <a href="{{ route('roles.create') }}" class="btn btnCrearRol fs-5">
                    <i class="bi bi-plus-circle"></i> Crear nuevo rol
                </a>
            </div>

            <!-- MODAL DE ÉXITO -->
            @if(session('success'))
                <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalExitoLabel">
                                    <i class="bi bi-check-circle-fill me-2"></i> Operación exitosa
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body fs-5">
                                {{ session('success') }}
                            </div>
                            <div class="modal-footer align-items-center justify-content-center text-center">
                                <button type="button" class="btn btnCrearRol" data-bs-dismiss="modal">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
                        modalExito.show();
                    });
                </script>
            @endif

            <div class="card rounded-4 shadow-lg p-3">
                <table class="table table-striped align-middle mb-0">
                    <thead class="fs-5">
                        <tr>
                            <th>Rol</th>
                            <th>Permisos</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $rol)
                        <tr>
                            <td class="fs-5">{{ $rol->name }}</td>
                            <td>
                                @foreach ($rol->permissions as $permiso)
                                    <span class="badge roles me-1 fs-6 mb-1 mt-1">{{ $permiso->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if (Str::lower($rol->name) !== 'administrador')
                                    <div class="d-inline-flex">
                                        <a href="{{ route('roles.edit', $rol->id) }}" class="btn btnCrearRol btn-sm me-1">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>

                                        <!-- Botón que abre el modal -->
                                        <button type="button" class="btn btnCrearRol btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminarRol{{ $rol->id }}">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </div>

                                    <!-- Modal de confirmación -->
                                    <div class="modal fade" id="modalEliminarRol{{ $rol->id }}" tabindex="-1" aria-labelledby="modalEliminarRolLabel{{ $rol->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 shadow">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEliminarRolLabel{{ $rol->id }}">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Estás seguro de que deseas eliminar el rol <strong>{{ $rol->name }}</strong>?<br>
                                                    Esta acción no se puede deshacer.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('roles.destroy', $rol->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btnCrearRol" type="submit">Eliminar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay roles registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
