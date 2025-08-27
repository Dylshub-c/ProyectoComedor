<!DOCTYPE html> 
<html lang="es">

<!---------------------------------------------------------LINKS----------------------------------------------------------->

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.17/locales/es.global.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="Stylesheet" href="{{ asset('css/IngresoComedor.css') }}">
        <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
        <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
        <title>Desarrollo Web</title>
    </head>

<!------------------------------------------------------------------------------------------------------------------------->

<body id="fondo" class="d-flex flex-column min-vh-100">

<!------------------------------------------------------------------------------------------------------------------------->
<!-----------------------------------------------------CONTENIDO-----------------------------------------------------------> 

    <main class="flex-grow-1">
    <div class="container-fluid">
      @if(session('error'))
    <div class="alert alert-danger fs-5">
        {{ session('error') }}
    </div>
@endif
    <div class="row">
        <div class="col-3 mb-4 mt-5">
          <select class="form-select mb-3 fs-5" id="TipoAsistencia" name="tipo_asistencia">
              <option value="desayuno">Desayuno</option>
              <option value="almuerzo">Almuerzo</option>
          </select>

            <div id="PrimerModulo" class="container-fluid text-center mt-5">
              @php
    $foto = isset($persona) && $persona?->estudiante?->foto
        ? asset('storage/' . $persona->estudiante->foto)
        : asset('/img/FotoEstudiante.webp');
@endphp

<img class="img-fluid" id="fotoEstudiante" src="{{ $foto }}" alt="Foto del estudiante">

  
     <label id="NomEstudiante" class=" mt-1 text-center fs-4" for="">
    <strong>
    {{ isset($persona) ? "{$persona->Nombre} {$persona->PrimerApellido} {$persona->SegundoApellido}" : 'Nombre del estudiante' }}
</strong>

</label>

                <ul id="ul-Estudiante" class="list-group mt-5">
                    <li id="li-Estudiante" class="list-group-item">
                        <strong class="fs-5">Cédula:</strong><br />
                        <span id="cedula">{{ $persona?->Cedula ?? '-' }}</span>
                    </li>
                    <li id="li-Estudiante" class="list-group-item">
                        <strong class="fs-5">Especialidad:</strong><br />
                        <span id="especialidad">{{ $persona?->estudiante?->especialidade?->propiedade?->nombre ?? '-' }}</span>
                    </li>
                    <li id="li-Estudiante" class="list-group-item">
                        <strong class="fs-5">Tipo de beca:</strong><br />
                        <span id="tipo-beca">{{ $persona?->estudiante?->tipoBeca?->propiedade?->nombre ?? '-' }}</span>

                    </li>
                </ul>
            </div>
        </div>



                <div class="col-6 mb-4 mt-4">
                    <div id="SegundoModulo" class="container-fluid">

                        <div class="fs-5" id="calendar"></div>

                        <div id="popover">
                          <button id="btnAsistencia" title="Marcar asistencia" style="color:green;">
                            <i class="fa-solid fa-square-check"></i>
                          </button>
                          <button id="btnFalta" title="Marcar falta" style="color:red;">
                            <i class="fa-solid fa-square-xmark"></i>
                          </button>
                          <button id="btnEvento" title="Marcar evento" style="color:blue;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                          </button>
                        </div>

                        <div id="modalConfirmacion" class="modal-confirmacion ">
                          <div id="modal-content" class="modal-content">
                            <div class="modal-header mb-3 fs-5"><p>¡Advertencia!</p></div>
                            <p class="px-3 fs-5"> Se seleccionó una fecha diferente al día actual.</p>
                            <button id="btnAceptarModal" class="btn-aceptar fs-5">Aceptar</button>
                          </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">

                          <div>
                            <button type="button" id="finalizarAsistencia" class="btn mt-4 fs-5" data-bs-toggle="modal" data-bs-target="#modalBuscar">
                              <i class="fa-regular fa-address-card fa-lg me-1" style="color: #f7f7f7;"></i>
                              | Buscar por cédula
                            </button>
                            <form id="formBuscar" action="{{ route('comedor.buscar') }}" method="GET">
                            @csrf
                            <div class="modal fade" id="modalBuscar" tabindex="-1" aria-labelledby="modalBuscarLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title text-center fs-5" id="modalBuscarLabel">Búsqueda por cédula</h5>
                                  </div>
                                  <div class="modal-body">
                                    <div class="mb-3">
                                      <label for="cedulaEstudiante" class="form-label fs-5">Ingrese la cédula completa del estudiante:</label>
                                      <input type="text" class="form-control" name="cedula" id="cedulaEstudiante" >
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-cancelar fs-5" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-aceptar fs-5">Realizar búsqueda</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                          </div>

                          <div>
                            <button type="button" id="finalizarAsistencia" class="btn mt-4 fs-5" data-bs-toggle="modal" data-bs-target="#modalFinalizar">
                              <i class="fa-solid fa-check-to-slot fa-lg me-1" style="color: #f7f7f7;"></i>
                              | Finalizar asistencia
                            </button>
                            <div class="modal fade" id="modalFinalizar" tabindex="-1" aria-labelledby="modalFinalizarLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title text-center fs-5" id="modalFinalizarLabel">¡Advertencia!</h5>
                                  </div>
                                  <div class="modal-body fs-5">
                                    Aún no se realizó ninguna marca en el día actual. ¿Desea finalizar la asistencia de todas formas?
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-cancelar fs-5" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-aceptar fs-5">Finalizar asistencia</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>

                <div class="col-3 mb-4 mt-4">
                    <div id="TercerModulo" class="container-fluid position-relative">
                      <div class="mb-3">
                        <label id="Observaciones" for="editor-Observaciones" class="form-label mb-0 text-white px-3 py-2 rounded-top fs-5">
                          <i class="fa-solid fa-clipboard-list fa-lg me-1" style="color: #f7f7f7;"></i>
                          | Observaciones
                        </label>
                        <div id="editor-Observaciones" class="form-control border border-top-0 rounded-bottom-only">
                        </div>
                      </div>   
                      <div class="position-absolute bottom-0 start-50 translate-middle-x text-center mb-3">
                        <img class="img-fluid" id="LogoCovao" src="/img/LogoCovao.webp" alt="LogoCovao">
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

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>
    <script>
        const asistenciasEstudiante = @json($asistencias ?? []);
    </script>
<script>
  document.getElementById('formBuscar').addEventListener('submit', function(e){
    const select = document.getElementById('TipoAsistencia');
    // Crear un input hidden con el valor del select
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'tipo_asistencia';
    input.value = select.value;
    this.appendChild(input);
});

</script>
<script>
    // Seleccionamos el dropdown
    const select = document.getElementById('TipoAsistencia');

    // Restaurar la selección anterior al cargar la página
    const valorGuardado = localStorage.getItem('tipo_asistencia');
    if(valorGuardado) {
        select.value = valorGuardado;
    }

    // Guardar la opción seleccionada cada vez que cambie
    select.addEventListener('change', () => {
        localStorage.setItem('tipo_asistencia', select.value);
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src={{ asset('js/IngresoComedor.js') }}></script>
    <script src="https://kit.fontawesome.com/1e23feddae.js" crossorigin="anonymous"></script>

<!------------------------------------------------------------------------------------------------------------------------->
</body>
</html>