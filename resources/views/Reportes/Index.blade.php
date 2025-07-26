<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Asistencia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link rel="stylesheet" href="descargarReporte.css">
</head>
<body>
  <div class="main-wrapper">
    <div class="cuadro-principal">
      <div class="titulo-reporte">Reporte de Asistencia</div>
      <div class="d-flex flex-wrap gap-4 justify-content-between align-items-start">

        <!-- Panel lateral -->
        <div class="sidebar">
          <label for="tipoReporte" class="form-label fs-5">Tipo de reporte</label>
          <select class="form-select mb-3 fs-5" id="tipoReporte">
            <option>Semanal</option>
            <option selected>Mensual</option>
            <option>Anual</option>
          </select>

          <div class="p-3 mb-4 bg-light rounded shadow-sm">
            <label for="fecha" class="form-label fw-bold">Seleccione la fecha de inicio:</label>
            <div class="input-group">
              <input type="date" id="fecha" class="form-control fs-5" placeholder="dd/mm/aaaa" aria-describedby="fechaHelp">
              <span class="input-group-text"><i class="bi bi-calendar"></i></span>
            </div>
            <div class="mensaje-error mt-2 text-danger d-none" id="errorFecha">
              <i class="bi bi-exclamation-circle-fill me-1 fs-5"></i>Seleccione una fecha
            </div>
          </div>

          <div class="p-3 mb-4 bg-light rounded shadow-sm">
            <label for="fecha" class="form-label fw-bold">Seleccione la fecha de finalización:</label>
            <div class="input-group">
              <input type="date" id="fecha" class="form-control fs-5" placeholder="dd/mm/aaaa" aria-describedby="fechaHelp">
              <span class="input-group-text"><i class="bi bi-calendar"></i></span>
            </div>
            <div class="mensaje-error mt-2 text-danger d-none" id="errorFecha">
              <i class="bi bi-exclamation-circle-fill me-1 fs-5"></i>Seleccione una fecha
            </div>
          </div>

          <button id="btnBuscar" class="btn btn-dark w-100 mb-2 fs-5"><i class="bi bi-search me-1"></i>| Buscar</button>
          <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre...">

          <div class="dropdown w-100 mt-3">
            <button class="btn btn-descargar dropdown-toggle w-100 fs-5" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-download ms-1"></i>
              | Descargar
            </button>
            <ul class="dropdown-menu w-100 fs-5">
              <li><a class="dropdown-item" href="#">PDF</a></li>
              <li><a class="dropdown-item" href="#">Excel</a></li>
            </ul>
          </div>
        </div>

        <!-- Tabla de asistencias -->
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <button class="btn-volver"><i class="bi bi-arrow-left"></i></button>
              <span class="fw-bold fs-5">Asistencias - 5/02/2025</span>
            </div>
            <img src="img/LogoCovao.webp" class="logo" alt="COVAO">
          </div>

          <div class="tabla-scroll">
            <div class="overflow-auto">
            <table class="table table-bordered align-middle text-center mb-0" id="tablaEstudiantes">
              <thead class="table-light fs-5" style="background-color: #E9EBED;">
                <tr>
                  <th>Cédula</th>
                  <th>Nombre</th>
                  <th>Asistencia</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>305670954</td>
                  <td>Felipe Navarro</td>
                  <td>Presente</td>
                  <td><button class="btn-ver px-3">Ver Estudiante</button></td>
                </tr>
                <tr>
                  <td>104350654</td>
                  <td>Gustavo Gutierrez</td>
                  <td>Ausente</td>
                  <td><button class="btn-ver px-3">Ver Estudiante</button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.es.min.js"></script>
</body>
</html>
