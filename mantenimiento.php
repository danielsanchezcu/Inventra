<?php 
include 'includes/conexion.php';

if ($_POST) {
    $placa = $_POST['id']; 
    $fecha = $_POST["fecha_adquisicion"];
    $tecnico_id = $_POST['tecnico_nombre']; 
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];
    $descripcion = $_POST['descripcion'];
    $repuestos = $_POST['repuesto'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];

    $conexion = new mysqli($servername, $username, $password, $bd);

    $msj = "";
    $clase = "";

    // ✅ Obtener id del equipo
    $sql = "SELECT id FROM registro_equipos WHERE placa_inventario = '$placa' LIMIT 1";
    $resultado = $conexion->query($sql);
    $row = $resultado->fetch_assoc();

    if (!$row) {
        $msj = "❌ Error: No se encontró un equipo con la placa $placa";
        $clase = "error";
    } else {
        $id_equipo = $row['id'];

        // Verificar mantenimiento activo
        $sql_check = "SELECT * FROM mantenimiento WHERE id_equipo = '$id_equipo' AND LOWER(estado) != 'finalizado'";
        $resultado_check = $conexion->query($sql_check);

        if ($resultado_check->num_rows > 0 && strtolower($estado) != 'finalizado') {
            $msj = "❌ Este equipo ya tiene un mantenimiento activo. Finalice el anterior para registrar uno nuevo.";
            $clase = "error";
        } else {
            // ✅ Obtener nombre completo del técnico
            $sql_tecnico = "SELECT CONCAT(nombres, ' ', apellidos) AS nombre_completo 
                            FROM tecnicos 
                            WHERE id_tecnico = '$tecnico_id' 
                            LIMIT 1";
            $res_tecnico = $conexion->query($sql_tecnico);
            $nombre_tecnico = ($res_tecnico && $res_tecnico->num_rows > 0) 
                ? $res_tecnico->fetch_assoc()['nombre_completo'] 
                : 'Desconocido';

            // ✅ Insertar mantenimiento (deja que el AUTO_INCREMENT maneje el ID)
            $sql_insert = "INSERT INTO mantenimiento 
                (id_equipo, tecnico_nombre, tecnico_id, fecha_mantenimiento, tipo, estado, descripcion) 
                VALUES ('$id_equipo', '$nombre_tecnico', '$tecnico_id', '$fecha', '$tipo', '$estado', '$descripcion')";
            $resultado_insert = $conexion->query($sql_insert);

            if ($resultado_insert) {
                $id_mantenimiento = $conexion->insert_id;

                // Insertar repuestos si es correctivo
                if ($tipo === 'correctivo' && !empty($repuestos)) {
                    $num_repuestos = count($repuestos);        
                    for ($i = 0; $i < $num_repuestos; $i++) { 
                        $repuesto = htmlspecialchars($repuestos[$i]);
                        $cantidad = htmlspecialchars($cantidades[$i]);
                        $sql_rep = "INSERT INTO mantenimiento_repuesto 
                            (id_mantenimiento, id_repuesto, cantidad) 
                            VALUES ('$id_mantenimiento', '$repuesto', '$cantidad')";
                        $conexion->query($sql_rep);
                    }
                }

                // Actualizar estado del equipo si se finaliza
                if (strtolower($estado) == 'finalizado') {
                    $sql_update_equipo = "UPDATE registro_equipos SET estado = 'Disponible' WHERE id = '$id_equipo'";
                    $conexion->query($sql_update_equipo);
                }

                $msj = "✅ Nuevo registro creado exitosamente";
                $clase = "success";
            } else {
                $msj = "❌ Error: " . $conexion->error;
                $clase = "error";
            }
        }
    }

    //Mostrar mensaje
    echo "<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mensajeBox = document.getElementById('mensaje');
        const mensajeTexto = document.getElementById('mensaje-texto');
        mensajeTexto.textContent = '" . htmlspecialchars($msj, ENT_QUOTES, 'UTF-8') . "';
        mensajeBox.className = 'mensaje-form $clase';
        mensajeBox.style.display = 'block';
        setTimeout(() => { mensajeBox.style.display = 'none'; }, 6000);
    });
    </script>";
}
?>

<?php require("includes/encabezado.php"); ?>
<link rel="stylesheet" href="mantenimientos.css">

