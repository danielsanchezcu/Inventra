<?php 
    include 'includes/conexion.php';

    if ($_POST) {
        $id=$_POST['id'];
        $fecha=$_POST["fecha_adquisicion"];
        $tecnico=$_POST['tecnico'];
        $tipo=$_POST['tipo'];
        $estado=$_POST['estado'];
        $descripcion=$_POST['descripcion'];
        $repuestos=$_POST['repuesto'];
        $cantidades=$_POST['cantidad'];

        $conexion = new mysqli($servername, $username, $password, $bd);
        $sql="select max(id_mantenimiento) as num from mantenimiento";
        $resultado=$conexion->query($sql);
        $row = $resultado->fetch_array(MYSQLI_ASSOC);
        $num=$row["num"]+1;
        $sql="insert into mantenimiento values('$num','$id','$tecnico', '$fecha', '$tipo', '$estado', '$descripcion')";
        $conexion->query($sql);

        if ($tipo==='correctivo') {
            $num_repuestos = count($repuestos);        
            for ($i=0; $i < $num_repuestos ; $i++) { 
                $repuesto = htmlspecialchars($repuestos[$i]);
                $cantidad = htmlspecialchars($cantidades[$i]);
                $sql="insert into mantenimiento_repuesto values('$num','$repuesto','$cantidad')";
                $resultado=$conexion->query($sql);
            }
        }
        $msj="";
        if ($resultado)
            $msj="Nuevo registro creado exitosamente";
        else
            $msj="Error: " . $sql . "<br>" . $conexion->error;

        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', (event) => {";
        echo "document.getElementById('mensaje-texto').textContent = '" . htmlspecialchars($msj, ENT_QUOTES, 'UTF-8') . "';";
        echo "document.getElementById('mensaje').style.display = 'block';";
        echo "setTimeout(() => {mensaje.style.display = 'none';}, 1500);";
        echo "});";
        echo "</script>";
    }
?>
<?php require("includes/encabezado.php"); ?>

  <main class="main">
        <div class="main-header">
            <h2>Mantenimiento de Equipos</h2>
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

            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Serial</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Procesador</th>
                        <th>S.O</th>
                        <th>RAM</th>
                        <th>D.D</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql="select * from registro_equipos";
                        $resultado = $conexion->query($sql);
                        while ($fila = $resultado->fetch_assoc()){
                            $id=$fila['placa_inventario'];
                            $a='<input type="radio" name="item" id="item" onclick="edita(\''.$id.'\')">';
                            echo "<tr><td class='text-center'>".$id."</td><td>".$fila["modelo"]."</td><td>".$fila["serial"]."</td><td>".$fila["marca"]."</td><td>".$fila["tipo_equipo"]."</td><td>".$fila["estado"]."</td><td>".$fila["procesador"]."</td><td>".$fila["sistema_operativo"]."</td><td class='text-center'>".$fila["ram"]."</td><td class='text-center'>".$fila["disco_duro"]."</td><td class='text-center'>".$a."</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <h3 class="titulo-seccion"><i class="fas fa-microchip"></i> Mantenimiento</h3>
        <form action="" method="post">
            <div class="row">
                <div class="col-md-2">
                    <label for="id" class="required-field">Placa Equipo</label>
                    <input type="text" class="form-control text-end" id="id" name="id" readonly>
                </div>
                <div class="col-md-2">
                    <label for="fecha_adquisicion" class="required-field">Fecha de Mtto</label>
                    <input type="date" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion" required>
                </div>
                <div class="col-md-4">
                    <label for="tecnico" class="required-field">Tecnico Encargado</label>
                    <select name="tecnico" id="tecnico" class="form-select">
                        <option value="">Seleccione</option>
                        <?php
                            $sql="SELECT * FROM tecnico t INNER JOIN usuario u ON u.id_usuario=t.id_usuario";
                            $resultado = $conexion->query($sql);
                            while ($fila = $resultado->fetch_assoc()){
                                $id=$fila['id_tecnico'];
                                $nombre=$fila["nombre"];
                                echo "<option value='$id'>".$nombre."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="tipo" class="required-field">Tipo Mtto</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Seleccione</option>
                        <option value="preventivo">Preventivo</option>
                        <option value="correctivo">Correctivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="estado" class="required-field">Estado Mtto</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Seleccione</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="completado">Completado</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3" id="repuestos">
                <div class="col-md-2">
                    <button class="btn btn-primary" id="agregar">Agregar Repuesto</button>
                </div>
                <table class="table table-bordered table-striped" id="tabla">
                    <thead>
                        <tr class="text-center">
                            <th>Repuesto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            <select name="repuesto[]" id="repuesto_1" class="form-select" onchange="calcula(this.id)">
                                <option value="">Seleccione</option>
                                <?php
                                    $sql="select * from repuesto";
                                    $resultado = $conexion->query($sql);
                                    while ($fila = $resultado->fetch_assoc()){
                                        $id=$fila['id_repuesto'];
                                        $nombre=$fila["nombre"];
                                        $precio=$fila["costo"];
                                        echo "<option value='$id' data-precio='$precio'>".$nombre."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" id="precio_1" name="precio[]" class="form-control text-end" readonly>
                        </td>
                        <td>
                            <input type="number" id="cantidad_1" name="cantidad[]" min="0" class="form-control text-end" oninput="total(this.id)">
                        </td>
                        <td>
                            <input type="text" id="total_1" name="total[]" class="form-control text-end" readonly>
                        </td>
                    </tbody>
                </table>
            </div>
            <div class="row mt-3">
                <label for="descripcion" class="required-field">Descripción del Mantenimiento</label>
                <textarea name="descripcion" name="descripcion" id="descripcion" class="form-control"></textarea>
            </div>
            <div class="row mt-2">
                <button type="submit" class="btn btn-danger" id="grabaMtto">Guardar Información del Mantenimiento</button>
            </div>
        </form>
    </div>
 </main>
<script src="mantenimiento.js"></script>
<?php require("includes/pie.php"); ?>