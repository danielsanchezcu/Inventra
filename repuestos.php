<?php 
    require("includes/encabezado.php");

    include 'includes/conexion.php';

    $conexion = new mysqli($servername, $username, $password, $bd);
    $sql="select max(id_repuesto) as num from repuesto";
    $resultado=$conexion->query($sql);
    $row = $resultado->fetch_array(MYSQLI_ASSOC);
    $num=$row["num"]+1;


    $accion = $_GET['accion'] ?? '';
    $id = $_GET['id'] ?? null;

    if ($accion === 'eliminar') {
        $sql="delete from repuesto where id_repuesto='$id'";
        $resultado=$conexion->query($sql);
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', (event) => {";
        echo "document.getElementById('mensaje-texto').textContent = '" . htmlspecialchars("Repuesto elminado correctamente", ENT_QUOTES, 'UTF-8') . "';";
        echo "document.getElementById('mensaje').style.display = 'block';";
        echo "setTimeout(() => {mensaje.style.display = 'none';location.href = 'repuestos.php';}, 1500);";
        echo "});";
        echo "</script>";
    }

    if ($_POST) {
        $msj="";
        $id=$_POST['id'];
        $nombre=$_POST['nombre'];
        $descripcion=$_POST['descripcion'];
        $precio=$_POST['precio'];
        $sql="select * from repuesto where id_repuesto='$id'";
        $msj="";
        $resultado=$conexion->query($sql);
        if ($resultado->num_rows>0) {    
            $sql1="update repuesto set nombre='$nombre', descripcion='$descripcion', costo='$precio' where id_repuesto='$id'";
            $msj= "Datos actualizados correctamente...";
        }else{
            $sql1="insert into repuesto values('$num','$nombre','$descripcion', '$precio')";
            $msj="Nuevo registro creado exitosamente";
        }
        $conexion->query($sql1);
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', (event) => {";
        echo "document.getElementById('mensaje-texto').textContent = '" . htmlspecialchars($msj, ENT_QUOTES, 'UTF-8') . "';";
        echo "document.getElementById('mensaje').style.display = 'block';";
        echo "setTimeout(() => {mensaje.style.display = 'none';location.href = 'repuestos.php';}, 1500);";
        echo "});";
        echo "</script>";

        $sql="select max(id_repuesto) as num from repuesto";
        $resultado=$conexion->query($sql);
        $row = $resultado->fetch_array(MYSQLI_ASSOC);
        $num=$row["num"]+1;
    }
?>

  <main class="main">
    <div class="main-header">
        <h2>Gestión de Repuestos</h2>
    </div>
    <div class="form-contenedor">
        <div class="formulario">
            <h3 class="titulo-seccion">
            <i class="fas fa-desktop"></i> Crear Repuestos
            </h3>
            <div id="mensaje" class="alerta-flotante" style="display: none;">
                <span class="cerrar-alerta" onclick="this.parentElement.style.display='none'">✖</span>
                <span id="mensaje-texto"></span>
            </div>
            
            <form action="" method="post" id="frmRepuestos">
                <div class="row">
                    <div class="col-md-1">
                        <label for="id" class="required-field">ID Repuest</label>
                        <input type="text" class="form-control text-end" id="id" name="id" value="<?= $num; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="nombre" class="required-field">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-4">
                        <label for="descripcion" class="required-field">Descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                    </div>
                    <div class="col-md-2">
                        <label for="precio" class="required-field">Precio</label>
                        <input type="number" min="0" class="form-control text-end" id="precio" name="precio" required>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
            <h3 class="titulo-seccion mt-3"><i class="fas fa-microchip"></i> Listado de Repuestos</h3>
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql="select * from repuesto";
                        $resultado = $conexion->query($sql);
                        while ($fila = $resultado->fetch_assoc()){
                            $id=$fila['id_repuesto'];
                            $nombre=$fila["nombre"];
                            $descripcion=$fila["descripcion"];
                            $precio=$fila["costo"];
                            $a='<a href="repuestos.php?accion=eliminar&id='.$id.'" class="fa fa-trash fa-1x" title="Eliminar"></a>';
                            $b = '<a href="#" class="fa fa-pencil-square fa-1x" title="Editar" onclick="editar(this)" 
                                  data-id="' . htmlspecialchars($id) . '" 
                                  data-nombre="' . htmlspecialchars($nombre) . '" 
                                  data-descripcion="' . htmlspecialchars($descripcion) . '" 
                                  data-precio="' . htmlspecialchars($precio) . '"></a>';
                            echo "<tr><td class='text-center'>".$id."</td><td>".$fila["nombre"]."</td><td>".$fila["descripcion"]."</td><td class='text-end'>".$fila["costo"]."</td><td class='text-center'>".$a." ".$b."</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
 </main>
<script src="repuesto.js"></script>
<?php require("includes/pie.php"); ?>