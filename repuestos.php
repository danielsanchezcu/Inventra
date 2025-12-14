<?php 
require_once "includes/header_sesion.php";
require("includes/encabezado.php");
include 'includes/conexion.php';

$conexion = new mysqli($servername, $username, $password, $bd);

// Consecutivo automático
$sql = "SELECT MAX(id_repuesto) as num FROM repuesto";
$resultado = $conexion->query($sql);
$row = $resultado->fetch_array(MYSQLI_ASSOC);
$num = $row["num"] + 1;

$accion = $_GET['accion'] ?? '';
$id = $_GET['id'] ?? null;

// Variables para mensaje
$mensaje = '';
$tipo = '';

// Eliminar repuesto
if ($accion === 'eliminar' && $id) {
    $id = intval($id);

    // Obtener el nombre ANTES de eliminar
    $sql_nombre = "SELECT nombre FROM repuesto WHERE id_repuesto='$id' LIMIT 1";
    $res_nombre = $conexion->query($sql_nombre);
    $nombre_repuesto = ($res_nombre && $res_nombre->num_rows > 0)
        ? $res_nombre->fetch_assoc()['nombre']
        : 'Repuesto desconocido';

    // Eliminar repuesto
    $sql = "DELETE FROM repuesto WHERE id_repuesto='$id'";
    if ($conexion->query($sql)) {
        $mensaje = "Repuesto eliminado correctamente";
        $tipo = "success";

        // Crear notificación de eliminación
        $fecha_actual = date('Y-m-d H:i:s');
        $mensaje_notificacion = "El repuesto <b>$nombre_repuesto</b> fue eliminado del inventario.";
        $modulo = "Eliminación de Repuesto";

        $sql_notificacion = "INSERT INTO notificaciones (mensaje, modulo, fecha, leido)
                            VALUES ('$mensaje_notificacion', '$modulo', '$fecha_actual', 0)";
        $conexion->query($sql_notificacion);
    } else {
        $mensaje = "Error al eliminar: " . $conexion->error;
        $tipo = "error";
    }
}


//  Guardar o editar repuesto
if ($_POST) {
    $id = intval($_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $cantidad = intval($_POST['cantidad']);

    $sql = "SELECT * FROM repuesto WHERE id_repuesto='$id'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {    
        $sql1 = "UPDATE repuesto 
                SET nombre='$nombre', descripcion='$descripcion', costo='$precio', cantidad='$cantidad'
                WHERE id_repuesto='$id'";
        $mensaje = "Datos actualizados correctamente";
    } else {
        $sql1 = "INSERT INTO repuesto (id_repuesto, nombre, descripcion, costo, cantidad) 
                VALUES ('$num', '$nombre', '$descripcion', '$precio', '$cantidad')";
        $mensaje = "Nuevo repuesto registrado exitosamente";
    }

    if ($conexion->query($sql1)) {
        $tipo = "success";

  // === Notificación automática ===
    $fecha_actual = date('Y-m-d H:i:s');

    if ($resultado->num_rows > 0) {
        // Si fue una actualización
        $mensaje_notificacion = "El repuesto <b>$nombre</b> fue actualizado correctamente.";
        $modulo = "Actualización de Repuesto";
    } else {
        // Si fue un registro nuevo
        $mensaje_notificacion = "Se ha registrado un nuevo repuesto: <b>$nombre</b>.";
        $modulo = "Nuevo Repuesto";
    }

    $sql_notificacion = "INSERT INTO notificaciones (mensaje, modulo, fecha, leido)
                        VALUES ('$mensaje_notificacion', '$modulo', '$fecha_actual', 0)";
    $conexion->query($sql_notificacion);

    } else {
        $mensaje = "Error: " . $conexion->error;
        $tipo = "error";
    }

    // actualizar consecutivo
    $sql = "SELECT MAX(id_repuesto) as num FROM repuesto";
    $resultado = $conexion->query($sql);
    $row = $resultado->fetch_array(MYSQLI_ASSOC);
    $num = $row["num"] + 1;
}
?>
<link rel="stylesheet" href="css/stylerepuestos.css">
<main class="main">
    <div class="main-header">
        <h2>Gestión de Repuestos</h2>
    </div>
    <div class="form-contenedor">
        <div class="formulario">
            <h3 class="titulo-seccion">
                <i class='bx bxs-cog'></i>Agregar Repuesto
            </h3>

            <!-- Formulario -->
<form action="" method="post" id="frmRepuestos" novalidate>
    <div class="row g-3">
        <!-- Columna ID + Botón -->
        <div class="col-md-2 d-flex flex-column align-items-start">
            <label for="id" class="required-field">ID</label>
            <input type="text" class="form-control form-control-sm mb-2" 
                id="id" name="id" value="<?= $num; ?>" readonly style="max-width:80px;">
            <button class="btn btn-primary btn-sm mt-2">Guardar</button>
        </div>

        <!-- Nombre -->
        <div class="col-md-3">
            <label for="nombre" class="required-field">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <!-- Descripción -->
        <div class="col-md-3">
            <label for="descripcion" class="required-field">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>

        <!-- Precio -->
        <div class="col-md-2">
            <label for="precio" class="required-field">Precio</label>
            <input type="number" min="0" class="form-control text-end" id="precio" name="precio" required>
        </div>

        <!-- Cantidad -->
        <div class="col-md-2">
            <label for="cantidad" class="required-field">Cantidad</label>
            <input type="number" min="0" class="form-control form-control-sm text-end" 
                id="cantidad" name="cantidad" style="max-width:120px;" required>
        </div>
    </div>
</form>

            <!-- Listado -->
            <h3 class="titulo-seccion mt-3"><i class='bx bx-wrench'></i> Listado de Repuestos</h3>
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM repuesto ORDER BY id_repuesto DESC";
                        $resultado = $conexion->query($sql);
                        while ($fila = $resultado->fetch_assoc()){
                            $id = $fila['id_repuesto'];
                            $nombre = $fila["nombre"];
                            $descripcion = $fila["descripcion"];
                            $precio = $fila["costo"];
                            $cantidad = $fila["cantidad"];
                            $a = '<a href="repuestos.php?accion=eliminar&id='.$id.'" 
        class="btn-accion btn-eliminar" 
        title="Eliminar">
        <i class="bx bx-trash"></i>
    </a>'; 

                            $b = '<a href="#" class="btn-accion btn-editar" title="Editar" onclick="editar(this)" 
        data-id="' . htmlspecialchars($id) . '" 
        data-nombre="' . htmlspecialchars($nombre) . '" 
        data-descripcion="' . htmlspecialchars($descripcion) . '" 
        data-precio="' . htmlspecialchars($precio) . '" 
        data-cantidad="' . htmlspecialchars($cantidad) . '">
        <i class="bx bx-edit-alt"></i>
    </a>';

                            $precio_formateado = "$ " . number_format($fila["costo"], 0, ',', '.'); 
                        echo "<tr>
                            <td class='text-center'>".$id."</td>
                            <td>".$fila["nombre"]."</td>
                            <td>".$fila["descripcion"]."</td>
                            <td class='text-end'>".$precio_formateado."</td>
                            <td class='text-center'>".$cantidad."</td>
                            <td class='text-center'>".$a." ".$b."</td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="repuesto.js"></script>
<?php require("includes/pie.php"); ?>

<script>
  // Pasar variables PHP al JS si existen mensajes
  const mensajePHP = <?= json_encode($mensaje) ?>;
  const tipoPHP = <?= json_encode($tipo) ?>;
</script>

