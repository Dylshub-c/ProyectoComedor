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
    <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)" />
    <title>Desarrollo Web</title>
</head>

<body id="fondo" class="d-flex flex-column min-vh-100">

    <main class="flex-grow-1">
        <div class="container-fluid">
            <div id="rowSuperior" class="row">
                <div id="lineaHorizontal" class="col-5 px-5">
                    <h2 class="mt-3 px-3">Subir estudiantes desde un archivo excel:</h2>
                    <button type="button" id="btnAbrir" class="btn fs-5 mb-4 mt-4 px-4 py-2 btn-success">
                        <i class="fa-solid fa-file-export fa-lg" style="color: #f7f7f7;"></i> | Subir Archivo
                    </button>
                    <form action="{{ route('estudiantes.importar') }}" method="POST" enctype="multipart/form-data" id="formImportar">
                        @csrf
                    
                        <input type="file" id="fileInput"  name="archivo" accept=".xlsx, .xls" />
                    </form>
                </div>

                <div class="col-5 px-5">
                    <h2 class="mt-3">Subir estudiantes desde la aplicación (individualmente):</h2>
                    <button type="button" id="btnIndividual" class="btn fs-5 mb-4 mt-4 px-4 py-2 btn-success">
                        <i class="fa-solid fa-user-plus fa-lg" style="color: #f7f7f7;"></i> | Nuevo estudiante
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
                    <button type="button" id="btnCeleste" class="btn fs-5 me-3 mt-4 px-3 py-1 btn-success">
                        <i class="fa-solid fa-file-excel fa-lg" style="color: #f7f7f7;"></i> | Eliminar lista
                    </button>
                    <button type="button" id="btnCeleste" class="btn fs-5 mt-4 px-4 py-1 btn-success">
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

            <div class="row">
                <div class="col-10"></div>
                <div class="col-2">
                    <button type="submit" form="formImportar" id="btnIndividual" class="btn fs-5 mt-3 mb-4 px-4 py-2 btn-success">
                        <i class="fa-solid fa-repeat fa-lg" style="color: #f7f7f7;"></i> | Finalizar
                    </button>
                </div>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/AnadirEstudiantesM.js') }}"></script>
    <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>

</body>

</html>
