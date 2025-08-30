<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tipo de Beca</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    {{-- FontAwesome --}}
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    {{-- Tu CSS personalizado --}}
    <link rel="stylesheet" href="{{ asset('css/tipoBeca.css') }}">
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
                    | Agregar Usuarios
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.informacion') }}'">
                    <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i>
                    | Ver lista de usuarios
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

    {{-- Fondo fijo --}}
    <div class="position-fixed top-0 start-0 w-100 h-100 z-n1">
        <img src="{{ asset('img/FondoPrincipal.webp') }}" class="w-100 h-100" alt="Fondo">
    </div>

    <div class="container-fluid p-4 ps-5 pe-5">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3">
        <div class="header ms-7 d-flex flex-column flex-md-row align-items-stretch align-items-md-center flex-grow-1 gap-3 shadow-sm">
    {{-- Buscar por nombre --}}
    <div class="input-icon-container d-flex flex-column flex-md-row align-items-start align-items-md-center">
        <i class="fa-solid fa-clipboard-user color1"></i>
        <input type="text" class="input-line fs-5" placeholder="Buscar por nombre" />
    </div>

    {{-- Logo --}}
    <div class="ms-md-auto text-center text-md-end">
        <img class="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo" />
    </div>
</div>

        </div>
    </div>

    {{-- Tarjeta --}}
    <div class="container-fluid h-100 overflow-hidden d-flex justify-content-center align-items-center p-5 pt-0 mb-4">
        <div class="card rounded-4 shadow p-3 w-100">
            <div class="row g-3 align-items-center">

                {{-- Título --}}
                <div class="col-md-8">
                    <h1 class="fw-bold color4 mb-4 ps-3">Tipo de Beca</h1>
                </div>

                {{-- Crear nuevo tipo de beca --}}
                <div class="mb-5 form-group-horizontal d-flex align-items-center gap-3">
                    <form method="POST" action="{{ route('tipobeca.store') }}" class="d-flex gap-2 align-items-center">
                        @csrf
                        <input type="text" name="nombre" class="form-control inputColor sin-borde-focus fs-5" placeholder="Nuevo tipo de beca" required>
                        <div class="text-center me-5">
                            <button type="submit" class="btnPrimario fs-5">
                                <i class="fa-solid fa-plus"></i> Crear
                            </button>
                        </div>
                    </form>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                {{-- Formulario oculto para editar (puedes mostrarlo con JS) --}}
                <div id="formEditar" class="form-editar">
                <form method="POST" id="formEditarTipoBeca" action="">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombreEditar" class="form-label">Editar nombre</label>
                        <input type="text" id="nombreEditar" name="nombre" class="form-control inputColor sin-borde-focus fs-5" required>
                    </div>

                    <div class="botones-editar d-flex gap-3">
                        <button type="submit" class="btnPrimario btnEditar fs-5">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                        </button>
                        <button type="button" class="btnPrimario btnEditar fs-5" onclick="ocultarEditar()">
                            <i class="fa-solid fa-xmark"></i> Cancelar
                        </button>
                    </div>
                </form>

</div>


                {{-- Listado de tipos de beca existentes --}}
                @foreach($tiposBeca as $tipo)
                <div class="mb-5 form-group-horizontal ">
                    <div class="form-input-flex inputColor2 ms-3 fs-5" aria-readonly="true">
                        {{ $tipo->propiedade->nombre ?? 'Sin nombre' }}
                    </div>

                    {{-- Botón que abre el modal --}}
                    <div class="text-center">
                        <button type="button" class="btnPrimario fs-5" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $tipo->id }}">
                            <i class="bi bi-trash-fill"></i> Eliminar
                        </button>
                    </div>

                    {{-- Modal --}}
                    <div class="modal fade" id="modalEliminar{{ $tipo->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $tipo->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $tipo->id }}">Confirmar eliminación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btnPrimario btnCancelar" data-bs-dismiss="modal">Cancelar</button>

                                    {{-- Formulario de eliminación --}}
                                    <form method="POST" action="{{ route('tipobeca.destroy', $tipo->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btnPrimario">Eliminar</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Formulario editar (modal o formulario inline) --}}
                    <div class="text-center me-5">
                    <button class="btnPrimario fs-5" onclick="mostrarEditar({{ $tipo->id }}, '{{ $tipo->propiedade->nombre }}')">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </button>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <script>
        function mostrarEditar(id, nombre) {
            const form = document.getElementById('formEditar');
            const inputNombre = document.getElementById('nombreEditar');
            const formEditar = document.getElementById('formEditarTipoBeca');

            formEditar.action = `/tipobeca/${id}`; // Ajusta la ruta según tu ruta PUT
            inputNombre.value = nombre;
            form.style.display = 'block';
        }

        function ocultarEditar() {
            document.getElementById('formEditar').style.display = 'none';
        }
        document.addEventListener("DOMContentLoaded", function () {
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                // Opcional: efecto fade-out antes de remover
                successAlert.classList.remove('show');
                successAlert.classList.add('fade');

                setTimeout(() => {
                    successAlert.remove();
                }, 500); // Espera a que termine el fade
            }, 4000); // Mostrar durante 4 segundos
        }
        });
    </script>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
