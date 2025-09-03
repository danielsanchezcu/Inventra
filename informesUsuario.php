<?php
    require("includes/conexion.php");
    require("includes/encabezado.php"); 
?>


<main class="main">
    <div class="main-header">
        <h2>Informe de  Inventarios</h2>
    </div>
    <div class="form-contenedor">
        <div class="formulario">
            <h3 class="titulo-seccion">
            <i class="fas fa-desktop"></i> Inventario por Usuarios
            </h3>
            <div class="col">
                <label for="usuario" class="required-field">Usuario</label>
                <select name="usuario" id="usuario" class="form-select">
                    <option value="">Seleccione Usuario a consultar equipos</option>
                    <?php
                        $sql="SELECT * FROM usuario";
                        $resultado = $conexion->query($sql);
                        while ($fila = $resultado->fetch_assoc()){
                            $id=$fila['id_usuario'];
                            $nombre=$fila["nombre"];
                            echo "<option value='$id'>".$nombre."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col mt-3">
                <table class="table table-bordered table-striped" id="tablaInforme1">
                	<thead>
                		<tr class="text-center">
                            <th>ID Equipo</th>
                            <th>Fecha Asignación</th>
                            <th>Nombre</th>
                            <th>Serial</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                		</tr>
                	</thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="informesUsuario.js"></script>
<?php require("includes/pie.php"); ?>
<script>
    $('#tablaInforme1').DataTable(
        {
            "language": {
            "url": "Spanish.json"
            }
        }
    );
</script>