<main class="main">
    <div class="main-header">
        <h2>Mantenimiento de Equipos</h2>
    </div>
    <div class="form-contenedor">
        <div class="formulario">
            <h3 class="titulo-seccion">
                <i class="fas fa-desktop"></i> Listado de Equipos
            </h3>
            <div id="mensaje" class="mensaje-form" style="display: none;">
                <span id="mensaje-texto"></span>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead class="text-center">
                        <tr>
                            <th>Placa</th>
                            <th>Modelo</th>
                            <th>Serial</th>
                            <th>Marca</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Procesador</th>
                            <th>Sistema Operativo</th>
                            <th>RAM</th>
                            <th>Disco Duro</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT * FROM registro_equipos WHERE estado='En Mantenimiento'";
                            $resultado = $conexion->query($sql);
                            while ($fila = $resultado->fetch_assoc()){
                                $placa = $fila['placa_inventario'];
                                $a = '<input type="radio" name="item" id="item" onclick="edita(\''.$placa.'\')">';
                                echo "<tr><td class='text-center'>".$placa."</td><td>".$fila["modelo"]."</td><td>".$fila["serial"]."</td><td>".$fila["marca"]."</td><td>".$fila["tipo_equipo"]."</td><td>".$fila["estado"]."</td><td>".$fila["procesador"]."</td><td>".$fila["sistema_operativo"]."</td><td class='text-center'>".$fila["ram"]."</td><td class='text-center'>".$fila["disco_duro"]."</td><td class='text-center'>".$a."</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>       
        </div>

        <h3 class="titulo-seccion"><i class="fas fa-tools"></i> Mantenimiento</h3>
        <form action="" method="post">
            <div class="formulariorow">
                <div class="campos">
                    <label for="id" class="required-field">Placa Equipo</label>
                    <input type="text" class="form-control text-end" id="id" name="id" readonly>
                </div>
                <div class="campos">
                    <label for="fecha_adquisicion" class="required-field">Fecha</label>
                    <input type="date" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion" required>
                </div>
                <div class="campos">
                    <label for="tecnico" class="required-field">Técnico Encargado</label>
                    <select class="form-control" id="tecnico" name="tecnico_nombre" required>
                        <option value="">Seleccione un técnico</option>
                        <?php
                        $sql_tec = "
                            SELECT id_tecnico, CONCAT(nombres, ' ', apellidos) AS nombre_completo
                            FROM tecnicos 
                            WHERE estado = 'Activo'
                            GROUP BY id_tecnico
                            ORDER BY nombres ASC
                        ";
                        $res_tec = $conexion->query($sql_tec);
                        while ($tec = $res_tec->fetch_assoc()) {
                            echo "<option value='{$tec['id_tecnico']}'>{$tec['nombre_completo']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="campos">
                    <label for="tipo" class="required-field">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Seleccione</option>
                        <option value="preventivo">Preventivo</option>
                        <option value="correctivo">Correctivo</option>
                    </select>
                </div>
                <div class="campos">
                    <label for="estado" class="required-field">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="En Proceso">En Proceso</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
                </div>
            </div>

            <div class="formulariorepuestos" id="repuestos">
                <div class="tabla-repuestos">
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
                            <tr>
                                <td>
                                    <select name="repuesto[]" id="repuesto_1" class="form-select" onchange="calcula(this.id)">
                                        <option value="">Seleccione</option>
                                        <?php
                                        $sql = "SELECT * FROM repuesto";
                                        $resultado = $conexion->query($sql);
                                        while ($fila = $resultado->fetch_assoc()){
                                            echo "<option value='{$fila['id_repuesto']}' data-precio='{$fila['costo']}'>{$fila['nombre']}</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" id="precio_1" name="precio[]" class="form-control text-end" readonly></td>
                                <td><input type="number" id="cantidad_1" name="cantidad[]" min="0" class="form-control text-end" oninput="total(this.id)"></td>
                                <td><input type="text" id="total_1" name="total[]" class="form-control text-end" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="botones-repuestos">
                        <button type="button" class="boton-agregar" id="agregar">Agregar Repuesto</button>
                    </div>
                </div>
            </div>

            <div class="descripcion">
                <label for="descripcion" class="required-field">Descripción del Mantenimiento</label>
                <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
            </div>
            <div class="botones">
                <button type="button" class="boton-cancelar" id="btnCancelar">Cancelar</button>
                <button type="submit" class="boton-guardar" id="btnGuardar">Guardar</button>
            </div>
        </form>
    </div>
</main>

<script src="mantenimiento.js"></script>
<?php require("includes/pie.php"); ?>
