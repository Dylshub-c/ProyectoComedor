const btnGuardar = document.getElementById("btnGuardar");
const form = document.getElementById("formAsistencia");

btnGuardar.onclick = () => {
  const fecha = document.getElementById("fecha").value.trim();
  const beca = document.getElementById("beca").value.trim();
  const errorFecha = document.getElementById("errorFecha");
  const errorBeca = document.getElementById("errorBeca");

  let valido = true;

  if (!fecha) {
    errorFecha.style.display = "block";
    valido = false;
  } else {
    errorFecha.style.display = "none";
  }

  if (!beca) {
    errorBeca.style.display = "block";
    valido = false;
  } else {
    errorBeca.style.display = "none";
  }

  if (valido) {
    new bootstrap.Modal(document.getElementById('modalConfirmacion')).show();
  }
};

document.getElementById("confirmarAsistencia").onclick = () => {
  bootstrap.Modal.getInstance(document.getElementById('modalConfirmacion')).hide();
  new bootstrap.Modal(document.getElementById('modalExito')).show();
  form.reset();
};

document.getElementById("menuBtn").onclick = () => {
  document.getElementById("sidebar").classList.add("active");
  document.getElementById("overlay").classList.add("active");
};

document.getElementById("closeSidebar").onclick =
document.getElementById("overlay").onclick = () => {
  document.getElementById("sidebar").classList.remove("active");
  document.getElementById("overlay").classList.remove("active");
};

/*-----------------------------------------------------------------------------*/
const quill = new Quill('#editor', {
      theme: 'snow' // Usa la barra de herramientas predeterminada
    });