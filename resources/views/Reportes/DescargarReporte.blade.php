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
    <link rel="Stylesheet" href="{{ asset('css/descargarReporte.css') }}">
     <link rel="Stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />

    <!-- Iconos de la página -->
    <link rel="icon" href="../img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="../img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
  </head>

<!------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------NAVBAR---------------------------------------------------------->
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
<!------------------------------------------------------------------------------------------------------------------------->
<!-----------------------------------------------------CONTENIDO----------------------------------------------------------->
<body class="d-flex flex-column min-vh-100 bg-light">
  <main class="flex-grow-1">
    <div class="container py-5">

      <div class="row justify-content-center">
        <div class="col-lg-6">

          <!-- Card principal -->
          <div class="card shadow-sm p-4">

            <!-- Título dentro del recuadro blanco -->
            <h2 class="text-center fw-bold mb-4">Reporte de Asistencia</h2>

            <!-- Selección de fecha -->
            <label for="fecha" class="form-label fw-bold">Seleccione la fecha a consultar:</label>
            <div class="input-group mb-3">
              <input type="month" id="fecha" name="fecha" class="form-control fs-5">
              <span class="input-group-text"><i class="bi bi-calendar"></i></span>
            </div>

            <!-- Mensaje de error -->
            <div class="text-danger d-none" id="errorFecha">
              <i class="bi bi-exclamation-circle-fill me-1"></i>Seleccione una fecha
            </div>

            <!-- Búsqueda por nombre -->
            <input type="text" id="searchInput" class="form-control mt-4" placeholder="Buscar por nombre...">

            <!-- Botón para descargar PDF -->
            <button id="pdfButton" class="btn btn-secondary w-100 fs-5 mt-4" disabled>
              <i class="bi bi-download me-1"></i> Descargar PDF
            </button>

            <!-- Mensaje informativo -->
            <p class="text-center text-muted mt-4 mb-0">
              Seleccione la fecha para ver y descargar el reporte.
            </p>

          </div>

        </div>
      </div>

    </div>
  </main>

</body>





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

  <script>
    const fechaInput = document.getElementById("fecha");
    const pdfButton = document.getElementById("pdfButton");

    // Actualiza el link y habilita el botón según la fecha seleccionada
    fechaInput.addEventListener("change", function() {
      const fecha = this.value;
      if (fecha) {
        pdfButton.disabled = false;
        pdfButton.onclick = () => {
          window.location.href = `/reporte/asistencia/pdf?fecha=${fecha}`;
        };
      } else {
        pdfButton.disabled = true;
        pdfButton.onclick = null;
      }
    });
  </script>

<!------------------------------------------------------------------------------------------------------------------------->
</body>
</html>
