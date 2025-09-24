<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Mantenimiento</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="mantenimiento.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="encabezado.css">
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="styledashb.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
    
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

    <a href="inicio.php" class="menu-item">
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


    <details class="menu-group">
      <summary>
            <i class="fas fa-wrench"></i>
            <span>Mantenimiento</span>
            <i class='bx bx-chevron-right arrow'></i>
      </summary>
        <div class="submenu">
            <a href="mantenimiento.php">Registrar Mantenimiento</a>
            <a href="repuestos.php"> Agregar Repuestos</a>
            <a href="listadoMantenimiento.php"> Historial Mantenmientos</a>
        </div>
    </details>

    <details class="menu-group">
      <summary>
            <i class='bx bxs-food-menu'></i>
            <span>Informes</span>
            <i class='bx bx-chevron-right arrow'></i>
      </summary>
    </details>

    <a href="index.php" class="menu-item">
      <i class='bx bxs-exit'></i>
      <span>Salir</span>
    </a>
  </nav>
  </aside>