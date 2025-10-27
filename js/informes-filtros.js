// js/informes-filtros.js
document.addEventListener("DOMContentLoaded", () => {
  const selectHistorial = document.getElementById("reporte-historial");
  const inputBusqueda = document.getElementById("busqueda-historial");

  selectHistorial.addEventListener("change", () => {
    const valor = selectHistorial.value;
    if (valor === "por-tecnico" || valor === "por-sede" || valor === "por-estado") {
      inputBusqueda.style.display = "inline-block";
    } else {
      inputBusqueda.style.display = "none";
      inputBusqueda.value = "";
    }
  });
});
