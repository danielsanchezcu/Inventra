// js/informes-api.js
document.addEventListener("DOMContentLoaded", () => {

  async function cargarInforme(tipo) {
    let url = `api/informes.php?report=${tipo}`;
    const params = [];

    // Capturar filtros dinámicos
    const select = document.querySelector(`#reporte-${tipo}`);
    const filtro = select ? select.value : "";

    if (filtro) params.push(`filter=${encodeURIComponent(filtro)}`);

    // Campo de búsqueda (solo historial)
    if (tipo === "historial") {
      const busqueda = document.getElementById("busqueda-historial")?.value.trim();
      if (busqueda) params.push(`search=${encodeURIComponent(busqueda)}`);
    }

    // Filtros de fecha
    if (tipo === "asignaciones_fecha") {
      const inicio = document.getElementById("fecha-inicio").value;
      const fin = document.getElementById("fecha-fin").value;
      if (inicio) params.push(`date_from=${inicio}`);
      if (fin) params.push(`date_to=${fin}`);
    }

    // Unir parámetros
    if (params.length > 0) {
      url += "&" + params.join("&");
    }

    try {
      const response = await fetch(url);
      const result = await response.json();

      if (result.status === "ok") {
        renderTable(result.data);
      } else {
        document.getElementById("tabla-resultados").innerHTML = `<p class="texto-error">${result.message}</p>`;
      }
    } catch (err) {
      document.getElementById("tabla-resultados").innerHTML = `<p class="texto-error">Error al conectar con la API: ${err.message}</p>`;
    }
  }

  function renderTable(data) {
    const container = document.getElementById("tabla-resultados");
    if (!data || data.length === 0) {
      container.innerHTML = `<p class="texto-placeholder">No se encontraron registros.</p>`;
      return;
    }

    const headers = Object.keys(data[0]);
    let html = "<table class='tabla-informes'><thead><tr>";
    headers.forEach(h => (html += `<th>${h.replaceAll('_', ' ')}</th>`));
    html += "</tr></thead><tbody>";

    data.forEach(row => {
      html += "<tr>";
      headers.forEach(h => (html += `<td>${row[h] ?? ''}</td>`));
      html += "</tr>";
    });

    html += "</tbody></table>";
    container.innerHTML = html;
  }

  // Escuchar clicks de botones Excel
  document.querySelectorAll(".btn-excel").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      const tipo = btn.dataset.tipo;
      if (tipo) cargarInforme(tipo);
    });
  });
});
