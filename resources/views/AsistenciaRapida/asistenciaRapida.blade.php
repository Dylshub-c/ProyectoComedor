<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Asistencia Rápida</title>

 
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/asistenciaRapida.css') }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
 
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

 
  <div class="container-fluid2 mt-4">
    <form id="formAsistencia" novalidate>
 
      <div class="row mb-3">
        <div class="col-md-4 mb-3 mb-md-0">
          <label for="fecha" class="form-label fw-bold fs-5">Seleccione la fecha:</label>
          <div class="input-group">
            <input type="date" id="fecha" class="form-control fs-5" placeholder="dd/mm/aaaa" />
            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
          </div>
          <div class="mensaje-error fs-5" id="errorFecha" style="display: none;">
            <i class="bi bi-exclamation-circle-fill me-1"></i>Seleccione una fecha
          </div>
        </div>

        <div class="col-md-4">
          <label for="tipoAsistencia" class="form-label fw-bold fs-5">Seleccione la beca para la asistencia:</label>
          <select id="tipoAsistencia" class="form-select beca-select fs-5">
            <option selected disabled value="">Seleccione una opción</option>
            <option value="Desayuno - Almuerzo">Desayuno - Almuerzo</option>
            <option value="Desayuno">Desayuno</option>
            <option value="Almuerzo">Almuerzo</option>
          </select>
          <div class="mensaje-error fs-5" id="errorTipo" style="display: none;">
            <i class="bi bi-exclamation-circle-fill me-1"></i>Seleccione un tipo de beca
          </div>
        </div>

        <div class="col-md-4">
          <label for="estadoAsistencia" class="form-label fw-bold fs-5">Marcar asistencia como:</label>
          <select id="estadoAsistencia" class="form-select beca-select fs-5">
            <option selected disabled value="">Seleccione una opción</option>
            <option value="Presente">Presente</option>
            <option value="Ausente">Ausente</option>
          </select>
          <div class="mensaje-error fs-5" id="errorEstado" style="display: none;">
            <i class="bi bi-exclamation-circle-fill me-1"></i>Seleccione un estado
          </div>
        </div>
      </div>


      <div class="mb-4 textarea-box">
        <div class="etiqueta-superior">
          <label for="explicacion" class="m-0 text-white fw-semibold fs-5">Explicación del caso:</label>
          <i class="bi bi-clipboard-fill clipboard-icon text-white"></i>
        </div>
        <textarea id="explicacion" class="form-control fs-4" rows="6" placeholder="Escriba aquí..."></textarea>
      </div>


      <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-guardar px-4 shadow fs-5" id="confirmarAsistencia">
          <i class="bi bi-check-square"></i> Realizar asistencia
        </button>
      </div>
    </form>
  </div>


  <div class="modal fade" id="modalExito" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header justify-content-end border-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body pt-0">
          <p class="mt-2 mb-0 fs-5">La asistencia se realizó con éxito.</p>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.getElementById('confirmarAsistencia').addEventListener('click', function() {
      const fecha = document.getElementById('fecha').value;
      const tipoAsistencia = document.getElementById('tipoAsistencia').value;
      const estado = document.getElementById('estadoAsistencia').value;
      const observaciones = document.getElementById('explicacion').value;

      if (!fecha || !tipoAsistencia || !estado) {
          alert('Por favor complete todos los campos requeridos.');
          return;
      }

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch('/asistencia-rapida', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({
        fecha_hora: fecha,
        tipo_asistencia: tipoAsistencia,
        estado: estado,
        observaciones: observaciones
    })
})
      .then(response => response.json())
      .then(data => {
          if(data.message){
              var modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
              modalExito.show();
          } else if(data.error){
              alert('Error: ' + data.error);
          }
      })
      .catch(error => console.error('Error:', error));
    });
  </script>
</body>
</html>
