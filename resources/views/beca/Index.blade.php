<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>SICAB</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
        <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
        <link rel="stylesheet" href="{{ asset('css/tipoBeca.css') }}">
        <link rel="Stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />
    </head>

<body class="d-flex flex-column min-vh-100">
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
                    | Agregar usuarios
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

    <main class="flex-grow-1 mt-5">
        <div class="container-fluid h-100 overflow-hidden d-flex justify-content-center align-items-center mt-5 p-5 pt-0 mb-4">
            <div class="card rounded-4 shadow p-3 w-100">
                <div class="row g-3 align-items-center mt-1">

                    {{-- Título --}}
                    <div class="col-md-8 mb-3">
                        <h1 class="fw-bold color4 mb-4 ps-3">Tipo de Beca</h1>
                    </div>

                    <div class="col-md-4 text-end mb-3">
                        <div class="ms-auto">
                            <img class="w-25" id="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo"/>
                        </div>
                    </div>

                    {{-- Crear nuevo tipo de beca --}}
                    <div class="container-fluid mb-5">
                        <div class="row">
                            <div class="col-6">
                                <form method="POST" action="{{ route('tipobeca.store') }}">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="nombre" class="form-control shadow fs-5" placeholder="Nuevo tipo de beca" required>
                                        <button type="submit" class="btn btnEditar fs-5 shadow">
                                            <i class="fa-solid fa-plus"></i> Crear
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-2">

                            </div>
                            <div class="col-4">
                                <div class="d-flex align-items-md-center">
                                    <i class="fa-solid fa-magnifying-glass fa-lg color1"></i>
                                    <input type="text" id="buscadorBeca" class="form-control input-line fs-5" placeholder="| Buscar beca por nombre" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal de éxito --}}
                    @if(session('success'))
                    <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header text-white">
                                    <h5 class="modal-title" id="modalExitoLabel">
                                        <i class="bi bi-check-circle-fill me-2"></i> Operación exitosa
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body fs-5">
                                    {{ session('success') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Listado de tipos de beca existentes --}}
                    <div class="container-fluid mb-5 mt-3">
                        <div class="row g-3">
                            @foreach($tiposBeca as $tipo)
                            <div class="col-md-3">
                                <div class="card shadow rounded-4 p-3 h-100 d-flex flex-column justify-content-between">
                                    <div class="fs-5 fw-bold mb-3">
                                        {{ $tipo->propiedade->nombre ?? 'Sin nombre' }}
                                    </div>
                                    <div class="input-group mt-auto">
                                        <button type="button" class="btn btnEditar w-50" data-bs-toggle="modal" data-bs-target="#modalEditar"
                                            onclick="cargarEditar({{ $tipo->id }}, '{{ $tipo->propiedade->nombre ?? '' }}')">
                                            <i class="bi bi-pencil-fill"></i> Editar
                                        </button>
                                        <button type="button" class="btn btnEliminar w-50" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $tipo->id }}">
                                            <i class="bi bi-trash-fill"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal eliminar --}}
                            <div class="modal fade" id="modalEliminar{{ $tipo->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $tipo->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $tipo->id }}">Confirmar eliminación</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                                            <form method="POST" action="{{ route('tipobeca.destroy', $tipo->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btnEditar">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditarLabel">Editar tipo de beca</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" id="formEditarTipoBeca" action="">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="nombreEditar" class="form-label">Nombre</label>
                                            <input type="text" id="nombreEditar" name="nombre" class="form-control shadow fs-5" required>
                                        </div>
                                        <div class="d-flex justify-content-end gap-3">
                                            <button type="button" class="btn btnCancelar" data-bs-dismiss="modal">
                                                <i class="fa-solid fa-xmark"></i> Cancelar
                                            </button>
                                            <button type="submit" class="btn btnEditar">
                                                <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
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
                <div class="col-12 d-flex justify-content-center text-center align-items-center">
                    <p id="footerText" class="mb-0">Copyright ©2025 Especialidad Desarrollo Web | COVAO.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const baseUrlTipobeca = "{{ url('tipobeca') }}";

        function cargarEditar(id, nombre) {
            const inputNombre = document.getElementById('nombreEditar');
            const formEditar = document.getElementById('formEditarTipoBeca');
            formEditar.action = baseUrlTipobeca + '/' + id;
            inputNombre.value = nombre;
        }

        document.addEventListener("DOMContentLoaded", function () {
            @if(session('success'))
                const editarEl = document.getElementById('modalEditar');
                if (editarEl) {
                    try { bootstrap.Modal.getInstance(editarEl)?.hide(); } catch(e) {}
                }

                const exitoEl = document.getElementById('modalExito');
                if (exitoEl) {
                    const modalExito = new bootstrap.Modal(exitoEl);
                    modalExito.show();
                    setTimeout(() => { try { modalExito.hide(); } catch(e) {} }, 3000);
                }
            @endif
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const buscador = document.getElementById('buscadorBeca');
        const cards = document.querySelectorAll('.row.g-3 > .col-md-3');

        buscador.addEventListener('input', function() {
            const texto = this.value.toLowerCase();

            cards.forEach(card => {
                const nombre = card.querySelector('.fs-5.fw-bold').textContent.toLowerCase();
                if(nombre.includes(texto)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>