<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Consultar Inventario</title>
    <link rel="stylesheet" href="style-consultar.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-registro.css">
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
        <a href="dash.html" class="menu-item">
            <i class='bx bxs-home'></i>
            <span>Dashboard</span>
        </a>

<!-- Menú desplegable INVENTARIO -->
    <details class="menu-group">
      <summary>
            <i class='bx bx-desktop'></i>
            <span>Inventario</span>
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
</body>


<main class="main">
        <div class="main-header">
          <h2>CONSULTAR INVENTARIO</h2>
          <h3>INVENTRA</h3>
        </div>  
    <div class="form-section">
        <h3>Busqueda</h3>

  <?php 
        
        include 'conexion.php';

        $consulta = "SELECT * FROM asignacion_prueba";
        $busqueda = "";



if (isset($_GET['enviar'])) {
    if (empty(trim($_GET['busqueda']))) {
        echo "<script>alert('Por favor diligencie el campo de búsqueda.');</script>";
    } else {
        $busqueda = $conn->real_escape_string($_GET['busqueda']);
    $consulta .= " WHERE equipo LIKE '%$busqueda%' OR identificacion LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR apellidos LIKE '%$busqueda%' OR area LIKE '%$busqueda%'";

}
}

$resultado = $conn->query($consulta);

?>



        <div class="consulta-inventario">
            <div class="filtros">
                <form action= "" method="GET">
                <input type="text" id="busqueda" placeholder="Buscar por nombre, usuario, área..." name ="busqueda">
                <i class='bx bx-search'></i>
                <button class= "buscar" type="submit" name="enviar">Buscar</button>
                  <select name="tipo_exportacion">
                  <option value="" disabled selected>Tipo de Exportación</option>
                  <option value="excel">Excel</option>
                  <option value="pdf">PDF</option>
                  </select>
                <button class= "exportar" type="submit" name="exportar">Exportar</button>
                </form>
            </div>
                <table>
                    <thead>
                        <tr>
                            <th>Equipo</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Área</th>
                            <th>Correo</th>
                            <th>Cargo</th>
                            <th>Serial</th>    
                            <th>Estado</th>
                            <th>Identificación</th>
                            <th>Fecha Asignación</th>
                            <th>Fecha Devolución</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()) :  ?>
                        
                        <tr>
                            <td><?= $fila['equipo'] ?></td>
                            <td><?= $fila['nombre'] ?></td>
                            <td><?= $fila['apellidos'] ?></td>
                            <td><?= $fila['area'] ?></td>
                            <td><?= $fila['correo'] ?></td>
                            <td><?= $fila['cargo'] ?></td>
                            <td><?= $fila['serial'] ?></td>
                            <td><?= $fila['estado'] ?></td>
                            <td><?= $fila['identificacion'] ?></td>
                            <td><?= $fila['fecha_asignacion'] ?></td>
                            <td><?= $fila['fecha_devolucion'] ?></td>
                            <td><button class="detalle-btn">Ver detalles</button></td>
                        </tr>
                        <?php endwhile; ?>
                        
                    </tbody>
                </table>
        </div> 
 </div>   
</main>
</body>
</html>