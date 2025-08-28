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

  <!-- Botón y menú lateral -->
  <button id="btn-Menu" class="btn ms-3 mb-3 fs-5 py-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" aria-label="Abrir menú">
    <i class="fa-solid fa-bars fa-xl" style="color: #f7f7f7;"></i>
  </button>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header justify-content-end">
      <button type="button" class="btn" data-bs-dismiss="offcanvas" aria-label="Cerrar menú">
        <i class="fa-solid fa-xmark fa-2xl" style="color: #f7f7f7;"></i>
      </button>
    </div>
    <div class="offcanvas-body">
      <div class="d-grid gap-3">
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas" onclick="window.location.href='{{ route('admin.home') }}'">
          <i class="fa-solid fa-house-chimney fa-lg" id="icono-menu"></i> | Home
        </button>
      </div>
    </div>
    <div class="offcanvas-footer p-3 border-top">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button id="btn-opcion" class="btn btn-outline-light fs-5" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-arrow-right-to-bracket fa-lg" id="icono-menu"></i> | Cerrar sesión
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

        <!-- Formulario (8 columnas) -->
        <div class="col-md-8">
          <h1 class="fw-bold color4 mb-4 ps-3">Crear Estudiante</h1>

          <form action="{{ route('estudiantes.store') }}" method="POST" enctype="multipart/form-data" id="form-estudiante">
            @csrf

            <!-- Select rol -->
            <div class="mb-4 row align-items-center">
              <label for="rol" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Rol</strong></label>
              <div class="col-sm-9">
                <select id="rol" name="rol" class="form-select inputColor fs-5" required>
                  <option value="" selected disabled>Seleccione un rol</option>
                  @foreach ($roles as $rol)
                    <option value="{{ $rol->name }}">{{ ucfirst($rol->name) }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Campos comunes (nombre, cedula) -->
            <div id="campos-comunes" style="display:none;">
              <div class="mb-4 row align-items-center">
                <label for="nombre" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Nombre completo</strong></label>
                <div class="col-sm-9">
                  <input type="text" id="nombre" name="nombre" class="form-input-flex inputColor fs-5" placeholder="Ingrese nombre completo">
                </div>
              </div>

              <div class="mb-4 row align-items-center">
                <label for="cedula" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Cédula</strong></label>
                <div class="col-sm-9">
                  <input type="text" id="cedula" name="cedula" class="form-input-flex inputColor fs-5" placeholder="Ingrese la cédula">
                </div>
              </div>
            </div>

            <!-- Campo correo (solo para roles que no sean estudiante) -->
            <div id="campo-correo" style="display:none;" class="mb-4 row align-items-center">
              <label for="correo" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Correo electrónico</strong></label>
              <div class="col-sm-9">
                <input type="email" id="correo" name="correo" class="form-input-flex inputColor fs-5" placeholder="Ingrese correo electrónico">
              </div>
            </div>

            <!-- Campos estudiante -->
            <div id="campos-estudiante" style="display:none;">
              <div class="mb-4 row align-items-center">
                <label for="seccion" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Sección</strong></label>
                <div class="col-sm-9">
                  <input type="text" id="seccion" name="seccion" class="form-input-flex inputColor fs-5" placeholder="Ingrese la sección">
                </div>
              </div>

              <!-- Especialidad (restaurada) -->
              <div class="mb-4 row align-items-center">
                <label for="especialidad" class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Especialidad</strong></label>
                <div class="col-sm-9">
                  <input type="text" id="especialidad" name="especialidad" class="form-input-flex inputColor fs-5" placeholder="Ingrese la especialidad">
                </div>
              </div>

              <!-- Becas (multi-selección con checkboxes) -->
              <div class="mb-4 row align-items-start">
                <label class="col-sm-3 col-form-label text-end color1 fs-5"><strong>Tipo de beca</strong></label>
                <div class="col-sm-9">
                  <div class="border rounded p-3" style="max-height: 220px; overflow-y: auto;">
                    <div class="row">
                      @foreach ($tiposBeca as $beca)
                        <div class="col-md-6">
                          <div class="form-check">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              name="tipo_beca_id[]"
                              value="{{ $beca->id }}"
                              id="beca_{{ $beca->id }}"
                            >
                            <label class="form-check-label" for="beca_{{ $beca->id }}">
                              {{ $beca->propiedade->nombre }}
                            </label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                  <small class="text-muted">Puede seleccionar una o varias becas.</small>
                </div>
              </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-start gap-3 mt-4 pe-5 ms-5">
              <button type="submit" class="btnPrimario fs-5 ms-5">
                <i class="bi bi-save2-fill"></i> Guardar
              </button>
              <button type="reset" class="btnPrimario fs-5 ms-5" onclick="window.location='{{ route('estudiantes.importar.form') }}'">
                <i class="bi bi-x-circle-fill"></i> Cancelar
              </button>
            </div>

            <!-- Input oculto para la foto -->
            <input type="file" id="foto" name="foto" accept="image/*" hidden />
          </form>
        </div>

        <!-- Foto (4 columnas) alineada verticalmente -->
        <div class="col-md-4 d-flex align-items-center" id="foto-wrapper" style="display:none;">
          <div class="foto-estudiante-wrapper">
            <img
              id="previewFoto"
              src="{{ asset('img/FotoEstudiante.webp') }}"
              alt="Previsualización Foto"
              class="foto-estudiante"
            />
            <button
              class="btn-agregar-foto"
              type="button"
              title="Agregar imagen"
              onclick="document.getElementById('foto').click()"
            >
              <i class="bi bi-plus fs-4"></i>
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

<script>
  const rolSelect = document.getElementById('rol');
  const camposComunes = document.getElementById('campos-comunes');
  const camposEstudiante = document.getElementById('campos-estudiante');
  const fotoWrapper = document.getElementById('foto-wrapper');
  const campoCorreo = document.getElementById('campo-correo');

  function actualizarCampos() {
    const rol = rolSelect.value?.toLowerCase() || '';

    // Ocultar todo por defecto usando !important
    camposComunes.style.setProperty('display', 'none', 'important');
    camposEstudiante.style.setProperty('display', 'none', 'important');
    fotoWrapper.style.setProperty('display', 'none', 'important');
    campoCorreo.style.setProperty('display', 'none', 'important');

    // Resetear required (solo inputs que existen por ID)
    ['nombre', 'cedula', 'correo', 'seccion', 'especialidad', 'foto'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.required = false;
    });

    if (!rol) return;

    // Campos comunes visibles
    camposComunes.style.setProperty('display', 'block', 'important');
    ['nombre', 'cedula'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.required = true;
    });

    if (rol === 'estudiante') {
      camposEstudiante.style.setProperty('display', 'block', 'important');
      fotoWrapper.style.setProperty('display', 'flex', 'important');
      campoCorreo.style.setProperty('display', 'none', 'important');

      ['seccion', 'especialidad'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.required = true;
      });

    } else {
      // Para otros roles
      campoCorreo.style.setProperty('display', 'flex', 'important');
      const correo = document.getElementById('correo');
      if (correo) correo.required = true;
    }
  }

  // Ejecutar al cambiar rol
  rolSelect.addEventListener('change', actualizarCampos);

  // Ejecutar al cargar la página
  window.addEventListener('DOMContentLoaded', actualizarCampos);

  // Previsualización de foto
  document.getElementById('foto').addEventListener('change', function() {
    const [file] = this.files;
    if (file) {
      document.getElementById('previewFoto').src = URL.createObjectURL(file);
    }
  });
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script src="{{ asset('js/AnadirEstudiante.js') }}"></script>

</body>
</html>
