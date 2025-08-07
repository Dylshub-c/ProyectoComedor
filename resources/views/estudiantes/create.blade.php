<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Crear Estudiante</title>
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
                <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
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
        <img id="fotoEstudiante" src="{{ asset('img/FotoEstudiante.webp') }}" alt="foto">
        <span class="fw-bold fs-3">{{ auth()->user()->persona->Nombre }}</span>
        <div class="ms-auto">
          <img class="py-2" id="logo" src="{{ asset('img/LogoCovao.webp') }}" alt="Logo"/>
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
        <!-- Columna izquierda -->
        <div class="col-md-8">
          <h1 class="fw-bold color4 mb-4 ps-3">Crear Estudiante</h1>

          <form action="{{ route('estudiantes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4 row align-items-start">
              <label for="nombre" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Nombre</strong></label>
              <div class="col-sm-9">
                <input type="text" id="nombre" name="nombre" class="form-control customInput fs-5" placeholder="Ingrese nombre completo" required>
              </div>
            </div>

            <div class="mb-4 row align-items-start">
              <label for="cedula" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Cédula</strong></label>
              <div class="col-sm-9">
                <input type="text" id="cedula" name="cedula" class="form-control customInput fs-5" placeholder="Ingrese la cédula" required>
              </div>
            </div>

            <div class="mb-4 row align-items-start">
              <label for="seccion" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Sección</strong></label>
              <div class="col-sm-9">
                <input type="text" id="seccion" name="seccion" class="form-control customInput fs-5" placeholder="Ingrese la sección" required>
              </div>
            </div>

            <div class="mb-4 row align-items-start">
              <label for="especialidad" class="col-sm-3 col-form-label text-end color1 fs-5 text-end"><strong>Especialidad</strong></label>
              <div class="col-sm-9">
                <input type="text" id="especialidad" name="especialidad" class="form-control customInput fs-5" placeholder="Ingrese la especialidad" required>
              </div>
            </div>

            <div class="mb-2 row align-items-start">
              <label for="tipo_beca_id" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Tipo de beca</strong></label>
              <div class="col-sm-9">
                <select id="tipo_beca_id" name="tipo_beca_id" class="form-select customInput fs-5" required>
                  <option disabled selected value="">Seleccione una opción</option>
                  @foreach ($tiposBeca as $beca)
                    <option value="{{ $beca->id }}">{{ $beca->propiedade->nombre }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div id="mensajeError" class="text-danger ms-5 mt-1 d-none">
              <i class="bi bi-exclamation-circle-fill fs-5"></i> Llene todos los campos para continuar
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4 pe-5">
              <button type="submit" class="btnPrimario fs-5">
                <i class="bi bi-save2-fill"></i> Guardar
              </button>
              <button type="reset" class="btnPrimario fs-5" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                <i class="bi bi-x-circle-fill"></i> Cancelar
              </button>
            </div>
            <input type="file" id="foto" name="foto" accept="image/*" style="display:none" />
          </form>
        </div>

       <div class="col-md-4 text-center mb-4 position-relative">
        <div
            class="bg-avatar mx-auto position-relative rounded-circle overflow-hidden"
            style="width: 240px; height: 240px; border: 4px solid #007bff; background-color: #e9ecef; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
        >
            <!-- Imagen previa -->
            <img
            id="previewFoto"
            src="{{ asset('img/FotoEstudiante.webp') }}"
            alt="Previsualización Foto"
            class="rounded-circle w-100 h-100"
            style="object-fit: cover;"
            />
            <!-- Ícono persona oculto porque ya hay imagen -->
            <i class="bi bi-person-fill icono-avatar" style="display: none;"></i>

            <!-- Botón para agregar imagen -->
            <button
            class="btn btn-primary btn-lg"
            title="Agregar imagen"
            onclick="document.getElementById('foto').click()"
            style="
                position: absolute;
                bottom: 15px;
                right: 15px;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            "
            >
            <i class="bi bi-plus fs-3"></i>
            </button>

            <!-- Input oculto -->

        </div>
        </div>



      </div>
    </div>
  </div>
  <script>
    document.getElementById('foto').addEventListener('change', function(event) {
  const [file] = this.files;
  if (file) {
    const preview = document.getElementById('previewFoto');
    preview.src = URL.createObjectURL(file);
    // Ocultamos el icono persona porque ya mostramos la imagen
    const icono = document.querySelector('.icono-avatar');
    if(icono) icono.style.display = 'none';
  }
});
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script src="{{ asset('js/AnadirEstudiante.js') }}"></script>

</body>
</html>
