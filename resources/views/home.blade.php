<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SICAB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/Home.css') }}" />
    <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- HEADER -->
    <div class="container-fluid pt-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 main-header gap-3">
            <!-- Información de usuario y logo -->
            <div class="header d-flex flex-column flex-md-row align-items-center justify-content-between shadow-sm w-100 gap-3 p-3">
                <div class="user-info d-flex align-items-center gap-2">
                    <!-- Icono que abre modal -->
                    <div class="icon-box" role="button" data-bs-toggle="modal" data-bs-target="#userInfoModal">
                        <i class="bi bi-person-circle" id="iconUser"></i>
                    </div>
                    <span id="nombre-orientadora" class="fw-bold">{{ auth()->user()->persona->Nombre }}</span>
                </div>
                <img class="logo" src="../img/LogoCovao.webp" alt="Logo">
            </div>

            <!-- Botón Logout con Modal -->
            <div class="header2 d-flex align-items-center justify-content-center shadow-sm">
                <button class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-list fs-2"></i>
                </button>
            </div>
        </div>
    </div>

    <main class="flex-grow-1 mb-5">
        <!-- TARJETAS -->
        <div class="container-fluid2">
            <div class="row g-4">
                {{-- Ingreso al Comedor --}}
                <div class="col-sm-12 col-md-6 col-lg-4">
                    @can('ver ingreso comedor')
                        <a href="{{ route('IngresoCom.IngresoComedor') }}" class="text-decoration-none">
                            <div class="card card-fondo text-white text-center p-4 mt-2">
                                <div class="card-overlay"></div>
                                <div class="card-content text-start">
                                    <i class="bi bi-house-door-fill card-icon"></i>
                                    Ingreso al Comedor
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="card card-fondo text-white text-center p-4 mt-2" role="button" data-bs-toggle="modal" data-bs-target="#sinPermisoModal">
                            <div class="card-overlay"></div>
                            <div class="card-content text-start">
                                <i class="bi bi-house-door-fill card-icon"></i>
                                Ingreso al Comedor
                            </div>
                        </div>
                    @endcan
                </div>

                {{-- Información de usuarios --}}
                <div class="col-sm-12 col-md-6 col-lg-4">
                    @can('ver estudiantes')
                        <a href="{{ route('estudiantes.informacion') }}" class="text-decoration-none">
                            <div class="card card-fondo text-white text-center p-4 mt-2">
                                <div class="card-overlay"></div>
                                <div class="card-content text-start">
                                    <i class="bi bi-people-fill card-icon"></i>
                                    Información de usuarios
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="card card-fondo text-white text-center p-4 mt-2" role="button" data-bs-toggle="modal" data-bs-target="#sinPermisoModal">
                            <div class="card-overlay"></div>
                            <div class="card-content text-start">
                                <i class="bi bi-people-fill card-icon"></i>
                                Información de usuarios
                            </div>
                        </div>
                    @endcan
                </div>

                {{-- Tipos de Beca --}}
                <div class="col-sm-12 col-md-6 col-lg-4">
                    @can('ver tipo beca')
                        <a href="{{ route('tipobeca.index') }}" class="text-decoration-none">
                            <div class="card card-fondo text-white text-center p-4 mt-2">
                                <div class="card-overlay"></div>
                                <div class="card-content text-start">
                                    <i class="bi bi-award-fill card-icon"></i>
                                    Tipos de beca
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="card card-fondo text-white text-center p-4 mt-2" role="button" data-bs-toggle="modal" data-bs-target="#sinPermisoModal">
                            <div class="card-overlay"></div>
                            <div class="card-content text-start">
                                <i class="bi bi-award-fill card-icon"></i>
                                Tipos de beca
                            </div>
                        </div>
                    @endcan
                </div>

                {{-- Agregar usuarios --}}
                <div class="col-sm-12 col-md-6 col-lg-4">
                    @can('importar estudiantes')
                        <a href="{{ route('estudiantes.importar.form') }}" class="text-decoration-none">
                            <div class="card card-fondo text-white text-center p-4 mt-2 h-100">
                                <div class="card-overlay"></div>
                                <div class="card-content text-start">
                                    <i class="bi bi-person-plus-fill card-icon"></i>
                                    Agregar usuarios
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="card card-fondo text-white text-center p-4 mt-2 h-100" role="button" data-bs-toggle="modal" data-bs-target="#sinPermisoModal">
                            <div class="card-overlay"></div>
                            <div class="card-content text-start">
                                <i class="bi bi-person-plus-fill card-icon"></i>
                                Agregar usuarios
                            </div>
                        </div>
                    @endcan
                </div>

                {{-- Descargar Reportes --}}
                <div class="col-sm-12 col-md-6 col-lg-4">
                    @can('descargar reportes')
                        <a href="{{ route('Reportes.DescargarReporte') }}" class="text-decoration-none">
                            <div class="card card-fondo text-white text-center p-4 mt-2">
                                <div class="card-overlay"></div>
                                <div class="card-content text-start">
                                    <i class="bi bi-file-earmark-arrow-down-fill card-icon"></i>
                                    Descargar Reportes
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="card card-fondo text-white text-center p-4 mt-2" role="button" data-bs-toggle="modal" data-bs-target="#sinPermisoModal">
                            <div class="card-overlay"></div>
                            <div class="card-content text-start">
                                <i class="bi bi-file-earmark-arrow-down-fill card-icon"></i>
                                Descargar Reportes
                            </div>
                        </div>
                    @endcan
                </div>

                {{-- Asistencias rápidas --}}
                <div class="col-sm-12 col-md-6 col-lg-4">
                    @can('asistencia rápida')
                        <a href="{{route('AsistenciaRapida.asistenciaRapida')}}" class="text-decoration-none">
                            <div class="card card-fondo text-white text-center p-4 mt-2">
                                <div class="card-overlay"></div>
                                <div class="card-content text-start">
                                    <i class="bi bi-check2-square card-icon"></i>
                                    Asistencias rápidas
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="card card-fondo text-white text-center p-4 mt-2" role="button" data-bs-toggle="modal" data-bs-target="#sinPermisoModal">
                            <div class="card-overlay"></div>
                            <div class="card-content text-start">
                                <i class="bi bi-check2-square card-icon"></i>
                                Asistencias rápidas
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- MODAL DE LOGOUT PERSONALIZADO -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-estilo">
                    <div class="modal-header modal-header-estilo">
                        <h5 class="modal-title text-white">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>Advertencia
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center fw-bold fs-5 py-4">
                        Opciones Adicionales
                    </div>
                    <div class="modal-footer d-flex justify-content-center gap-3 border-0 pb-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-modal-gestionar">Gestionar Roles</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-modal-confirmar">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL SIN PERMISO -->
        <div class="modal fade" id="sinPermisoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-exclamation-circle-fill me-2"></i>Sin Permiso</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center fw-bold fs-5 py-4 colorB">
                        No tienes permiso para acceder a esta opción.
                    </div>
                    <div class="modal-footer d-flex justify-content-center border-0 pb-4">
                        <button type="button" class="btn btnAceptar" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
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

    <!-- SCRIPTS BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>