document.addEventListener("DOMContentLoaded", () => {

  /**
   * Descarga un archivo desde la API de exportación
   * @param {string} tipo - Tipo de reporte (asignados, disponibles, etc.)
   * @param {string} formato - Formato deseado (excel o pdf)
   */
  function descargarReporte(tipo, formato) {
    let url = `api/exportar.php?report=${tipo}&format=${formato}`;
    const params = [];

    // === FILTROS POR TIPO ===
    if (tipo === "asignados") {
      const filtro = document.getElementById("reporte-asignados").value;
      if (filtro) params.push(`filter=${encodeURIComponent(filtro)}`);
    }

    if (tipo === "disponibles") {
      const filtro = document.getElementById("reporte-disponibles").value;
      if (filtro) params.push(`filter=${encodeURIComponent(filtro)}`);
    }

    if (tipo === "mantenimiento") {
      const filtro = document.getElementById("reporte-mantenimiento").value;
      if (filtro) params.push(`filter=${encodeURIComponent(filtro)}`);
    }

    if (tipo === "asignaciones_fecha") {
      const inicio = document.getElementById("fecha-inicio").value;
      const fin = document.getElementById("fecha-fin").value;
      if (inicio) params.push(`date_from=${encodeURIComponent(inicio)}`);
      if (fin) params.push(`date_to=${encodeURIComponent(fin)}`);
    }

    if (tipo === "historial") {
      const filtro = document.getElementById("reporte-historial").value;
      const busqueda = document.getElementById("busqueda-historial")?.value.trim();
      if (filtro) params.push(`filter=${encodeURIComponent(filtro)}`);
      if (busqueda) params.push(`search=${encodeURIComponent(busqueda)}`);
    }

    // Construir URL final con filtros
    if (params.length > 0) {
      url += "&" + params.join("&");
    }

    // === DESCARGA DEL ARCHIVO ===
    const enlace = document.createElement("a");
    enlace.href = url;
    enlace.target = "_blank";
    enlace.style.display = "none";
    document.body.appendChild(enlace);
    enlace.click();
    document.body.removeChild(enlace);
  }

  // === EVENTOS BOTONES EXCEL ===
  document.querySelectorAll(".btn-excel").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      const tipo = btn.dataset.tipo;
      if (!tipo) return alert("No se detectó el tipo de reporte.");
      descargarReporte(tipo, "excel");
    });
  });

  // === EVENTOS BOTONES PDF ===
  document.querySelectorAll(".btn-pdf").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      const tipo = btn.closest(".reporte-opciones").querySelector(".btn-excel").dataset.tipo;
      if (!tipo) return alert("No se detectó el tipo de reporte.");
      descargarReporte(tipo, "pdf");
    });
  });

});
