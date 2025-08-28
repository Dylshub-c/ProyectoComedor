<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.17/locales/es.global.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="Stylesheet" href="{{ asset('css/AnadirEstudiantesM.css') }}" type="text/css" />
    <link rel="Stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />
    <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)" />
    <title>Desarrollo Web</title>
</head>

<body id="fondo" class="d-flex flex-column min-vh-100">


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
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
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
    <main class="flex-grow-1">
        <div class="container-fluid">
            <div id="rowSuperior" class="row">
                <div id="lineaHorizontal" class="col-5 px-5">
                    <h2 class="mt-3 px-3">Subir estudiantes desde un archivo excel:</h2>
                    <button type="btn btn-success" id="btnAbrir" class="btn fs-5 mb-4 mt-4 px-4 py-2">
                        <i class="fa-solid fa-file-export fa-lg" style="color: #f7f7f7;"></i> | Subir Archivo
                    </button>
                     <button id="btnSubir" type="submit" form="formImportar" class="btn fs-5 ms-2 px-4 py-2">
                        <i class="fa-solid fa-upload fa-lg" style="color: #f7f7f7;"></i>
                        | Subir al sistema
                    </button>
                    <form action="{{ route('estudiantes.importar') }}" method="POST" enctype="multipart/form-data" id="formImportar">
                        @csrf

                        <input type="file" id="fileInput"  name="archivo" accept=".xlsx, .xls" />
                    </form>
                </div>

                <div class="col-5 px-5">
                    <h2 class="mt-3">Subir usuarios desde la aplicación (individualmente):</h2>
                    <button type="btn btn-success" id="btnIndividual" class="btn fs-5 mb-4 mt-4 px-4 py-2" onclick="window.location='{{ route('estudiantes.create') }}'">
                        <i class="fa-solid fa-user-plus fa-lg" style="color: #f7f7f7;"></i> | Nuevo usuario
                    </button>
                </div>

                <div class="col-2 mt-4">
                    <img class="img-fluid" id="LogoCovao" src="/img/LogoCovao.webp" alt="COVAO" />
                </div>
            </div>
        </div>

        <div id="rowInferior" class="container-fluid">
            <div id="rowI" class="row">
                <div class="col-6">
                    <h1 class="mt-4">Lista de estudiantes subidos:</h1>
                </div>

                <div class="col-2 text-end"></div>

                <div class="col-4 justify-content-center d-flex">
                    <button type="btn btn-success" id="btnCeleste" class="btn fs-5 me-3 mt-4 px-3 py-1">
                        <i class="fa-solid fa-file-excel fa-lg" style="color: #f7f7f7;"></i> | Eliminar lista
                    </button>
                    <button type="btn btn-success" id="btnCeleste" class="btn fs-5 mt-4 px-4 py-1">
                        <i class="fa-solid fa-repeat fa-lg" style="color: #f7f7f7;"></i> | Recargar lista
                    </button>
                </div>
            </div>

            <div class="row px-5">
                <table id="tabla" class="table table-striped-columns table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th id="tb-a" scope="col">N°</th>
                            <th id="tb-b" scope="col">Cédula</th>
                            <th id="tb-a" scope="col">Nombre y Apellidos</th>
                            <th id="tb-b" scope="col">Especialidad</th>
                            <th id="tb-a" scope="col">Tipo de Beca</th>
                            <th id="tb-b" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($estudiantes) && $estudiantes->isNotEmpty())
                            @foreach ($estudiantes as $index => $estudiante)
                                <tr>
                                    <th id="tb-a" scope="row">{{ $index + 1 }}</th>
                                    <td id="tb-b">{{ $estudiante->persona->Cedula ?? 'N/A' }}</td>
                                    <td id="tb-a">
                                        {{ $estudiante->persona->Nombre ?? '' }}
                                        {{ $estudiante->persona->PrimerApellido ?? '' }}
                                        {{ $estudiante->persona->SegundoApellido ?? '' }}
                                    </td>
                                    <td id="tb-b">{{ $estudiante->especialidade->propiedade->nombre ?? 'N/A' }}</td>
                                    <td id="tb-a">{{ $estudiante->tipoBeca->propiedade->nombre ?? 'N/A' }}</td>
                                    <td id="tb-b" class="text-center">
                                        <i class="fa-solid fa-circle-minus fa-lg" style="color: #106AA4;"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted fst-italic">No hay estudiantes cargados aún.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <form action="{{ route('subir-fotos.importar') }}" method="POST" form="formSubir" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-10">
                        <button type="submit" id="btnComprimido" class="btn fs-5 ms-4 mb-4 mt-4 px-4 py-2">
                            <i class="fa-solid fa-image-portrait fa-lg" style="color: #f7f7f7;"></i> | Subir fotos de estudiantes
                        </button>

                        <button type="submit" id="btnDescomprimir" class="btn btn-primary fs-5 ms-2 px-4 py-2">
                            <i class="fa-solid fa-upload fa-lg" style="color: #f7f7f7;"></i> | Descomprimir y subir
                        </button>

                        <input type="file" id="fileRAR" name="zip" accept=".zip,.rar" class="form-control mt-2" required>
                    </div>
            </form>

                    <div class="col-2">
                        <button type="button" id="btnIndividual" onclick="window.location.href='{{ route('admin.home') }}'"  class="btn fs-5 mt-3 mb-4 px-4 py-2">
                            <i class="fa-solid fa-repeat fa-lg" style="color: #f7f7f7;"></i> | Finalizar
                        </button>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert" id="alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" id="alert-error">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </main>

    <footer id="DivFooter" class="text-dark py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center text-center">
                    <p id="footerText" class="mb-0">Copyright ©2025 Especialidad Desarrollo Web | COVAO.</p>
                </div>
            </div>
        </div>
    </footer>
    <script>
    // Ocultar automáticamente después de 3 segundos
    setTimeout(() => {
        const alertSuccess = document.getElementById('alert-success');
        const alertError = document.getElementById('alert-error');

        if (alertSuccess) {
            alertSuccess.classList.remove('show');
            alertSuccess.classList.add('fade');
        }

        if (alertError) {
            alertError.classList.remove('show');
            alertError.classList.add('fade');
        }
    }, 3000); // 3000ms = 3 segundos
</script>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/AnadirEstudiantesM.js') }}"></script>
    <script src="{{ asset('js/MenuLateral.js') }}"></script>
    <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>

</body>

</html>
