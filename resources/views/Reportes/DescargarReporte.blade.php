<!DOCTYPE html>
<html lang="es">
<!---------------------------------------------------------LINKS----------------------------------------------------------->

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SICAB</title>

    <!-- Bootstrap, iconos, datepicker y CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="Stylesheet" href="{{ asset('css/DescargarReporte.css') }}">

    <!-- Iconos de la página -->
    <link rel="icon" href="../img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="../img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
  </head>

<!------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------NAVBAR---------------------------------------------------------->

    <button id="btn-Menu" class="btn ms-3 mb-3 fs-5 py-3" data-bs-toggle="offcanvas" 
      data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
      <i class="fa-solid fa-bars fa-xl" style="color: #f7f7f7;"></i>
    </button>
        
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
      <div class="offcanvas-header justify-content-end">
          <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Close"> 
            <i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i> 
          </button>
      </div>
      
      <div class="offcanvas-body mt-5">
          <div class="d-grid gap-3">
            <a href="/HTML/Home.html">
              <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                  <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu"></i>
                  | Inicio
              </button>
            </a>
            <a href="/HTML/IngresoComedor.html">
              <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                  <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i>
                  | Ingreso al comedor
              </button>
            </a>
            <a href="/HTML/AñadirEstudiantesM.html">
              <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                  <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i>
                  | Añadir estudiantes
              </button>
            </a>
            <a href="/HTML/InformacionEstudiante.html">
              <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                  <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i>
                  | Información de Estudiantes
              </button>
            </a>
            <a href="#">
              <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                  <i class="fa-solid fa-download fa-lg" id="icono-menu"></i>
                  | Descargar reporte
              </button>
            </a>
            <a href="/HTML/TipoBeca.html">
              <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                  <i class="fa-solid fa-hand-holding-medical fa-lg" id="icono-menu"></i>
                  | Becas
              </button>
            </a>
            <a href="/HTML/AsistenciaRapida.html">
              <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                  <i class="fa-solid fa-star-half-stroke fa-lg" id="icono-menu"></i>
                  | Asistencia rápida
              </button>
            </a>
          </div>
      </div>

        <div class="offcanvas-footer p-3 border-top">
          <button id="btn-opcionF" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
              <i class="fa-solid fa-arrow-right-to-bracket fa-lg" id="icono-menu"></i>
              | Cerrar sesión
          </button>
        </div>
    </div>

<!------------------------------------------------------------------------------------------------------------------------->
<!-----------------------------------------------------CONTENIDO----------------------------------------------------------->

<body class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1">

    <div class="main-wrapper">
      <div class="cuadro-principal">
        <div class="titulo-reporte mt-3">Reporte de Asistencia</div>
        <div class="d-flex flex-wrap gap-4 justify-content-between align-items-start">

          <!-- opciones -->
          <div class="sidebar">
            <label for="tipoReporte" class="form-label fs-5">Tipo de reporte</label>
            <select class="form-select mb-3 fs-5" id="tipoReporte">
              <option>Semanal</option>
              <option selected>Mensual</option>
              <option>Anual</option>
            </select>

            <label for="tipoBeca" class="form-label fs-5">Tipo de beca</label>
            <select class="form-select mb-3 fs-5" id="tipoBeca">
              <option>Desayuno</option>
              <option selected>Almuerzo</option>
              <option>Desayuno / Almuerzo</option>
            </select>

            <!-- Sección para fecha -->
            <div class="p-3 mb-5 mt-5 bg-light rounded shadow-sm">
              <label for="fecha" class="form-label fw-bold">Seleccione la fecha a consultar:</label>
              <div class="input-group">
                <input type="date" id="fecha" class="form-control fs-5" placeholder="dd/mm/aaaa" aria-describedby="fechaHelp">
                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
              </div>
              <!-- Mensaje de error -->
              <div class="mensaje-error mt-2 text-danger d-none" id="errorFecha">
                <i class="bi bi-exclamation-circle-fill me-1 fs-5"></i>Seleccione una fecha
              </div>
            </div>

            <!-- Botón para realizar la búsqueda -->
            <button id="btnBuscar" class="btn btn-dark w-100 mb-2 fs-5">
              <i class="bi bi-search me-1"></i>| Buscar
            </button>
            <!-- Input para búsqueda por nombre -->
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre...">

            <!-- Dropdown para opciones de descarga -->
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

          <!-- Contenedor principal -->
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <button class="btn-volver"><i class="bi bi-arrow-left"></i></button>
                <span class="fw-bold fs-5">Asistencias - 5/02/2025</span>
              </div>
              <img src="../img/LogoCovao.webp" class="logo" alt="COVAO">
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
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>104350654</td>
                      <td>Gustavo Gutierrez</td>
                      <td>Ausente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>305670954</td>
                      <td>Felipe Navarro</td>
                      <td>Presente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>104350654</td>
                      <td>Gustavo Gutierrez</td>
                      <td>Ausente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>305670954</td>
                      <td>Felipe Navarro</td>
                      <td>Presente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>104350654</td>
                      <td>Gustavo Gutierrez</td>
                      <td>Ausente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>305670954</td>
                      <td>Felipe Navarro</td>
                      <td>Presente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>104350654</td>
                      <td>Gustavo Gutierrez</td>
                      <td>Ausente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>305670954</td>
                      <td>Felipe Navarro</td>
                      <td>Presente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>104350654</td>
                      <td>Gustavo Gutierrez</td>
                      <td>Ausente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>305670954</td>
                      <td>Felipe Navarro</td>
                      <td>Presente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>104350654</td>
                      <td>Gustavo Gutierrez</td>
                      <td>Ausente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>305670954</td>
                      <td>Felipe Navarro</td>
                      <td>Presente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                    <tr>
                      <td>104350654</td>
                      <td>Gustavo Gutierrez</td>
                      <td>Ausente</td>
                      <td><a href="/HTML/InformacionEstudiante.html"><button class="btn-ver px-3">Ver Estudiante</button></a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>
  
<!------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------FOOTER------------------------------------------------------------>   

  <footer id="DivFooter" class="text-dark py-3 mt-auto">
    <div class="container">
      <div class="row">
        <div class="col-12 d-flex justify-content-center text-center">
          <p id="footerText" class="mb-0">Copyright ©2025 Especialidad Desarrollo Web | COVAO.</p>
        </div>
      </div>
    </div>
  </footer>

<!------------------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------------SCRIPTS------------------------------------------------------------>

  <!-- Bootstrap, iconos, JS y datepicker -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.es.min.js"></script>
  <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>
  <script src="AñadirEstudiantesM.js"></script>

<!------------------------------------------------------------------------------------------------------------------------->
</body>
</html>