<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Editar Rol</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/InformacionEstudiante.css') }}">
  <link rel="stylesheet" href="{{ asset('css/MenuLateral.css') }}" type="text/css" />
  <style>
    body {
      background: url('{{ asset('img/FondoPrincipal.webp') }}') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      color: #333;
    }
    .card {
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    label.form-check-label {
      user-select: none;
    }
    .form-check-input:checked {
      background-color: #4e73df;
      border-color: #4e73df;
    }
  </style>
</head>
<body>

  <div class="container py-5">
    <div class="mx-auto" style="max-width: 700px;">
      <div class="card p-4">
        <h2 class="fw-bold text-center mb-4 color1">Editar Rol</h2>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="mb-0">
          @csrf
          @method('PUT')

          <div class="mb-4">
            <label for="name" class="form-label fw-semibold">Nombre del Rol</label>
            <input
              type="text"
              id="name"
              name="name"
              class="form-control form-control-lg"
              value="{{ old('name', $role->name) }}"
              placeholder="Ingrese el nombre del rol"
              required
            >
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Permisos</label>
            <div class="row g-3" style="max-height: 700px; overflow-y: auto; padding-right: 10px;">
              @foreach ($permissions as $permission)
                <div class="col-6 col-md-4">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="permissions[]"
                      id="perm_{{ $permission->id }}"
                      value="{{ $permission->name }}"
                      {{ (is_array(old('permissions', $rolePermissions)) && in_array($permission->name, old('permissions', $rolePermissions))) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                      {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btnPrimario btn-lg px-4 fw-semibold">
              <i class="bi bi-save2 me-2"></i> Guardar Cambios
            </button>
            <a href="{{ route('roles.index') }}" class="btn btnPrimario btn-lg px-4">
              <i class="bi bi-x-circle me-2"></i> Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
