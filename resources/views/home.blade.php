<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Orientadora</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/Home.css') }}" />
</head>
<body class="p-4">

  <!-- HEADER -->
  <div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 main-header gap-3">
      
      <!-- Información de usuario y logo -->
      <div class="header d-flex flex-column flex-md-row align-items-center justify-content-between shadow-sm w-100 gap-3">
        <div class="user-info d-flex align-items-center gap-2">
          <div class="icon-box">
            <i class="bi bi-person-circle"></i>
          </div>
          <span id="nombre-orientadora" class="fw-bold">{{ auth()->user()->persona->Nombre }}</span>
        </div>
        <img class="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo">
      </div>

      <!-- Botón Logout con Modal -->
      <div class="header2 d-flex align-items-center justify-content-center shadow-sm">
        <button class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#logoutModal">
          <i class="bi bi-box-arrow-right fs-2"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- TARJETAS -->
  <div class="container-fluid2">
    <div class="row g-4">
      <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card card-fondo text-white text-center p-4 mt-2">
          <div class="card-overlay"></div>
          <div class="card-content text-start">
            <i class="bi bi-house-door-fill card-icon"></i>
            Ingreso al Comedor
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card card-fondo text-white text-center p-4 mt-2">
          <div class="card-overlay"></div>
          <div class="card-contentinfo text-start">
            <i class="bi bi-people-fill card-icon"></i>
            Información de estudiantes
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card card-fondo text-white text-center p-4 mt-2">
          <div class="card-overlay"></div>
          <div class="card-contenttipos text-start">
            <i class="bi bi-award-fill card-icon"></i>
            Tipos de beca
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 col-lg-4">
        <a href="{{ route('estudiantes.importar.form') }}" class="text-decoration-none">
          <div class="card card-fondo text-white text-center p-4 mt-2 h-100">
            <div class="card-overlay"></div>
            <div class="card-content text-start">
              <i class="bi bi-person-plus-fill card-icon"></i>
              Agregar Estudiantes
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card card-fondo text-white text-center p-4 mt-2">
          <div class="card-overlay"></div>
          <div class="card-content text-start">
            <i class="bi bi-file-earmark-arrow-down-fill card-icon"></i>
            Descargar Reportes
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card card-fondo text-white text-center p-4 mt-2">
          <div class="card-overlay"></div>
          <div class="card-content text-start">
            <i class="bi bi-check2-square card-icon"></i>
            Asistencias rápidas
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL DE LOGOUT PERSONALIZADO -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-estilo">
        <div class="modal-header modal-header-estilo">
          <h5 class="modal-title text-white">
            <i class="bi bi-exclamation-circle-fill me-2"></i>Advertencia
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center fw-bold fs-5 py-4">
          ¿Está seguro que desea cerrar sesión?
        </div>
        <div class="modal-footer d-flex justify-content-center gap-3 border-0 pb-4">
          <button type="button" class="btn btn-modal-cancelar" data-bs-dismiss="modal">Cancelar</button>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-modal-confirmar">Cerrar Sesión</button>
          </form>
          
        </div>
      </div>
    </div>
  </div>

  <!-- SCRIPTS BOOTSTRAP -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
