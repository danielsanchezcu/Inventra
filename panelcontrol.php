<?php
include("includes/conexion.php");


// Consulta rápida de resumen
$usuarios = $conexion->query("SELECT COUNT(*) AS total FROM usuario")->fetch_assoc()['total'];
$equipos = $conexion->query("SELECT COUNT(*) AS total FROM registro_equipos WHERE estado = 'disponible'")->fetch_assoc()['total'];
$mantenimiento = $conexion->query("SELECT COUNT(*) AS total FROM registro_equipos WHERE estado = 'mantenimiento'")->fetch_assoc()['total'];
$asignaciones = $conexion->query("SELECT COUNT(*) AS total FROM asignacion_equipo WHERE fecha_asignacion >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['total'];

$datosArea = $conexion->query("SELECT area, COUNT(*) as total FROM asignacion_equipo GROUP BY area");

$labels = [];
$valores = [];

while ($fila = $datosArea->fetch_assoc()) {
    $labels[] = $fila['area'];
    $valores[] = $fila['total'];
}


$asignacionesRecientes = $conexion->query("
  SELECT nombres, apellidos, area, cargo, id_equipo, fecha_asignacion
  FROM asignacion_equipo
  ORDER BY fecha_asignacion DESC
  LIMIT 5
");
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Panel de Control</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styledashb.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
</head>


<body>
    <div class="barrasuperior">
  <div class="busqueda">
    <i class='bx bx-search icono-busqueda'></i>
    <input type="text" placeholder="Buscar..." />
  </div>

  <div class="acciones">
    <i class='bx bx-bell'></i>
    <i class='bx bx-user'></i>
  </div>
</div>

<!-- Barra lateral -->
    <aside class="lateral">
        <div class="logo">
          <img src="imagenes/Logo inventra.png" alt="Logo">
        </div>
       

    <nav class="menu">

    <a href="index.php" class="menu-item">
            <i class='bx bxs-home'></i>
            <span>Inicio</span>
        </a>

        <a href="panelcontrol.php" class="menu-item">
            <i class='bx bx-bar-chart-alt-2'></i>
            <span>Dashboard</span>
        </a>

<!-- Menú desplegable INVENTARIO -->
    <details class="menu-group">
      <summary>
            <i class='bx bx-desktop'></i>
            <span>Dispositivos</span>
            <i class='bx bx-chevron-right arrow'></i>
      </summary>
        <div class="submenu">
            <a href="registro.php"> Registrar Equipo</a>
            <a href="asignar.php"> Asignar Equipo</a>
            <a href="consultar.php"> Consultar Inventario</a>
        </div>
    </details>

    <a href="mantenimiento.php" class="menu-item">
      <i class='bx bxs-cog'></i>
      <span>Mantenimiento</span>
    </a>

    <a href="informes.php" class="menu-item">
      <i class='bx bxs-food-menu'></i>
      <span>Informes</span>
    </a>

    <a href="inicio.html" class="menu-item">
      <i class='bx bxs-exit'></i>
      <span>Salir</span>
    </a>
  </nav>
  </aside>

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
              <td><?= $fila['nombre'] ?></td>
              <td><?= $fila['apellidos'] ?></td>
              <td><?= $fila['area'] ?></td>
              <td><?= $fila['cargo'] ?></td>
              <td><?= $fila['equipo'] ?></td>
              <td><?= date('d-m-Y', strtotime($fila['fecha_asignacion'])) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

  <!-- Datos para gráficos -->
  <script>
    // Datos del gráfico circular
    window.datosGrafico = [<?= $equipos ?>, <?= $mantenimiento ?>, <?= $asignaciones ?>];

    // Datos del gráfico de barras
    const labelsBarras = <?= json_encode($labels); ?>;
    const valoresBarras = <?= json_encode($valores); ?>;
  </script>

  <!-- Cargar Chart.js solo una vez -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Tu script principal -->
  <script src="dashboard.js"></script>





</body>
</html>