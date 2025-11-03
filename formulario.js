document.addEventListener('DOMContentLoaded', function () {
  const tipoSelect = document.getElementById('tipo');
  const perifericosSection = document.getElementById('perifericos-desktop');
  const tecladoInput = document.getElementById('teclado');
  const mouseInput = document.getElementById('mouse');

  // === Mostrar/Ocultar periféricos si el tipo es Desktop ===
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

  // === Máscara para serial ===
  const serial = document.getElementById('serial');
  if (serial) {
    serial.addEventListener('input', function () {
      this.value = this.value
        .replace(/[^a-zA-Z0-9]/g, '')
        .toUpperCase()
        .slice(0, 10);
    });
  }

  // === Máscara para Placa de Inventario (INV-###) ===
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

  // === Máscara para costo ===
  const costo = document.getElementById('costo');
  if (costo) {
    costo.addEventListener('input', function () {
      this.value = this.value.replace(/[^\d.,]/g, '');
    });
  }

  // === Envío del formulario con Fetch ===
  const form = document.getElementById("form-registro");

  if (form) {
    // Evita que el navegador haga la validación nativa
    form.setAttribute("novalidate", true);

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(form);

      // === VALIDACIÓN DE CAMPOS VACÍOS (TODOS LOS REQUIRED) ===
      const camposRequeridos = form.querySelectorAll("[required]");
      const vacios = [];

      camposRequeridos.forEach(campo => {
        campo.style.border = ""; // limpia estilos anteriores
        if (!campo.value.trim()) {
          vacios.push(campo);
          campo.style.border = "0.5px solid #dc3545";
          campo.style.borderRadius = "8px";
          campo.style.transition = "0.3s";
        }
      });

      if (vacios.length > 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Campos incompletos',
          html: `Por favor completa todos los campos obligatorios antes de continuar.`,
          background: '#fff',
          color: '#012e42',
          confirmButtonColor: '#ffc107',
          confirmButtonText: 'Entendido',
          timer: 5000,
          timerProgressBar: true,
          customClass: { popup: 'alerta-pequena' }
        });

        // Quita los bordes rojos después de 5 segundos
        setTimeout(() => {
          vacios.forEach(campo => (campo.style.border = ""));
        }, 5000);

        return; // Evita el envío
      }

      // === Si todo está completo, proceder con el envío ===
      fetch("api/apiregistrar_equipo.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            mostrarSweetAlert("success", data.message);
            form.reset();
            if (perifericosSection) perifericosSection.classList.remove("mostrar");
          } else {
            mostrarSweetAlert("warning", data.message);
          }
        })
        .catch(error => {
          console.error("Error:", error);
          mostrarSweetAlert("error", "Error al enviar el formulario.");
        });
    });
  }

  // === Función para mostrar SweetAlert2 personalizada ===
  function mostrarSweetAlert(tipo, mensaje) {
    Swal.fire({
      icon: tipo,
      title:
        tipo === "success"
          ? "Registro completado"
          : tipo === "warning"
          ? "Aviso"
          : "Error",
      html: `${mensaje}`,
      background: "#fefefeff",
      color: "#012e42",
      confirmButtonColor: "#28a745",
      confirmButtonText: "Listo",
      timer: tipo === "success" ? 7000 : 6000,
      timerProgressBar: true,
      customClass: { popup: "alerta-pequena" },
    });
  }

});
