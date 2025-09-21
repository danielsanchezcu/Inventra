document.getElementById("form-login").addEventListener("submit", function (e) {
  e.preventDefault();

  const correo = document.getElementById("correo").value;
  const contrasena = document.getElementById("contrasena").value;
  const mensajeDiv = document.getElementById("mensaje");

  
  mensajeDiv.classList.remove("mensaje-error", "mensaje-exito", "mensaje-oculto");
  mensajeDiv.textContent = "";

  fetch("api/apilogin.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ correo, contrasena })
  })
    .then(res => {
      if (!res.ok) throw new Error("Error del servidor");
      return res.json();
    })
    .then(data => {
      if (data.success) {
        // mensajeDiv.textContent = "Inicio de sesión exitoso";
        // mensajeDiv.classList.add("mensaje-exito");
        location.href = "inicio.php";

        // setTimeout(() => {
        //   window.location.href = "inicio.php";
        // }, 1500);
      } else {
        mensajeDiv.textContent = data.message;
        mensajeDiv.classList.add("mensaje-error");
      }
    })
    .catch(err => {
      mensajeDiv.textContent = "No se pudo conectar con el servidor.";
      mensajeDiv.classList.add("mensaje-error");
    });
});

// Mostrar/ocultar contraseña con el ícono del ojo
const claveInput = document.getElementById('contrasena');
const toggleClave = document.getElementById('toggleClave');

toggleClave.addEventListener('click', function () {
  const tipo = claveInput.getAttribute('type') === 'password' ? 'text' : 'password';
  claveInput.setAttribute('type', tipo);
  this.classList.toggle('bx-show-alt');
  this.classList.toggle('bx-hide');
});
