<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asistencia</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="Asistencia.css" />
</head>
<body>
  <div class="main-container d-flex">
    <!-- Panel izquierdo -->
    <div class="left-panel d-flex flex-column align-items-center">
      
      <!-- Perfil del estudiante -->
      <div class="card estudiante-card text-center mb-3">
        <img src="img/FotoEstudiante.webp" class="estudiante-avatar" alt="Avatar del estudiante" />
        <div class="card-body">
          <h5 class="card-title" id="nombreEstudiante">Nombre del estudiante</h5>
          <p class="card-text" id="cedulaEstudiante">Cédula</p>
        </div>
      </div>

      <!-- Tipo de beca -->
      <div class="card tipo-beca-card text-center">
        <div class="card-header fw-bold">Tipo de beca asignada</div>
        <div class="card-body">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="desayuno" id="becaDesayuno" disabled>
            <label class="form-check-label" for="becaDesayuno">Beca de Desayuno</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="almuerzo" id="becaAlmuerzo" disabled>
            <label class="form-check-label" for="becaAlmuerzo">Beca de Almuerzo</label>
          </div>
        </div>
        <img src="img/LogoCovao.webp" alt="COVAO logo" class="logo-covao" />
      </div>
    </div>

    <!-- Panel derecho -->
    <div class="right-panel card">
      <div class="card-body">
        <h5 class="mb-3 fw-bold">Seleccione un rango de fechas</h5>
        <div class="row mb-4">
          <div class="col">
            <label for="fechaInicio" class="form-label">Fecha de inicio</label>
            <input type="date" id="fechaInicio" class="form-control" />
          </div>
          <div class="col">
            <label for="fechaFinal" class="form-label">Fecha final</label>
            <input type="date" id="fechaFinal" class="form-control" />
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle" id="tablaAsistencia">
            <thead class="table-light">
              <tr id="encabezadoTabla">
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody id="cuerpoTabla"></tbody>
          </table>
        </div>

        <!-- Botones estilo personalizado -->
        <div class="d-flex justify-content-around mt-3">
          <button class="custom-btn" id="btnEditar">
            <span class="icon-section">
              <i class="bi bi-pencil-fill small-icon"></i>
            </span>
            <div class="divider"></div>
            <span class="text-section">Editar marca</span>
          </button>

          <button class="custom-btn" id="btnFinalizar">
            <span class="icon-section">
              <i class="bi bi-check-circle-fill"></i>
            </span>
            <div class="divider"></div>
            <span class="text-section">Finalizar</span>
          </button>
        </div>

      </div>
    </div>
  </div>

  <script src="Asistencia.js"></script>
</body>
</html>

