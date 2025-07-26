const form = document.getElementById("formEstudiante");
    const mensajeError = document.getElementById("mensajeError");
    const modal = new bootstrap.Modal(document.getElementById("modalExito"));

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const nombre = document.getElementById("nombre").value.trim();
      const cedula = document.getElementById("cedula").value.trim();
      const seccion = document.getElementById("seccion").value.trim();
      const especialidad = document.getElementById("especialidad").value.trim();
      const tipoBeca = document.getElementById("tipoBeca").value;

      if (!nombre || !cedula || !seccion || !especialidad || !tipoBeca) {
        mensajeError.classList.remove("d-none");
      } else {
        mensajeError.classList.add("d-none");
        modal.show();
      }
    });