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


 document.getElementById("placa_inventario").addEventListener("blur", function () {
  const placa = this.value.trim();

  if (placa !== "") {
    fetch(`api/consultar_equipo.php?placa=${encodeURIComponent(placa)}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.getElementById("serial").value = data.equipo.serial;
          document.getElementById("marca").value = data.equipo.marca;
          document.getElementById("modelo").value = data.equipo.modelo;
          document.getElementById("tipo").value = data.equipo.tipo_equipo;
        } else {
          alert("⚠️ No se encontró un equipo con esa placa de inventario JAJA.");
          document.getElementById("serial").value = "";
          document.getElementById("marca").value = "";
          document.getElementById("modelo").value = "";
          document.getElementById("tipo").value = "";
        }
      })
      .catch(error => {
        console.error("Error al consultar el equipo:", error);
      });
  }
});