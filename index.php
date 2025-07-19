
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Panel de Control</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index1.css">
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

<main class="main-bienvenida">
  <div class="contenido-inicio">
    <div class="logo-bienvenida">
      <h1>Bienvenido a <span class="color-acento">Inventra</span></h1>
      <p>Tu sistema para la gestión inteligente de equipos y asignaciones</p>
    </div>

    <a href="panelcontrol.php" class="boton-ingreso">Ir a Dashboard</a>

    <hr class="linea-divisoria">

    <section class="inicio-info">
      <h2>¿Qué puedes hacer con Inventra?</h2>
      <ul class="funcionalidades">
        <li><i class='bx bx-package'></i>Registrar y consultar inventario</li>
        <li><i class='bx bx-share-alt'></i>Asignar equipos a personal o áreas</li>
        <li><i class='bx bx-cog'></i>Controlar el mantenimiento de dispositivos</li>
        <li><i class='bx bx-bar-chart-alt-2'></i>Visualizar reportes y estadísticas</li>
      </ul>
    </section>

    <footer>
      &copy; 2025 Inventra - Todos los derechos reservados
    </footer>
    <footer>
      <p>Desarrollado por Daniel Felipe Sánchez Currea</p>
    </footer>
  </div>
</main>
</body>
</html>