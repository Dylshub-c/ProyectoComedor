<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Asistencia Rápida</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap + Iconos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="/AsistenciaRapida.css" />
</head>
<body>

  <!-- Encabezado -->
  <div class="container-fluid mt-4">
    <div class="row">
      <div class="col-12">
        <div class="header d-flex justify-content-between align-items-center shadow-sm">
          <div class="text-start flex-grow-1">
            <h4 class="fw-bold m-0 fs-2">Asistencia rápida</h4>
            <p class="text-muted m-0 fs-5">Realice asistencias de forma general en caso de alguna situación que lo amerite.</p>
          </div>
          <img id="LogoCovao" src="/img/LogoCovao.webp" alt="Logo"/>
        </div>
      </div>
    </div>
  </div>

  <!-- Contenido principal -->
  <div class="container-fluid2 mt-4">
    <form id="formAsistencia" novalidate>

      <!-- Fecha y Beca  -->
      <div class="row mb-3">
        <div class="col-md-4 mb-3 mb-md-0">
          <label for="fecha" class="form-label fw-bold fs-5">Seleccione la fecha:</label>
          <div class="input-group">
            <input type="date" id="fecha" class="form-control fs-5" placeholder="dd/mm/aaaa">
            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
          </div>
          <div class="mensaje-error fs-5" id="errorFecha" style="display: none;">
            <i class="bi bi-exclamation-circle-fill me-1"></i>Seleccione una fecha
          </div>
        </div>

        <div class="col-md-4">
          <label for="beca" class="form-label fw-bold fs-5">Seleccione la beca para la asistencia:</label>
          <select id="beca" class="form-select beca-select fs-5">
            <option selected disabled value="">Seleccione una opción</option>
            <option>Desayuno - Almuerzo</option>
            <option>Desayuno</option>
            <option>Almuerzo</option>
          </select>
          <div class="mensaje-error fs-5" id="errorBeca" style="display: none;">
            <i class="bi bi-exclamation-circle-fill me-1"></i>Seleccione un tipo de beca
          </div>
        </div>

        <div class="col-md-4">
          <label for="beca" class="form-label fw-bold fs-5">Marcar asistencia como:</label>
          <select id="beca" class="form-select beca-select fs-5">
            <option selected disabled value="">Seleccione una opción</option>
            <option>Presentes</option>
            <option>Ausentes</option>
            <option>Evento</option>
          </select>
          <div class="mensaje-error fs-5" id="errorBeca" style="display: none;">
            <i class="bi bi-exclamation-circle-fill me-1"></i>Seleccione un tipo de beca
          </div>
        </div>
      </div>

      <!-- Explicación -->
      <div class="mb-4 textarea-box">
        <div class="etiqueta-superior">
          <label for="explicacion" class="m-0 text-white fw-semibold fs-5">Explicación del caso:</label>
          <i class="bi bi-clipboard-fill clipboard-icon text-white"></i>
        </div>
        <textarea id="explicacion" class="form-control fs-4" rows="6" placeholder="Escriba aquí..."></textarea>
      </div>

      <!-- Botones -->
      <div class="d-flex justify-content-between">
        <div>
          <button type="reset" id="btn-cancelar" class="btn btn-dark px-4 shadow fs-5 me-3"><i class="bi bi-x-lg"></i> Borrar</button>
          <button type="button" id="btn-editar" class="btn btn-secondary px-4 shadow fs-5"><i class="bi bi-pencil"></i> Editar</button>
        </div>
        <button type="button" class="btn btn-guardar px-4 shadow fs-5" id="btnGuardar"><i class="bi bi-check-square"></i> Guardar</button>
      </div>
    </form>
  </div>

  <!-- Modal Confirmación -->
  <div class="modal fade" id="modalConfirmacion" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-exclamation-circle-fill me-2"></i>Advertencia</h5>
        </div>
        <div class="modal-body fs-5">
          ¿Está seguro de realizar esta asistencia rápida?<br>
          <strong>Una vez realizada no se podrá retirar la acción.</strong>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary fs-5" id="confirmarAsistencia">Realizar asistencia</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Éxito -->
  <div class="modal fade" id="modalExito" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header justify-content-end border-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body pt-0">
          <p class=" mt-2 mb-0 fs-5">La asistencia se realizó con éxito.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script personalizado -->
  <script src="/AsistenciaRapida.js"></script>
</body>
</html>
