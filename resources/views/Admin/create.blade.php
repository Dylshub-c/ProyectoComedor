<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Crear Encargado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/AnadirEstudiante.css') }}">
  <link rel="stylesheet" href="{{ asset('css/MenuLateral.css') }}">
</head>
<body>

  <!-- Fondo -->
  <div class="position-fixed top-0 start-0 w-100 h-100 z-n1">
    <img src="{{ asset('img/FondoPrincipal.webp') }}" class="w-100 h-100" alt="Fondo">
  </div>

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
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('IngresoCom.IngresoComedor') }}'">
                    <i class="fa-solid fa-clipboard-list fa-lg" id="icono-menu"></i>
                    | Ingreso al comedor
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                    <i class="fa-solid fa-street-view fa-lg" id="icono-menu"></i>
                    | Agregar estudiantes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('estudiantes.informacion') }}'">
                    <i class="fa-solid fa-address-card fa-lg" id="icono-menu"></i>
                    | Ver lista de estudiantes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-download fa-lg" id="icono-menu"></i>
                    | Descargar reportes
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-calendar-check fa-lg" id="icono-menu"></i>
                    | Gestionar asistencias
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location='{{ route('tipobeca.index') }}'">
                    <i class="fa-solid fa-hand-holding-medical fa-lg" id="icono-menu"></i>
                    | Becas
                </button>
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
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

  <!-- Header -->
  <div class="container-fluid mb-5">
    <div class="row d-flex">
      <div class="header d-flex align-items-center gap-3 shadow-sm px-4">
        <span class="fw-bold fs-3">{{ auth()->user()->persona->Nombre }}</span>
        <div class="ms-auto">
          <img class="py-2" id="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo" />
        </div>
      </div>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Formulario -->
  <div class="container-fluid h-100 overflow-hidden d-flex justify-content-center align-items-center p-5 pt-0 mb-4">
    <div class="card rounded-4 shadow p-4 w-100">
      <div class="row g-3 align-items-center">
        <div class="col-md-8">
          <h1 class="fw-bold color4 mb-4 ps-3">Crear Encargado</h1>

          <form action="{{ route('encargados.store') }}" method="POST">
            @csrf

            <div class="mb-4 row align-items-start">
              <label for="Nombre" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Nombre</strong></label>
              <div class="col-sm-9">
                <input type="text" id="Nombre" name="nombre" class="form-control customInput fs-5" placeholder="Ingrese nombre completo" required>
              </div>
            </div>

            <div class="mb-4 row align-items-start">
              <label for="PrimerApellido" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Primer Apellido</strong></label>
              <div class="col-sm-9">
                <input type="text" id="PrimerApellido" name="PrimerApellido" class="form-control customInput fs-5" placeholder="Ingrese primer apellido" required>
              </div>
            </div>

            <div class="mb-4 row align-items-start">
              <label for="SegundoApellido" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Segundo Apellido</strong></label>
              <div class="col-sm-9">
                <input type="text" id="SegundoApellido" name="SegundoApellido" class="form-control customInput fs-5" placeholder="Ingrese segundo apellido">
              </div>
            </div>

            <div class="mb-4 row align-items-start">
              <label for="Cedula" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Cédula</strong></label>
              <div class="col-sm-9">
                <input type="text" id="Cedula" name="cedula" class="form-control customInput fs-5" placeholder="Ingrese la cédula" required>
              </div>
            </div>

            <div class="mb-4 row align-items-start">
              <label for="Correo" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Correo electrónico</strong></label>
              <div class="col-sm-9">
                <input type="email" id="Correo" name="email" class="form-control customInput fs-5" placeholder="Ingrese correo electrónico" required>
              </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4 pe-5">
              <button type="submit" class="btnPrimario fs-5">
                <i class="bi bi-save2-fill"></i> Guardar
              </button>
              <button type="reset" class="btnPrimario fs-5" onclick="window.location='{{ route('encargados.index') }}'">
                <i class="bi bi-x-circle-fill"></i> Cancelar
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>
