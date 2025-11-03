document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('asignacionForm');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    // === CAMPOS REQUERIDOS ===
    const requiredFields = [
      'placa_inventario',
      'nombres',
      'apellidos',
      'identificacion',
      'correo',
      'contrato',
      'cargo',
      'area',
      'sede',
      'extension',
      'accesorios',
      'fecha_asignacion'
    ];

    let missingFields = [];

    // === LIMPIAR ESTILOS ANTERIORES ===
    requiredFields.forEach(field => {
      const input = document.getElementById(field);
      if (input) input.style.border = '';
    });

    // === VALIDAR CAMPOS VACÍOS ===
    requiredFields.forEach(field => {
      const value = formData.get(field);
      if (!value || value.trim() === '') {
        missingFields.push(field);
        const input = document.getElementById(field);
        if (input) {
          input.style.border = '0.5px solid #dc3545'; // rojo
          input.style.borderRadius = '8px';
          input.style.transition = '0.3s';
        }
      }
    });

    // === SI FALTAN CAMPOS, MOSTRAR ALERTA ===
    if (missingFields.length > 0) {
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

      // Quitar el borde rojo después de 5 segundos
      setTimeout(() => {
        missingFields.forEach(field => {
          const input = document.getElementById(field);
          if (input) input.style.border = '';
        });
      }, 5000);

      return;
    }

    // === SI TODO ESTÁ COMPLETO ===
    const data = {
      placa_inventario: formData.get('placa_inventario'),
      nombres: formData.get('nombres'),
      apellidos: formData.get('apellidos'),
      identificacion: formData.get('identificacion'),
      correo_electronico: formData.get('correo'),
      tipo_contrato: formData.get('contrato'),
      cargo: formData.get('cargo'),
      area: formData.get('area'),
      sede: formData.get('sede'),
      extension_telefono: formData.get('extension'),
      accesorios_adicionales: formData.get('accesorios'),
      fecha_asignacion: formData.get('fecha_asignacion'),
      fecha_devolucion: formData.get('fecha_devolucion'),
      observaciones: formData.get('observaciones'),
    };

    try {
      const response = await fetch('api/api_asignacion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      if (!response.ok) {
        throw new Error(`Error del servidor (código ${response.status})`);
      }

      const result = await response.json();

      // === ÉXITO ===
      if (result.success) {
        Swal.fire({
          icon: 'success',
          title: 'Asignación completada',
          html: `${result.message}`,
          background: '#fefefeff',
          color: '#012e42;',
          confirmButtonColor: '#28a745',
          confirmButtonText: 'Listo',
          timer: 7000,
          timerProgressBar: true,
          customClass: { popup: 'alerta-pequena' }
        });
        form.reset();

      // === ERROR EN VALIDACIÓN / DUPLICADO ===
      } else {
        Swal.fire({
          icon: 'error',
          title: 'No se pudo asignar',
          html: result.message,
          background: '#ffffffff',
          color: '#012e42;',
          confirmButtonColor: '#d33',
          confirmButtonText: 'Cerrar',
          timer: 8000,
          timerProgressBar: true,
          customClass: { popup: 'alerta-pequena' }
        });
      }

    // === ERROR DE RED O SERVIDOR ===
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error de conexión',
        text: 'No se pudo enviar la asignación. ' + error.message,
        background: '#ffffffff',
        color: '#012e42;',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Cerrar',
        timer: 8000,
        timerProgressBar: true,
        customClass: { popup: 'alerta-pequena' }
      });
    }
  });
});
