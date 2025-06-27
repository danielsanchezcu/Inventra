<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Panel de Control</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleregistro.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>


<body>
    <div class="barrasuperior">
        <div class="busqueda">
            <input type="text" placeholder="Buscar...">
            <button type="submit"><i class='bx bx-search'></i></button>
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

<!-- Imagen inferior de la barra lateral-->
        <div class="detalle1">
            <img src="imagenes/detalle1.png" alt=""> 
        </div>

    <nav class="menu">
        <a href="panelcontrol.php" class="menu-item">
            <i class='bx bxs-home'></i>
            <span>Panel de control</span>
        </a>

<!-- Menú desplegable INVENTARIO -->
    <details class="menu-group">
      <summary>
            <i class='bx bx-desktop'></i>
            <span>Dispositivos</span>
            <i class='bx bx-chevron-right arrow'></i>
      </summary>
        <div class="submenu">
            <a href="registro.php">· Registrar Equipo</a>
            <a href="asignar.php">· Asignar Equipo</a>
            <a href="consultar.php">· Consultar Inventario</a>
        </div>
    </details>

    <a href="mantenimiento.html" class="menu-item">
      <i class='bx bxs-cog'></i>
      <span>Mantenimiento</span>
    </a>

    <a href="informes.html" class="menu-item">
      <i class='bx bxs-food-menu'></i>
      <span>Informes</span>
    </a>

    <a href="inicio.html" class="menu-item">
      <i class='bx bxs-exit'></i>
      <span>Salir</span>
    </a>
  </nav>
  </aside>