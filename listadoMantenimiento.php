<?php 
	require("includes/encabezado.php"); 
	include 'includes/conexion.php';
?>

<main class="main">
    <div class="main-header">
        <h2>Listado de Equipos en Mantenimiento</h2>
    </div>
    <div class="form-contenedor">
        <div class="formulario">
            <h3 class="titulo-seccion">
            <i class="fas fa-desktop"></i> Listado de Equipos
            </h3>
            <div id="mensaje" class="alerta-flotante" style="display: none;">
                <span class="cerrar-alerta" onclick="this.parentElement.style.display='none'">✖</span>
                <span id="mensaje-texto"></span>
            </div>

            <table class="table table-bordered table-striped" id="listado">
                <thead class="text-center">
                    <tr>
                        <th>ID equipo</th>
                        <th>Técnico</th>
                        <th>Fecha</th>
                        <th>Tipo Mtto</th>
                        <th>Estado</th>
                        <th>Descripción Mtto</th>
                        <th>Repuestos</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql="SELECT a.id_mantenimiento,a.id_equipo,u.nombre,a.fecha_mantenimiento,a.tipo,a.estado,a.descripcion,c.nombre as repuesto,b.cantidad FROM mantenimiento a INNER JOIN tecnico t ON t.id_tecnico=a.id_tecnico INNER JOIN usuario u ON u.id_usuario=t.id_usuario INNER JOIN mantenimiento_repuesto b ON b.id_mantenimiento=a.id_mantenimiento INNER JOIN repuesto c ON c.id_repuesto=b.id_repuesto ORDER BY a.id_mantenimiento";
                        $resultado = $conexion->query($sql);
                        while ($fila = $resultado->fetch_assoc()){
                            $id=$fila['id_mantenimiento'];
                            $a='<input type="radio" name="item" id="item" onclick="edita(\''.$id.'\')">';
                            echo "<tr><td class='text-center'>".$fila["id_equipo"]."</td><td>".$fila["nombre"]."</td><td class='text-center'>".$fila["fecha_mantenimiento"]."</td><td>".$fila["tipo"]."</td><td>".$fila["estado"]."</td><td>".$fila["descripcion"]."</td><td>".$fila["repuesto"]."</td><td class='text-end'>".$fila["cantidad"]."</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require("includes/pie.php"); ?>
<script>
    $('#listado').DataTable(
        {
            "language": {
            "url": "Spanish.json"
            }
        }
    );
</script>