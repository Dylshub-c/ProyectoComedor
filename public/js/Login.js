const formulario = document.getElementById("formularioLogin");
  const correo = document.getElementById("correo");
  const password = document.getElementById("password");
  const alertPassword = document.getElementById("alertPassword");
  const modalExito = document.getElementById("modalExito");
  const btnConfirmar = document.getElementById("btnConfirmar");
  const recordar = document.getElementById("recordar");

  window.onload = () => {
    if (localStorage.getItem("recordar") === "true") {
      cedula.value = localStorage.getItem("cedula") || "";
      password.value = localStorage.getItem("password") || "";
      recordar.checked = true;
    }
  };

  formulario.addEventListener("submit", function (e) {
  e.preventDefault();

  alertCedula.classList.add("d-none");
  alertCedulaFormato.classList.add("d-none");
  alertPassword.classList.add("d-none");

  const cedulaVal = cedula.value.trim();
  const passwordVal = password.value.trim();
  let hayError = false;
  if (cedulaVal === "") {
    alertCedula.classList.remove("d-none");
    hayError = true;
  } else if (!/^\d+$/.test(cedulaVal)) {
    alertCedulaFormato.classList.remove("d-none");
    hayError = true;
  }

  if (passwordVal === "") {
    alertPassword.classList.remove("d-none");
    hayError = true;
  }

  if (!hayError) {
    if (recordar.checked) {
      localStorage.setItem("cedula", cedulaVal);
      localStorage.setItem("password", passwordVal);
      localStorage.setItem("recordar", "true");
    } else {
      localStorage.removeItem("cedula");
      localStorage.removeItem("password");
      localStorage.setItem("recordar", "false");
    }

    modalExito.style.display = "flex";
  }
});
