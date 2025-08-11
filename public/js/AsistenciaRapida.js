 const btnGuardar = document.getElementById("btnGuardar");
    const form = document.getElementById("formAsistencia");

    btnGuardar.onclick = () => {
      const fecha = document.getElementById("fecha").value.trim();
      const tipo = document.getElementById("tipoAsistencia").value.trim();
      const estado = document.getElementById("estadoAsistencia").value.trim();
      const explicacion = document.getElementById("explicacion").value.trim();

      const errorFecha = document.getElementById("errorFecha");
      const errorTipo = document.getElementById("errorTipo");
      const errorEstado = document.getElementById("errorEstado");

      let valido = true;

      if (!fecha) {
        errorFecha.style.display = "block";
        valido = false;
      } else {
        errorFecha.style.display = "none";
      }

      if (!tipo) {
        errorTipo.style.display = "block";
        valido = false;
      } else {
        errorTipo.style.display = "none";
      }

      if (!estado) {
        errorEstado.style.display = "block";
        valido = false;
      } else {
        errorEstado.style.display = "none";
      }

      if (valido) {
        new bootstrap.Modal(document.getElementById("modalConfirmacion")).show();
      }
    };

    document.getElementById("confirmarAsistencia").onclick = async () => {
      bootstrap.Modal.getInstance(document.getElementById("modalConfirmacion")).hide();

      const fecha = document.getElementById("fecha").value.trim();
      const tipo = document.getElementById("tipoAsistencia").value.trim();
      const estado = document.getElementById("estadoAsistencia").value.trim();
      const explicacion = document.getElementById("explicacion").value.trim();

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

      const data = {
        fecha_hora: fecha,
        tipo_asistencia: tipo,
        estado: estado,
        observaciones: explicacion,
      };

      try {
        const response = await fetch("/asistencia-rapida", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
          },
          body: JSON.stringify(data),
        });

        if (response.ok) {
          new bootstrap.Modal(document.getElementById("modalExito")).show();
          form.reset();
        } else {
          alert("Error al guardar la asistencia. Intente nuevamente.");
        }
      } catch (error) {
        alert("Error de conexión o servidor. Revisa consola.");
        console.error("Fetch error:", error);
      }
    };