<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Asistencia Rápida</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/AsistenciaRapida.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/MenuLateral.css') }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    {{-- Menú lateral --}}
    <button id="btn-Menu" class="btn ms-3 mb-3 fs-5 py-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <i class="fa-solid fa-bars fa-xl" style="color: #f7f7f7;"></i>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample">
        <div class="offcanvas-header justify-content-end">
            <button type="button" class="btn" data-bs-dismiss="offcanvas"><i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i></button>
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
        <div class="offcanvas-footer p-3 border-top">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-arrow-right-to-bracket fa-lg"></i> | Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    {{-- Cabecera --}}
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="header d-flex justify-content-between align-items-center shadow-sm">
                    <div class="text-start flex-grow-1">
                        <h4 class="fw-bold m-0 fs-2">Asistencia rápida</h4>
                        <p class="text-muted m-0 fs-5">Realice asistencias de forma general en caso de alguna situación que lo amerite.</p>
                    </div>
                    <img id="LogoCovao" src="/img/LogoCovao.webp" alt="Logo" />
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    <div class="container-fluid mt-4">
        <form id="formAsistencia" novalidate>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha" class="form-label fw-bold fs-5">Seleccione la fecha:</label>
                    <input type="date" id="fecha" class="form-control fs-5" required>
                </div>
                <div class="col-md-6">
                    <label for="tipoAsistencia" class="form-label fw-bold fs-5">Seleccione la beca:</label>
                    <select id="tipoAsistencia" class="form-select fs-5" required>
                        <option value="" selected disabled>Seleccione una opción</option>
                        @foreach($tiposBeca as $beca)
                            <option value="{{ $beca->id }}">{{ $beca->propiedade->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="explicacion" class="form-label fw-bold fs-5">Observaciones:</label>
                    <textarea id="explicacion" class="form-control fs-5" rows="3" placeholder="Escriba aquí..."></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <table class="table table-bordered" id="tablaEstudiantes">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Beca</th>
                                <th>Presente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">Seleccione un tipo de beca para cargar los estudiantes</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-success fs-5" id="confirmarAsistencia">Realizar asistencia</button>
            </div>
        </form>
    </div>

    {{-- Modal éxito --}}
    <div class="modal fade" id="modalExito" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <p class="fs-5 mb-0">La asistencia se realizó con éxito.</p>
                    <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
$(document).ready(function() {

    // Cargar estudiantes según beca
    $('#tipoAsistencia').change(function() {
        let beca_id = $(this).val();
        if(!beca_id) return;

        $.ajax({
            url: "{{ route('AsistenciaRapida.asistenciaRapida') }}",
            type: "GET",
            data: { beca_id: beca_id },
            success: function(res) {
                let tbody = '';
                if(res.length > 0){
                    res.forEach(est => {
                        tbody += `<tr data-id="${est.id}">
                            <td>${est.nombre_completo}</td>
                            <td>${est.beca}</td>
                            <td class="text-center">
                                <input type="checkbox" class="asistencia-checkbox" checked>
                            </td>
                        </tr>`;
                    });
                } else {
                    tbody = '<tr><td colspan="3" class="text-center">No hay estudiantes para esta beca</td></tr>';
                }
                $('#tablaEstudiantes tbody').html(tbody);
            },
            error: function(err){
                console.error('Error al cargar estudiantes:', err);
                alert('Error al cargar estudiantes. Ver consola para más detalles.');
            }
        });
    });

    // Guardar asistencia masiva
    $('#confirmarAsistencia').click(function() {
        let fecha = $('#fecha').val();
        let tipoBeca = $('#tipoAsistencia').val();
        let observaciones = $('#explicacion').val();
        let estudiantes = [];

        $('#tablaEstudiantes tbody tr').each(function() {
            let estudianteId = $(this).data('id');
            if(!estudianteId) return;

            estudiantes.push({
                id: estudianteId,
                presente: $(this).find('.asistencia-checkbox').is(':checked')
            });
        });

        if(!fecha || !tipoBeca || estudiantes.length === 0){
            alert('Seleccione fecha, beca y asegúrese de que haya estudiantes.');
            return;
        }

        const token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ route('asistencia.rapida.guardar') }}",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                fecha_hora: fecha,
                tipo_asistencia: tipoBeca,
                observaciones: observaciones,
                estudiantes: estudiantes
            }),
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(res){
                $('#modalExito').modal('show');
            },
            error: function(err){
                console.error('Error al guardar la asistencia:', err);
                alert('Error al guardar la asistencia. Ver consola para más detalles.');
            }
        });
    });

});
</script>

</body>
</html>
