<?php
require_once "includes/header_sesion.php";
require("includes/encabezado.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Técnicos - Inventra</title>
  <link rel="stylesheet" href="css/styleregistrar_tecnico.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<main class="main">
  <div class="main-header">
    <h2 class="page-title">Registrar Técnicos</h2>
  </div>

  <div class="form-contenedor">
    <div class="formulario">
      <h3 class="titulo-seccion">
        <i class="bx bx-user"></i> Información del Técnico
      </h3>

      <form id="form-tecnico" class="form-grid" novalidate>
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
          <input type="email" id="correo" required>
        </div>

        <div class="form-group">
          <label>Especialidad</label>
          <input type="text" id="especialidad">
        </div>

        <div class="form-group">
          <label>Estado</label>
          <select id="estado" required>
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
        <i class="bx bx-group"></i> Listado de Técnicos
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
          <tbody>
            <tr>
              <td colspan="10" class="table-empty">No se encontraron técnicos.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Archivo JS externo que maneja todo -->
<script src="registrar_tecnicos.js"></script>
</body>
</html>
