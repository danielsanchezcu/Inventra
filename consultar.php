<?php require("includes/encabezado.php"); ?>



<main class="main">
    <div class="main-header">
        <h2>Consultar inventario</h2>
        <h3>INVENTRA</h3>
    </div>  
    <div class="form-section">
        <h3>Busqueda</h3>

  <?php 
    include 'includes/conexion.php';

    $consulta = "SELECT * FROM asignacion";
    $busqueda = "";

    if (isset($_GET['enviar'])) {
        if (empty(trim($_GET['busqueda']))) {
            echo "<script>alert('Por favor diligencie el campo de búsqueda.');</script>";
        } else {
            $busqueda = $conn->real_escape_string($_GET['busqueda']);
            $consulta .= " WHERE equipo LIKE '%$busqueda%' OR identificacion LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR apellidos LIKE '%$busqueda%' OR area LIKE '%$busqueda%'";
        }
    }

    $resultado = $conexion->query($consulta);
?>


    <div class="consulta-inventario">
        <div class="filtros">
            <form action= "" method="GET">
                <input type="text" id="busqueda" placeholder="Buscar por nombre, usuario, área..." name ="busqueda">
                <i class='bx bx-search'></i>
                <button class= "btn btn-primary buscar" type="submit" name="enviar">Buscar</button>
                <select name="tipo_exportacion">
                    <option value="" disabled selected>Tipo de Exportación</option>
                    <option value="excel">Excel</option>
                    <option value="pdf">PDF</option>
                </select>
                <button class= "btn btn-primary exportar" type="submit" name="exportar">Exportar</button>
            </form>
        </div>
        <table class="table table-bordered table-striped mt-3">
            <thead class="text-center">
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
            <?php echo var_dump($fila); ?>    
                <tr>
                    <td><?= $fila['id_equipo'] ?></td>
                    <td><?= $fila['nombres'] ?></td>
                    <td><?= $fila['apellidos'] ?></td>
                    <td><?= $fila['area'] ?></td>
                    <td><?= $fila['correo_electronico'] ?></td>
                    <td><?= $fila['cargo'] ?></td>
                    <td><?= $fila['serial'] ?></td>
                    <td><?= $fila['estado'] ?></td>
                    <td><?= $fila['identificacion'] ?></td>
                    <td><?= $fila['fecha_asignacion'] ?></td>
                    <td><?= $fila['fecha_devolucion'] ?></td>
                    <td>
                        <button class="detalle-btn">Editar</button>
                        <button class="detalle-btn2">Eliminar</button>
                    </td>

                </tr>
                <?php endwhile; ?>        
            </tbody>
        </table>
    </div> 
 </div>   
</main>

<?php require("includes/pie.php"); ?>