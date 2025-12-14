<?php
require_once "includes/header_sesion.php";
include("includes/conexion.php");


// Consulta rápida de resumen
$usuarios = $conexion->query("SELECT COUNT(*) AS total FROM usuario")->fetch_assoc()['total'];
$equipos = $conexion->query("SELECT COUNT(*) AS total FROM registro_equipos WHERE estado = 'Disponible'")->fetch_assoc()['total'];
$mantenimiento = $conexion->query("SELECT COUNT(*) AS total FROM registro_equipos WHERE estado = 'En mantenimiento'")->fetch_assoc()['total'];
$asignaciones = $conexion->query("SELECT COUNT(*) AS total FROM asignacion_equipo WHERE fecha_asignacion >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['total'];

$datosArea = $conexion->query("SELECT area, COUNT(*) as total FROM asignacion_equipo GROUP BY area");

$labels = [];
$valores = [];

while ($fila = $datosArea->fetch_assoc()) {
    $labels[] = $fila['area'];
    $valores[] = $fila['total'];
}


$asignacionesRecientes = $conexion->query("
  SELECT nombres, apellidos, area, cargo, placa_inventario, fecha_asignacion
  FROM asignacion_equipo
  ORDER BY fecha_asignacion DESC
  LIMIT 5
");

require("includes/encabezado.php");
?>


<title>Inventra Web - Dashboard </title>
<link rel="stylesheet" href="<?= $base ?>css/styledashb.css">
  <div class="contenido-dashboard">
  <h4>Dashboard</h4>

  <div class="tarjetas">
    <div class="tarjeta azul">
      <i class='bx bx-user'></i>
      <p class="label">Usuarios registrados</p>
      <p class="valor"><?php echo $usuarios; ?></p>
    </div>

    <div class="tarjeta verde">
      <i class='bx bx-desktop'></i>
      <p class="label">Equipos disponibles</p>
      <p class="valor"><?php echo $equipos; ?></p>
    </div>

    <div class="tarjeta amarilla">
      <i class='bx bx-cog'></i>
      <p class="label">En mantenimiento</p>
      <p class="valor"><?php echo $mantenimiento; ?></p>
    </div>

    <div class="tarjeta morada">
      <i class='bx bx-bar-chart-alt-2'></i>
      <p class="label">Asignaciones recientes</p>
      <p class="valor"><?php echo $asignaciones; ?></p>
    </div>
  </div>

<div class="contenedor-graficos">
  <div class="grafico-container">
    <canvas id="graficoCircular"></canvas>
  </div>

  <div class="grafico-barras-container">
    <canvas id="graficoBarras"></canvas>

    <!-- Aquí insertamos la tabla debajo -->
    <div class="tabla-asignaciones-container">
      <h4>Asignaciones recientes</h4>
      <table class="tabla-asignaciones">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Área</th>
            <th>Cargo</th>
            <th>Equipo</th>
            <th>Fecha de asignación</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($fila = $asignacionesRecientes->fetch_assoc()): ?>
            <tr>
              <td class='text-nowrap'><?= $fila['nombres'] ?></td>
              <td class='text-nowrap'><?= $fila['apellidos'] ?></td>
              <td class='text-nowrap'><?= $fila['area'] ?></td>
              <td class='text-nowrap'><?= $fila['cargo'] ?></td>
              <td class='text-nowrap'><?= $fila['placa_inventario'] ?></td>
              <td class='text-nowrap'><?= date('d-m-Y', strtotime($fila['fecha_asignacion'])) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require("includes/pie.php"); ?>