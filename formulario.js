document.addEventListener('DOMContentLoaded', function () {
  const tipoSelect = document.getElementById('tipo');
  const perifericosSection = document.getElementById('perifericos-desktop');
  const tecladoInput = document.getElementById('teclado');
  const mouseInput = document.getElementById('mouse');

  // Mostrar/Ocultar periféricos si el tipo es Desktop
  if (tipoSelect) {
    tipoSelect.addEventListener('change', function () {
      if (this.value === 'Desktop') {
        perifericosSection.classList.add('mostrar');
        tecladoInput.required = true;
        mouseInput.required = true;
      } else {
        perifericosSection.classList.remove('mostrar');
        tecladoInput.required = false;
        mouseInput.required = false;
      }
    });
  }

  // Máscara para serial: solo letras y números, mayúsculas, máx 10
  const serial = document.getElementById('serial');
  if (serial) {
    serial.addEventListener('input', function () {
      this.value = this.value
        .replace(/[^a-zA-Z0-9]/g, '')
        .toUpperCase()
        .slice(0, 10);
    });
  }

  // Máscara para Placa de Inventario (formato fijo INV-###)
  const placa = document.getElementById('placa_inventario');
  if (placa) {
    placa.value = 'INV-';
    placa.addEventListener('input', function () {
      if (!this.value.startsWith('INV-')) {
        this.value = 'INV-';
      }
      let numeros = this.value.slice(4).replace(/\D/g, '').slice(0, 3);
      this.value = 'INV-' + numeros;
    });

    placa.addEventListener('keydown', function (e) {
      if (this.selectionStart <= 4 && ['Backspace', 'Delete'].includes(e.key)) {
        e.preventDefault();
      }
    });
  }

  // Máscara para costo: solo números y separadores
  const costo = document.getElementById('costo');
  if (costo) {
    costo.addEventListener('input', function () {
      this.value = this.value.replace(/[^\d.,]/g, '');
    });
  }

  // === Envío del formulario con Fetch ===
  const form = document.getElementById("form-registro");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(form);

      fetch("api/apiregistrar_equipo.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            mostrarMensaje("success", data.message);
            form.reset();
            if (perifericosSection) {
              perifericosSection.classList.remove("mostrar");
            }
          } else {
            mostrarMensaje("error", data.message);
          }
        })
        .catch(error => {
          console.error("Error:", error);
          mostrarMensaje("error", "❌ Error al enviar el formulario.");
        });
    });
  }

  // Función para mostrar mensajes 
  function mostrarMensaje(tipo, texto) {
    const mensaje = document.getElementById("mensaje");
    const mensajeTexto = document.getElementById("mensaje-texto");

    if (!mensaje || !mensajeTexto) return;

    mensajeTexto.innerHTML = texto;
    mensaje.classList.remove("success");
    if (tipo === "success") {
      mensaje.classList.add("success");
    }

    mensaje.style.display = "block";
    mensaje.scrollIntoView({ behavior: "smooth", block: "center" });

    setTimeout(() => {
      mensaje.style.display = "none";
    }, 15000);
  }
});
