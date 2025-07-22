const form = document.getElementById("recuperarForm");
const mensaje = document.getElementById("mensaje");
form.addEventListener("submit", function (e) {
  e.preventDefault();

  const correo = document.getElementById("correo").value;
  console.log("Correo a recuperar:", correo);

  mensaje.classList.remove("d-none");

  setTimeout(() => {
    window.location.href = "login.html";
  }, 3000);
});
