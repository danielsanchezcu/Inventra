<?php
require("includes/encabezado.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes - Inventra</title>
  <link rel="stylesheet" href="informes.css">
  <script src="https://kit.fontawesome.com/a2e0e9ad4d.js" crossorigin="anonymous"></script>
</head>
<body>
  <section class="reportes-container">
    <div class="reportes-header">
      <h2 class="titulo-reportes">
        <i class="fas fa-file-alt"></i> Reportes de Inventario
      </h2>
      
      <p class="descripcion-reportes">
        Esta interfaz permite generar y exportar informes detallados del inventario general,
        equipos asignados, disponibles, en mantenimiento o reparación. Además, facilita
        consultas por rango de fechas para optimizar la gestión y el control de los recursos tecnológicos.
      </p>
    </div>

    <div class="reportes-lista">

      <!-- Reporte 1: Equipos Asignados -->
      <div class="reporte-item">
        <label for="reporte-asignados" class="reporte-label">Equipos Asignados</label>
        <div class="reporte-opciones">
          <select id="reporte-asignados" class="reporte-select">
            <option value="">Seleccione una opción</option>
            <option value="todas">Todas las áreas</option>
            <option value="sistemas">Área de Sistemas</option>
            <option value="administrativa">Área Administrativa</option>
            <option value="comercial">Área Comercial</option>
            <option value="Recursos Humanos">Área Recursos Humanos</option>
          </select>
          <div class="reporte-botones">
            <button class="btn-excel" data-tipo="asignados" title="Ver informe">
              <img src="imagenes/excel-icono.png" alt="Excel">
            </button>
            <button class="btn-pdf" title="Exportar a PDF">
              <img src="imagenes/pdf-icon.png" alt="PDF">
            </button>
          </div>
        </div>
      </div>

      <!-- Reporte 2: Equipos Disponibles -->
      <div class="reporte-item">
        <label for="reporte-disponibles" class="reporte-label">Equipos Disponibles</label>
        <div class="reporte-opciones">
          <select id="reporte-disponibles" class="reporte-select">
            <option value="">Seleccione una opción</option>
            <option value="todos">Todos los equipos</option>
            <option value="portatiles">Laptop</option>
            <option value="escritorio">Desktop</option>
          </select>
          <div class="reporte-botones">
            <button class="btn-excel" data-tipo="disponibles" title="Ver informe">
              <img src="imagenes/excel-icono.png" alt="Excel">
            </button>
            <button class="btn-pdf" title="Exportar a PDF">
              <img src="imagenes/pdf-icon.png" alt="PDF">
            </button>
          </div>
        </div>
      </div>

      <!-- Reporte 3: Equipos en Mantenimiento/Reparación -->
      <div class="reporte-item">
        <label for="reporte-mantenimiento" class="reporte-label">Equipos en Mantenimiento/Reparación</label>
        <div class="reporte-opciones">
          <select id="reporte-mantenimiento" class="reporte-select">
            <option value="">Seleccione una opción</option>
            <option value="pendiente">Pendientes</option>
            <option value="en-proceso">En Proceso</option>
            <option value="finalizado">Finalizados</option>
          </select>
          <div class="reporte-botones">
            <button class="btn-excel" data-tipo="mantenimiento" title="Ver informe">
              <img src="imagenes/excel-icono.png" alt="Excel">
            </button>
            <button class="btn-pdf" title="Exportar a PDF">
             <img src="imagenes/pdf-icon.png" alt="PDF">
            </button>
          </div>
        </div>
      </div>

      <!-- Reporte 4: Asignaciones por Fecha -->
      <div class="reporte-item">
        <label for="reporte-fecha" class="reporte-label">Asignaciones por Fecha</label>
        <div class="reporte-opciones">
          <input type="date" id="fecha-inicio" class="input-fecha" placeholder="Desde">
          <input type="date" id="fecha-fin" class="input-fecha" placeholder="Hasta">
          <div class="reporte-botones">
            <button class="btn-excel" data-tipo="asignaciones_fecha" title="Ver informe">
              <img src="imagenes/excel-icono.png" alt="Excel">
            </button>
            <button class="btn-pdf" title="Exportar a PDF">
              <img src="imagenes/pdf-icon.png" alt="PDF">
            </button>
          </div>
        </div>
      </div>

<!-- Reporte 5: Historial de Mantenimientos -->
<div class="reporte-item">
  <label for="reporte-historial" class="reporte-label">Historial de Mantenimientos</label>
  <div class="reporte-opciones">
    <select id="reporte-historial" class="reporte-select">
      <option value="">Seleccione una opción</option>
      <option value="todos">Todos los registros</option>
      <option value="por-tecnico">Por Técnico</option>
      <option value="por-sede">Por Sede</option>
      <option value="por-estado">Por Estado</option>
    </select>

    <!-- Campo de búsqueda dinámico -->
    <input 
      type="text" 
      id="busqueda-historial" 
      class="input-busqueda" 
      placeholder="Buscar técnico, sede o estado..."
      style="display: none;"
    >

    <div class="reporte-botones">
      <button class="btn-excel" title="Exportar a Excel" data-tipo="historial">
        <img src="imagenes/excel-icono.png" alt="Excel">
      </button>
      <button class="btn-pdf" title="Exportar a PDF">
        <img src="imagenes/pdf-icon.png" alt="PDF">
      </button>
    </div>
  </div>
</div>

<script src="js/informes-api.js"></script>
<script src="js/informes-filtros.js"></script>
<script src="js/informes-exportar.js"></script>

</body>
</html>
