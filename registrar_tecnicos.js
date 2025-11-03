const apiUrl = "api/api_tecnicos.php";

// === CARGAR LISTADO AL INICIAR ===
document.addEventListener("DOMContentLoaded", cargarTecnicos);

// === GUARDAR O ACTUALIZAR TÉCNICO ===
document.getElementById("form-tecnico").addEventListener("submit", async (e) => {
  e.preventDefault();

  const id_tecnico = document.getElementById("id_tecnico").value;
  const nombres = document.getElementById("nombres");
  const apellidos = document.getElementById("apellidos");
  const identificacion = document.getElementById("identificacion");
  const telefono = document.getElementById("telefono");
  const correo = document.getElementById("correo");
  const especialidad = document.getElementById("especialidad");
  const estado = document.getElementById("estado");
  const observaciones = document.getElementById("observaciones");

  const campos = [nombres, apellidos, identificacion, telefono, correo, especialidad, estado];

  // --- Limpiar resaltado previo ---
  campos.forEach(campo => campo.classList.remove("campo-incompleto"));

  // --- Validar campos vacíos ---
  const vacios = campos.filter(campo => !campo.value.trim());

  if (vacios.length > 0) {
    vacios.forEach(campo => campo.classList.add("campo-incompleto"));

    Swal.fire({
      icon: "warning",
      title: "Campos incompletos",
      text: "Por favor completa todos los campos obligatorios.",
      position: "center",
      confirmButtonText: "Entendido",
      confirmButtonColor: "#f5b041",
      customClass: {
        popup: "alerta-pequena",
      },
    });

    // --- Remover borde rojo al escribir ---
    vacios.forEach(campo => {
      campo.addEventListener("input", () => campo.classList.remove("campo-incompleto"), { once: true });
    });

    return;
  }

  const data = {
    id_tecnico,
    nombres: nombres.value.trim(),
    apellidos: apellidos.value.trim(),
    identificacion: identificacion.value.trim(),
    telefono: telefono.value.trim(),
    correo: correo.value.trim(),
    especialidad: especialidad.value.trim(),
    estado: estado.value,
    observaciones: observaciones.value.trim(),
  };

  const url = id_tecnico ? `${apiUrl}?action=update` : `${apiUrl}?action=create`;
  const method = id_tecnico ? "PUT" : "POST";

  try {
    const res = await fetch(url, {
      method,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await res.json();

    if (result.error) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: result.error,
        position: "center",
        confirmButtonText: "Entendido",
        confirmButtonColor: "#e74c3c",
        customClass: {
          popup: "alerta-pequena",
        },
      });
    } else {
      Swal.fire({
        icon: "success",
        title: id_tecnico ? "Técnico actualizado" : "Técnico registrado",
        text: result.message || "Operación completada correctamente.",
        position: "center",
        confirmButtonText: "Entendido",
        confirmButtonColor: "#28a745",
        customClass: {
          popup: "alerta-pequena",
        },
      });

      document.getElementById("form-tecnico").reset();
      document.getElementById("id_tecnico").value = "";
      document.getElementById("btnCancelar").style.display = "none";
      cargarTecnicos();
    }
  } catch (error) {
    console.error("Error:", error);
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: "No se pudo conectar con el servidor.",
      position: "center",
      confirmButtonText: "Entendido",
      confirmButtonColor: "#e74c3c",
      customClass: {
        popup: "alerta-pequena",
      },
    });
  }
});

// === BOTÓN CANCELAR ===
document.getElementById("btnCancelar").addEventListener("click", () => {
  document.getElementById("form-tecnico").reset();
  document.getElementById("id_tecnico").value = "";
  document.getElementById("btnCancelar").style.display = "none";
  document.querySelectorAll(".campo-incompleto").forEach(c => c.classList.remove("campo-incompleto"));
});

// === CARGAR LISTADO DE TÉCNICOS ===
async function cargarTecnicos() {
  try {
    const res = await fetch(`${apiUrl}?action=read`);
    const data = await res.json();

    const tbody = document.querySelector("#tablaTecnicos tbody");
    tbody.innerHTML = "";

    if (data.length === 0) {
      tbody.innerHTML = `<tr><td colspan="10" class="table-empty">No se encontraron técnicos registrados.</td></tr>`;
      return;
    }

    data.forEach((tecnico) => {
      const tr = document.createElement("tr");

      tr.innerHTML = `
        <td>${tecnico.id_tecnico}</td>
        <td>${tecnico.nombres}</td>
        <td>${tecnico.apellidos}</td>
        <td>${tecnico.identificacion}</td>
        <td>${tecnico.telefono}</td>
        <td>${tecnico.correo}</td>
        <td>${tecnico.especialidad}</td>
        <td>${tecnico.estado}</td>
        <td>${tecnico.observaciones || ""}</td>
        <td class="td-acciones">
          <button class="btn btn-editar" title="Editar"><i class='bx bx-edit-alt'></i></button>
          <button class="btn btn-eliminar" title="Eliminar"><i class='bx bx-trash'></i></button>
        </td>
      `;

      // --- EDITAR ---
      tr.querySelector(".btn-editar").addEventListener("click", () => {
        document.getElementById("id_tecnico").value = tecnico.id_tecnico;
        document.getElementById("nombres").value = tecnico.nombres;
        document.getElementById("apellidos").value = tecnico.apellidos;
        document.getElementById("identificacion").value = tecnico.identificacion;
        document.getElementById("telefono").value = tecnico.telefono;
        document.getElementById("correo").value = tecnico.correo;
        document.getElementById("especialidad").value = tecnico.especialidad;
        document.getElementById("estado").value = tecnico.estado;
        document.getElementById("observaciones").value = tecnico.observaciones || "";
        document.getElementById("btnCancelar").style.display = "inline";
        window.scrollTo({ top: 0, behavior: "smooth" });
      });

      // --- ELIMINAR ---
      tr.querySelector(".btn-eliminar").addEventListener("click", () => eliminarTecnico(tecnico.id_tecnico));

      tbody.appendChild(tr);
    });
  } catch (error) {
    console.error("Error al cargar técnicos:", error);
    Swal.fire({
      icon: "error",
      title: "Error al cargar",
      text: "No se pudo cargar la lista de técnicos.",
      position: "center",
      confirmButtonText: "Entendido",
      confirmButtonColor: "#e74c3c",
      customClass: {
        popup: "alerta-pequena",
      },
    });
  }
}

// === ELIMINAR TÉCNICO ===
async function eliminarTecnico(id) {
  Swal.fire({
    title: "¿Eliminar técnico?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
    customClass: {
      popup: "alerta-pequena",
    },
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const res = await fetch(`${apiUrl}?action=delete&id=${id}`, { method: "DELETE" });
        const data = await res.json();

        if (data.error) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: data.error,
            position: "center",
            confirmButtonText: "Entendido",
            confirmButtonColor: "#e74c3c",
            customClass: {
              popup: "alerta-pequena",
            },
          });
        } else {
          Swal.fire({
            icon: "success",
            title: "Técnico eliminado",
            text: data.message || "El técnico ha sido eliminado correctamente.",
            position: "center",
            confirmButtonText: "Entendido",
            confirmButtonColor: "#28a745",
            customClass: {
              popup: "alerta-pequena",
            },
          });
          cargarTecnicos();
        }
      } catch (error) {
        console.error("Error al eliminar:", error);
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "No se pudo eliminar el técnico.",
          position: "center",
          confirmButtonText: "Entendido",
          confirmButtonColor: "#e74c3c",
          customClass: {
            popup: "alerta-pequena",
          },
        });
      }
    }
  });
}
