// Datos del estudiante simulados desde el sistema
const estudiante = {
  nombre: "Juancito Perez",
  cedula: "123456789",
  avatar: "img/FotoEstudiante.webp",
  becas: {
    desayuno: true,
    almuerzo: false,
  },
  asistencias: {
    "2025-07-24": { desayuno: "check", almuerzo: "check" },
    "2025-07-25": { desayuno: "x", almuerzo: "check" },
    "2025-07-26": { desayuno: "check", almuerzo: "x" }
  }
};

let modoEdicion = false;

// Cargar datos iniciales
window.onload = () => {
  document.getElementById("nombreEstudiante").textContent = estudiante.nombre;
  document.getElementById("cedulaEstudiante").textContent = estudiante.cedula;
  document.querySelector(".student-avatar").src = estudiante.avatar;

  document.getElementById("becaDesayuno").checked = estudiante.becas.desayuno;
  document.getElementById("becaAlmuerzo").checked = estudiante.becas.almuerzo;

  document.getElementById("becaDesayuno").disabled = true;
  document.getElementById("becaAlmuerzo").disabled = true;

  actualizarTabla();
};

document.getElementById("btnEditar").addEventListener("click", () => {
  modoEdicion = !modoEdicion;
  actualizarTabla();
});

document.getElementById("btnFinalizar").addEventListener("click", () => {
  guardarCambios();
  modoEdicion = false;
  alert("Asistencias actualizadas.");
  actualizarTabla();
});

// Actualiza tabla si cambian fechas
document.getElementById("fechaInicio").addEventListener("change", actualizarTabla);
document.getElementById("fechaFinal").addEventListener("change", actualizarTabla);

function actualizarTabla() {
  const inicio = new Date(document.getElementById("fechaInicio").value);
  const fin = new Date(document.getElementById("fechaFinal").value);

  const desayuno = estudiante.becas.desayuno;
  const almuerzo = estudiante.becas.almuerzo;

  const encabezado = document.getElementById("encabezadoTabla");
  const cuerpo = document.getElementById("cuerpoTabla");

  encabezado.innerHTML = `<th>Fecha</th>`;
  if (desayuno) encabezado.innerHTML += `<th>Desayuno</th>`;
  if (almuerzo) encabezado.innerHTML += `<th>Almuerzo</th>`;

  cuerpo.innerHTML = "";

  if (isNaN(inicio) || isNaN(fin)) return;

  for (let fecha = new Date(inicio); fecha <= fin; fecha.setDate(fecha.getDate() + 1)) {
    const diaISO = fecha.toISOString().split("T")[0];
    const diaCR = fecha.toLocaleDateString("es-CR", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    });

    let fila = `<tr><td>${diaCR}</td>`;

    if (desayuno) {
      fila += `<td data-fecha="${diaISO}" data-tipo="desayuno">${renderMarca("desayuno", diaISO)}</td>`;
    }

    if (almuerzo) {
      fila += `<td data-fecha="${diaISO}" data-tipo="almuerzo">${renderMarca("almuerzo", diaISO)}</td>`;
    }

    fila += `</tr>`;
    cuerpo.innerHTML += fila;
  }
}

function renderMarca(tipo, fechaISO) {
  const marca = estudiante.asistencias?.[fechaISO]?.[tipo];

  if (!modoEdicion) {
    if (marca === "check") return `<i class="bi bi-check-circle"></i>`;
    if (marca === "x") return `<i class="bi bi-x-circle"></i>`;
    return `<i class="bi bi-dash-circle"></i>`;
  } else {
    return `
      <select class="form-select form-select-sm">
        <option value="check" ${marca === "check" ? "selected" : ""}>✓</option>
        <option value="x" ${marca === "x" ? "selected" : ""}>✗</option>
        <option value="none" ${!marca ? "selected" : ""}>Quitar</option>
      </select>
    `;
  }
}

function guardarCambios() {
  const selects = document.querySelectorAll("select");

  selects.forEach(select => {
    const td = select.closest("td");
    const fecha = td.getAttribute("data-fecha");
    const tipo = td.getAttribute("data-tipo");
    const valor = select.value;

    if (!estudiante.asistencias[fecha]) {
      estudiante.asistencias[fecha] = {};
    }

    if (valor === "none") {
      delete estudiante.asistencias[fecha][tipo];
    } else {
      estudiante.asistencias[fecha][tipo] = valor;
    }
  });
}

