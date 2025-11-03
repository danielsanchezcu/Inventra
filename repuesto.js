// === Cargar datos en el formulario para editar ===
function editar(element) {
  document.getElementById("id").value = element.dataset.id;
  document.getElementById("nombre").value = element.dataset.nombre;
  document.getElementById("descripcion").value = element.dataset.descripcion;
  document.getElementById("precio").value = element.dataset.precio;
  document.getElementById("cantidad").value = element.dataset.cantidad;

  Swal.fire({
    icon: "info",
    title: "Editando repuesto",
    html: "Puedes modificar los campos y guardar los cambios.",
    background: "#fefefeff",
    color: "#012e42",
    confirmButtonColor: "#28a745",
    confirmButtonText: "Entendido",
    timer: 2000,
    timerProgressBar: true,
    customClass: { popup: "alerta-pequena" },
  });
}

// === Confirmación antes de eliminar con SweetAlert2 ===
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btn-eliminar").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const url = this.getAttribute("href");

      Swal.fire({
        title: "¿Eliminar repuesto?",
        html: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        background: "#fefefeff",
        color: "#012e42",
        customClass: { popup: "alerta-pequena" },
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });
});

// === Envío del formulario con validación visual y SweetAlert ===
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("frmRepuestos");

  if (form) {
    form.addEventListener("submit", (e) => {
      const campos = form.querySelectorAll("[required]");
      let vacio = false;

      campos.forEach((campo) => {
        // Quitar estilos previos
        campo.classList.remove("campo-vacio");

        if (!campo.value.trim()) {
          vacio = true;
          campo.classList.add("campo-vacio");
        }
      });

      if (vacio) {
        e.preventDefault();

        Swal.fire({
          icon: "warning",
          title: "Campos incompletos",
          html: "Por favor completa los campos resaltados en rojo antes de guardar.",
          background: "#fefefeff",
          color: "#012e42",
          confirmButtonColor: "#ffc107",
          confirmButtonText: "Entendido",
          customClass: { popup: "alerta-pequena" },
        });

        // Efecto de animación para llamar la atención
        campos.forEach((campo) => {
          if (campo.classList.contains("campo-vacio")) {
            campo.animate(
              [
                { transform: "scale(1)" },
                { transform: "scale(1.03)" },
                { transform: "scale(1)" },
              ],
              { duration: 400 }
            );
          }
        });

        return;
      }
    });
  }
});

// === Mostrar mensajes que vienen desde PHP (guardado, actualización, eliminación) ===
document.addEventListener("DOMContentLoaded", () => {
  if (typeof mensajePHP !== "undefined" && mensajePHP && tipoPHP) {
    Swal.fire({
      icon: tipoPHP,
      title: tipoPHP === "success" ? "Operación exitosa" : "Error",
      html: mensajePHP,
      background: "#fefefeff",
      color: "#012e42",
      confirmButtonColor: tipoPHP === "success" ? "#28a745" : "#d33",
      confirmButtonText: "Listo",
      timer: 4000,
      timerProgressBar: true,
      customClass: { popup: "alerta-pequena" },
    }).then(() => {
      location.href = "repuestos.php";
    });
  }
});
