<?php
require("includes/encabezado.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Módulo Técnicos - Inventra</title>
  <link rel="stylesheet" href="registrar_tecnico.css">
    

</head>
<body>
<main class="main">
    <div class="main-header">
        <h2 class="page-title">Registrar Técnicos</h2>
    </div>

<!-- Formulario de registro -->
<div class="form-contenedor">
    <div class="formulario">
        <h3 class="titulo-seccion">
            
            <i class="bx bx-user"></i> Información del Técnico
        </h3>
        <form id="formTecnico" class="form-grid">
        <input type="hidden" id="id_tecnico">

        <div class="form-group">
            <label>Nombres</label>
            <input type="text" id="nombres" required>
        </div>

        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" id="apellidos" required>
        </div>

        <div class="form-group">
            <label>Identificación</label>
            <input type="text" id="identificacion" required>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" id="telefono">
        </div>

        <div class="form-group">
            <label>Correo</label>
            <input type="email" id="correo" require>
        </div>

        <div class="form-group">
            <label>Especialidad</label>
            <input type="text" id="especialidad">
        </div>

        <div class="form-group">
            <label>Estado</label>
            <select id="estado">
            <option value="">Seleccionar</option>
            <option value="ACTIVO">Activo</option>
            <option value="INACTIVO">Inactivo</option>
            <option value="SUSPENDIDO">Suspendido</option>
            </select>
        </div>

        <div class="form-group form-full">
            <label>Observaciones</label>
            <textarea id="observaciones"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-guardar">Guardar</button>
            <button type="button" id="btnCancelar" class="btn btn-cancelar">Cancelar</button>
        </div>
        </form>

        <!-- Tabla de técnicos -->
        <h3 class="titulo-seccion">
            <i class="bx bx-group"></i>Listado de Técnicos
        </h3>
<div class="table-container">
<table id="tablaTecnicos">
    <thead>
        <tr>
        <th>ID</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Identificación</th>
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Especialidad</th>
        <th>Estado</th>
        <th>Observaciones</th>
        <th>Acciones</th>
        </tr>
    </thead>
    <tr>
        <td colspan="10" class="table-empty">No se encontraron técnicos.</td>
    </tr>
    </tbody>
</table>
</div>

    </div>
</div>
</main>

<script>
  const apiUrl = "api/api_tecnicos.php";

  // util: safe value
  const safe = v => (v === null || v === undefined) ? "" : v;

  // Cargar técnicos al iniciar
  document.addEventListener("DOMContentLoaded", cargarTecnicos);

  // Envío del formulario (crear / actualizar)
  document.getElementById("formTecnico").addEventListener("submit", async (e) => {
    e.preventDefault();

    const id = document.getElementById("id_tecnico").value;
    const payload = {
      id_tecnico: id,
      nombres: document.getElementById("nombres").value.trim(),
      apellidos: document.getElementById("apellidos").value.trim(),
      identificacion: document.getElementById("identificacion").value.trim(),
      telefono: document.getElementById("telefono").value.trim(),
      correo: document.getElementById("correo").value.trim(),
      especialidad: document.getElementById("especialidad").value.trim(),
      estado: document.getElementById("estado").value,
      observaciones: document.getElementById("observaciones").value.trim(),
    };

    let url = apiUrl + "?action=create";
    let method = "POST";
    if (id) {
      url = apiUrl + "?action=update";
      method = "PUT";
    }

    try {
      const res = await fetch(url, {
        method,
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      let result;
      try {
        result = await res.json();
      } catch (err) {
        const text = await res.text();
        console.error("Respuesta no JSON:", text);
        alert("Error: respuesta inválida del servidor. Revisa la consola.");
        return;
      }

      alert(result.message || result.error || "Operación completada");
    } catch (err) {
      console.error("Error en fetch:", err);
      alert("Error de conexión con la API");
    }

    document.getElementById("formTecnico").reset();
    document.getElementById("id_tecnico").value = "";
    document.getElementById("btnCancelar").style.display = "none";
    cargarTecnicos();
  });

  document.getElementById("btnCancelar").addEventListener("click", () => {
    document.getElementById("formTecnico").reset();
    document.getElementById("id_tecnico").value = "";
    document.getElementById("btnCancelar").style.display = "none";
  });

  async function cargarTecnicos() {
    try {
      const res = await fetch(apiUrl + "?action=read");
      const data = await res.json();

      const tbody = document.querySelector("#tablaTecnicos tbody");
      tbody.innerHTML = "";

      data.forEach(tecnico => {
        const tr = document.createElement("tr");

        ['id_tecnico','nombres','apellidos','identificacion','telefono','correo','especialidad','estado','observaciones'].forEach(key => {
          const td = document.createElement('td');
          td.textContent = safe(tecnico[key]);
          td.classList.add("td");
          tr.appendChild(td);
        });

        const tdAcc = document.createElement('td');
        tdAcc.classList.add("td", "td-acciones");

        const btnEdit = document.createElement('button');
btnEdit.classList.add("btn", "btn-editar");
btnEdit.innerHTML = '<i class="bx bx-edit-alt"></i>'; // icono de editar
btnEdit.title = "Editar"; // tooltip
btnEdit.addEventListener('click', () => {
  document.getElementById("id_tecnico").value = tecnico.id_tecnico;
  document.getElementById("nombres").value = tecnico.nombres || "";
  document.getElementById("apellidos").value = tecnico.apellidos || "";
  document.getElementById("identificacion").value = tecnico.identificacion || "";
  document.getElementById("telefono").value = tecnico.telefono || "";
  document.getElementById("correo").value = tecnico.correo || "";
  document.getElementById("especialidad").value = tecnico.especialidad || "";
  document.getElementById("estado").value = tecnico.estado || "ACTIVO";
  document.getElementById("observaciones").value = tecnico.observaciones || "";

  document.getElementById("btnCancelar").style.display = "inline";
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

const btnDelete = document.createElement('button');
btnDelete.classList.add("btn", "btn-eliminar");
btnDelete.innerHTML = '<i class="bx bx-trash"></i>'; // icono de bote de basura
btnDelete.title = "Eliminar"; // tooltip
btnDelete.addEventListener('click', () => eliminarTecnico(tecnico.id_tecnico));


        tdAcc.appendChild(btnEdit);
        tdAcc.appendChild(document.createTextNode(' '));
        tdAcc.appendChild(btnDelete);
        tr.appendChild(tdAcc);

        tbody.appendChild(tr);
      });
    } catch (err) {
      console.error("Error cargando técnicos:", err);
      alert("No se pudieron cargar los técnicos. Revisa la consola.");
    }
  }

  async function eliminarTecnico(id) {
    if (!confirm("¿Seguro que deseas eliminar este técnico?")) return;

    try {
      const res = await fetch(apiUrl + "?action=delete&id=" + id, { method: "DELETE" });
      let result;
      try {
        result = await res.json();
      } catch (e) {
        const text = await res.text();
        console.error("Respuesta no JSON al eliminar:", text);
        alert("Error: respuesta inválida del servidor al eliminar.");
        return;
      }
      alert(result.message || result.error || "Operación completada");
      cargarTecnicos();
    } catch (err) {
      console.error("Error en eliminar:", err);
      alert("Error de conexión al eliminar.");
    }
  }
</script>
</body>
</html>
