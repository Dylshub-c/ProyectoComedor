<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asistencia</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="{{ asset('css/EstiloRevisarAs.css') }}">
</head>
<body>
@if(!$persona)
  <div class="container mt-4">
    <div class="alert alert-warning text-center">
      No hay estudiante seleccionado.  
      <a href="{{ route('estudiantes.informacion') }}" class="btn btn-link">Volver</a>
    </div>
  </div>
@else
<div class="main-container d-flex">

  <!-- Panel izquierdo -->
  <div class="left-panel d-flex flex-column align-items-center">

    <!-- Perfil del estudiante -->
    <div class="card estudiante-card text-center mb-3">
      <img src="{{ asset($persona->estudiante->foto ?? 'img/FotoEstudiante.webp') }}"
           class="estudiante-avatar"
           alt="Avatar del estudiante" />
      <div class="card-body">
        <h5 class="card-title">
          {{ $persona->Nombre }} {{ $persona->PrimerApellido }} {{ $persona->SegundoApellido }}
        </h5>
        <p class="card-text">{{ $persona->Cedula }}</p>
      </div>
    </div>

    <!-- Formulario para crear/editar asistencia -->
    <form action="{{ route('asistencia.guardar') }}" method="POST">
      @csrf
      <input type="hidden" name="estudiante_id" value="{{ $persona->estudiante->id }}">

      <div class="card tipo-beca-card text-center mb-3">
        <div class="card-header fw-bold">Tipo de beca asignada</div>
        <div class="card-body">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tipo_asistencia[]" value="desayuno" id="checkDesayuno"
              @if(in_array($persona->estudiante->tipoBeca->propiedade->nombre, ['Desayuno', 'Desayuno - Almuerzo'])) @else disabled @endif>
            <label class="form-check-label" for="checkDesayuno">Desayuno</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tipo_asistencia[]" value="almuerzo" id="checkAlmuerzo"
              @if(in_array($persona->estudiante->tipoBeca->propiedade->nombre, ['Almuerzo', 'Desayuno - Almuerzo'])) @else disabled @endif>
            <label class="form-check-label" for="checkAlmuerzo">Almuerzo</label>
          </div>
        </div>

        <div class="col mt-3">
          <label for="fechaAsistencia" class="form-label fw-bold fs-5">Fecha de la Asistencia:</label>
          <input type="date" name="fecha_hora" id="fechaAsistencia" class="form-control" required>
        </div>

        <div class="col mt-3">
          <label for="estadoAsistencia" class="form-label fw-bold fs-5">Marcar asistencia como:</label>
          <select id="estadoAsistencia" name="estado" class="form-select beca-select fs-5" required>
            <option selected disabled value="">Seleccione una opción</option>
            <option value="presente">Presente</option>
            <option value="ausente">Ausente</option>
          </select>
        </div>

        <button type="submit" class="custom-btn mt-3">
          <span class="icon-section">
            <i class="bi bi-pencil-fill small-icon"></i>
          </span>
          <div class="divider"></div>
          <span class="text-section">Editar o crear marca </span>
        </button>
      </div>
    </form>

  </div>

  <!-- Panel derecho: lista de asistencias filtrable por mes -->
  <div class="right-panel card flex-grow-1">
    <div class="card-body">
      <h5 class="mb-3 fw-bold">Mes a solicitar</h5>
      <div class="row mb-4">
        <div class="col">
          <label for="fechaInicio" class="form-label">Mes</label>
          <input type="month" id="fechaInicio" class="form-control">
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="table-light">
            <tr>
              <th>Fecha</th>
              <th>Tipo</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($listados) && $listados->count())
              @foreach($listados as $listado)
                <tr data-tipo="{{ strtolower($listado->asistencia->tipo_asistencia) }}"
                    data-fecha="{{ \Carbon\Carbon::parse($listado->asistencia->fecha_hora)->format('Y-m') }}">
                  <td>{{ \Carbon\Carbon::parse($listado->asistencia->fecha_hora)->format('d/m/Y') }}</td>
                  <td>{{ ucfirst($listado->asistencia->tipo_asistencia) }}</td>
                  <td>{{ ucfirst($listado->asistencia->estado) }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" class="text-muted">No hay registros de asistencia.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
  const checkDesayuno = document.getElementById('checkDesayuno');
  const checkAlmuerzo = document.getElementById('checkAlmuerzo');
  const fechaInput = document.getElementById('fechaInicio');
  const tableRows = document.querySelectorAll('table tbody tr');

  function filtrarTabla() {
    const mostrarDesayuno = checkDesayuno.checked;
    const mostrarAlmuerzo = checkAlmuerzo.checked;
    const mesSeleccionado = fechaInput.value; // formato "YYYY-MM"

    tableRows.forEach(row => {
      const tipo = row.getAttribute('data-tipo');
      const filaMes = row.getAttribute('data-fecha');

      const tipoValido = (!mostrarDesayuno && !mostrarAlmuerzo) ||
                         (tipo === 'desayuno' && mostrarDesayuno) ||
                         (tipo === 'almuerzo' && mostrarAlmuerzo);

      const mesValido = !mesSeleccionado || filaMes === mesSeleccionado;

      row.style.display = (tipoValido && mesValido) ? '' : 'none';
    });
  }

  checkDesayuno.addEventListener('change', filtrarTabla);
  checkAlmuerzo.addEventListener('change', filtrarTabla);
  fechaInput.addEventListener('change', filtrarTabla);
});
</script>

</body>
</html>
