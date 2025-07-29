// Máscara para Placa de Inventario (formato fijo INV-###)
document.addEventListener("DOMContentLoaded", () => {
  const placaInput = document.getElementById('placa_inventario');
  const mensajeError = document.getElementById("mensaje-error");

  if (placaInput) {
    // Establecer formato inicial
    placaInput.value = 'INV-';

    // Forzar formato INV-###
    placaInput.addEventListener('input', function () {
      if (!this.value.startsWith('INV-')) {
        this.value = 'INV-';
      }
      let numeros = this.value.slice(4).replace(/\D/g, '').slice(0, 3);
      this.value = 'INV-' + numeros;
    });

    // Evitar borrar el prefijo "INV-"
    placaInput.addEventListener('keydown', function (e) {
      if (this.selectionStart <= 4 && ['Backspace', 'Delete'].includes(e.key)) {
        e.preventDefault();
      }
    });

    // Consultar al desenfocar (blur)
    placaInput.addEventListener("blur", async () => {
      const placa = placaInput.value.trim();

      // Validar que la placa no esté vacía
      if (placa === "" || placa === "INV-") {
        mensajeError.textContent = "⚠️ Placa no proporcionada";
        limpiarCampos();
        return;
      }

      try {
        const response = await fetch("api/consultar_equipo.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ placa })
        });

        const data = await response.json();

        if (!data.success) {
          mensajeError.textContent = data.message || "Equipo no encontrado";
          limpiarCampos();
        } else {
          mensajeError.textContent = ""; // Limpiar mensaje si es exitoso
          document.querySelector('input[name="serial"]').value = data.serial;
          document.querySelector('input[name="marca"]').value = data.marca;
          document.querySelector('input[name="modelo"]').value = data.modelo;
          document.querySelector('input[name="tipo"]').value = data.tipo_equipo;
        }

      } catch (error) {
        mensajeError.textContent = "Error consultando equipo";
        limpiarCampos();
      }
    });

    // Función para limpiar los campos si hay error
    function limpiarCampos() {
      document.querySelector('input[name="serial"]').value = "";
      document.querySelector('input[name="marca"]').value = "";
      document.querySelector('input[name="modelo"]').value = "";
      document.querySelector('input[name="tipo"]').value = "";
    }
  }
});
