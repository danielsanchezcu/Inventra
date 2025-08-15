document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('asignacionForm');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const placa = document.getElementById('placa_inventario').value.trim();
    const nombres = document.getElementById('nombres').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const identificacion = document.getElementById('identificacion').value.trim();

    if (!placa || !nombres || !apellidos || !identificacion) {
      mostrarMensajeEnPantalla('⚠️ Por favor completa todos los campos obligatorios.', false);
      return;
    }

    const formData = {
      placa_inventario: placa,
      nombres,
      apellidos,
      identificacion,
      correo_electronico: document.getElementById('correo').value,
      tipo_contrato: document.getElementById('contrato').value,
      cargo: document.getElementById('cargo').value,
      area: document.getElementById('area').value,
      sede: document.getElementById('sede').value,
      extension_telefono: document.getElementById('extension').value,
      accesorios_adicionales: document.getElementById('accesorios').value,
      fecha_asignacion: document.getElementById('fecha_asignacion').value,
      fecha_devolucion: document.getElementById('fecha_devolucion').value,
      observaciones: document.getElementById('observaciones').value,
    };

    try {
      const response = await fetch('api/api_asignacion.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
      });

      if (!response.ok) {
        throw new Error(`Error del servidor (código ${response.status})`);
      }

      const result = await response.json();

    
      if (result.success) {
        mostrarMensajeEnPantalla('✅ ' + result.message, true);
        form.reset();
      } else {
        mostrarMensajeEnPantalla('❌ ' + result.message, false);
      }

    } catch (error) {
      mostrarMensajeEnPantalla('⚠️ Error al enviar la asignación: ' + error.message, false);
    }
  });
});

function mostrarMensajeEnPantalla(mensaje, esExito = true) {
  const contenedor = document.getElementById('mensajeRespuesta');

  contenedor.className = 'mensaje-respuesta'; // Reinicia clases
  contenedor.classList.add(esExito ? 'success' : 'error');
  contenedor.innerHTML = mensaje;
  contenedor.style.whiteSpace = 'pre-line'; 

  // Mostrar con animación
  setTimeout(() => contenedor.classList.add('visible'), 10);

  // Scroll suave hacia el mensaje
  contenedor.scrollIntoView({ behavior: 'smooth', block: 'center' });

  // Ocultar después de 20 segundos
  setTimeout(() => {
    contenedor.classList.remove('visible');
    setTimeout(() => {
      contenedor.innerHTML = '';
      contenedor.className = 'mensaje-respuesta'; // Limpiar clases
    }, 500);
  }, 40000);
}
