<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Buscar Estudiante</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/InformacionEstudiante.css') }}">
  <link rel="stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />
</head>
<body>

<!-- Botón y menú lateral -->
<button id="btn-Menu" class="btn ms-3 mb-3 fs-5 py-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
    <i class="fa-solid fa-bars fa-xl" style="color: #f7f7f7;"></i>
</button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample">
    <div class="offcanvas-header justify-content-end">
        <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close"> 
            <i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i> 
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="d-grid gap-3">
            <button class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location.href='{{ route('admin.home') }}'">
                <i class="fa-solid fa-house-chimney fa-lg"></i> | Home
            </button>
            <button class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                <i class="fa-solid fa-clipboard-list fa-lg"></i> | Ingreso al comedor
            </button>
            <button class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                <i class="fa-solid fa-street-view fa-lg"></i> | Agregar usuarios
            </button>
            <button class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.informacion') }}'">
                <i class="fa-solid fa-address-card fa-lg"></i> | Ver lista de usuarios
            </button>
            <button class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('Reportes.DescargarReporte') }}'">
                <i class="fa-solid fa-download fa-lg"></i> | Descargar reportes
            </button>
            <button class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('tipobeca.index') }}'">
                <i class="fa-solid fa-hand-holding-medical fa-lg"></i> | Becas
            </button>
            <button class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('AsistenciaRapida.asistenciaRapida') }}'">
                <i class="fa-solid fa-star-half-stroke fa-lg"></i> | Asistencia rápida
            </button>
        </div>
    </div>
</div>

<!-- Fondo fijo -->
<div class="position-fixed top-0 start-0 w-100 h-100 z-n1">
    <img src="{{ asset('img/FondoPrincipal.webp') }}" class="w-100 h-100" alt="Fondo">
</div>

<div class="container-fluid p-4 ps-5 pe-5 mb-4">
    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3">
        <div class="header d-flex flex-column flex-md-row align-items-stretch align-items-md-center flex-grow-1 gap-3 shadow-sm">
            <form method="POST" action="{{ route('estudiantes.informacion') }}" class="d-flex gap-3 w-100" role="search">
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
                <button type="submit" class="btn btnPrimario btnCancelar fs-5 ms-2">Buscar</button>
            </form>
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
            <div class="col-md-8 p-2">
                <h1 class="fw-bold color4 mb-4 ps-3">{{ $persona->Nombre }} {{ $persona->PrimerApellido }} {{ $persona->SegundoApellido }}</h1>

                <div class="mb-5 form-group-horizontal">
                    <label class="form-label fs-5 color1"><strong>Cédula</strong></label>
                    <div class="form-input-flex inputColor fs-5">{{ $persona->Cedula }}</div>
                </div>

                <div class="mb-5 form-group-horizontal">
                    <label class="form-label fs-5 color1"><strong>Sección</strong></label>
                    <div class="form-input-flex inputColor fs-5">{{ $persona->estudiante->seccione->propiedade->nombre ?? 'N/A' }}</div>
                </div>

                <div class="mb-5 form-group-horizontal">
                    <label class="form-label fs-5 color1"><strong>Especialidad</strong></label>
                    <div class="form-input-flex inputColor fs-5">{{ $persona->estudiante->especialidade->propiedade->nombre ?? 'N/A' }}</div>
                </div>
<strong>Seleccionar el tipo de beca</strong>
<select id="becaSeleccionada" class="form-select">
    <option value="">Seleccione una beca</option>
    @foreach($persona->estudiante->tipoBecas as $beca)
        <option value="{{ $beca->propiedade->nombre }}">
            {{ $beca->propiedade->nombre }}
        </option>
    @endforeach
</select>





                <!-- Selector de mes -->
                <div class="mb-4 form-group-horizontal">
                    <label for="mesSeleccionado" class="form-label fs-5 color1"><strong>Seleccionar mes</strong></label>
                    <input type="month" id="mesSeleccionado" class="form-control" value="{{ date('Y-m') }}">
                </div>

                <!-- Tabla de asistencias -->
                <div class="container-fluid mb-4">
                    <h3 class="fw-bold mb-3">Historial de Asistencias</h3>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0" id="tablaAsistencias">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center">Seleccione una beca y un mes para ver las asistencias</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4 text-center mb-5 align-self-center">
                <img src="{{ asset($persona->estudiante && $persona->estudiante->foto ? $persona->estudiante->foto : 'img/FotoEstudiante.webp') }}" alt="Foto del estudiante" class="foto-perfil-redonda">
            </div>
        </div>
    </div>
</div>
@endif

<!-- Scripts -->
<script>
    const estudianteId = {{ $persona->estudiante->id }};
    
    const becaSelect = document.getElementById('becaSeleccionada');
    const mesSelect = document.getElementById('mesSeleccionado');

    async function cargarAsistencias() {
        const tipoBeca = becaSelect.value;
        const mes = mesSelect.value;

        if (!mes || !tipoBeca) {
            document.querySelector('#tablaAsistencias tbody').innerHTML =
                '<tr><td colspan="4" class="text-center">Seleccione una beca y un mes</td></tr>';
            return;
        }

        const res = await fetch(`/estudiantes/${estudianteId}/asistencias?tipo_beca=${tipoBeca}&mes=${mes}`);
        const data = await res.json();

        const tbody = document.querySelector('#tablaAsistencias tbody');
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay asistencias para este mes y beca</td></tr>';
            return;
        }

        tbody.innerHTML = data.map(a => `
            <tr>
                <td>${a.fecha_hora}</td>
                <td>${a.tipo_asistencia}</td>
                <td>${a.estado}</td>
                <td>${a.observaciones}</td>
            </tr>
        `).join('');
    }

    becaSelect.addEventListener('change', cargarAsistencias);
    mesSelect.addEventListener('change', cargarAsistencias);
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
