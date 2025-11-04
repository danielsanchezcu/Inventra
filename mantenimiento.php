<?php 
include 'includes/conexion.php';

if ($_POST) {
    $placa = trim($_POST['id']); 
    $fecha = trim($_POST["fecha_adquisicion"]);
    $tecnico_id = trim($_POST['tecnico_nombre']); 
    $tipo = trim($_POST['tipo']);
    $estado = trim($_POST['estado']);
    $descripcion = trim($_POST['descripcion']);
    $repuestos = $_POST['repuesto'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];

    $conexion = new mysqli($servername, $username, $password, $bd);

    $msj = "";
    $clase = "";

    // 🔍 Validar campos obligatorios
    $faltan = [];
    if (empty($placa)) $faltan[] = 'id';
    if (empty($fecha)) $faltan[] = 'fecha_adquisicion';
    if (empty($tecnico_id)) $faltan[] = 'tecnico';
    if (empty($tipo)) $faltan[] = 'tipo';
    if (empty($estado)) $faltan[] = 'estado';
    if (empty($descripcion)) $faltan[] = 'descripcion';

    if (!empty($faltan)) {
        // Mostrar alerta si faltan campos
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Campos Incompletos',
                text: 'Por favor completa todos los campos obligatorios antes de continuar.',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#ffc107',
                customClass: { popup: 'alerta-pequena'}
            });

            //  Resaltar los campos faltantes
            const faltantes = " . json_encode($faltan) . ";
            faltantes.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.style.border = '1px solid #dc3545';
                }
            });

            // Quitar el rojo al escribir
            faltantes.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', () => {
                        input.style.border = '';
                        input.style.backgroundColor = '';
                    });
                }
            });
        });
        </script>";
    } else {
        // Si no faltan campos, continuar con el proceso
        $sql = "SELECT id FROM registro_equipos WHERE placa_inventario = '$placa' LIMIT 1";
        $resultado = $conexion->query($sql);
        $row = $resultado->fetch_assoc();

        if (!$row) {
            $msj = "Error: No se encontró un equipo con la placa $placa";
            $clase = "error";
        } else {
            $id_equipo = $row['id'];

            // Verificar solo el último mantenimiento del equipo
            $sql_check = "
                SELECT estado 
                FROM mantenimiento 
                WHERE id_equipo = '$id_equipo'
                ORDER BY id_mantenimiento DESC
                LIMIT 1
            ";
            $resultado_check = $conexion->query($sql_check);
            $registro_activo = false;

            if ($resultado_check && $resultado_check->num_rows > 0) {
                $ultimo = $resultado_check->fetch_assoc();
                $estado_ultimo = strtolower(trim($ultimo['estado'] ?? ''));
                
                // Si el último mantenimiento no está finalizado, bloquear
                if ($estado_ultimo !== 'finalizado' && $estado_ultimo !== '') {
                    $registro_activo = true;
                }
            }

            if ($registro_activo && strtolower($estado) != 'finalizado') {
                $msj = "Este equipo ya tiene un mantenimiento activo. Finalice el anterior para registrar uno nuevo.";
                $clase = "error";
            } else {
                $sql_tecnico = "SELECT CONCAT(nombres, ' ', apellidos) AS nombre_completo 
                                FROM tecnicos 
                                WHERE id_tecnico = '$tecnico_id' 
                                LIMIT 1";
                $res_tecnico = $conexion->query($sql_tecnico);
                $nombre_tecnico = ($res_tecnico && $res_tecnico->num_rows > 0) 
                    ? $res_tecnico->fetch_assoc()['nombre_completo'] 
                    : 'Desconocido';

                $sql_insert = "INSERT INTO mantenimiento 
                    (id_equipo, tecnico_nombre, tecnico_id, fecha_mantenimiento, tipo, estado, descripcion) 
                    VALUES ('$id_equipo', '$nombre_tecnico', '$tecnico_id', '$fecha', '$tipo', '$estado', '$descripcion')";
                $resultado_insert = $conexion->query($sql_insert);

                if ($resultado_insert) {
                    $id_mantenimiento = $conexion->insert_id;

                    // Insertar repuestos si aplica
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

                    // Actualizar el estado del equipo si se finaliza
                    if (strtolower($estado) == 'finalizado') {
                        $sql_update_equipo = "UPDATE registro_equipos SET estado = 'Disponible' WHERE id = '$id_equipo'";
                        $conexion->query($sql_update_equipo);
                    }

                    // Notificación
                    $sql_equipo = "SELECT placa_inventario, marca, modelo FROM registro_equipos WHERE id = '$id_equipo' LIMIT 1";
                    $res_equipo = $conexion->query($sql_equipo);
                    $equipo = $res_equipo->fetch_assoc();
                    $placa_equipo = $equipo['placa_inventario'];
                    $marca_equipo = $equipo['marca'];
                    $modelo_equipo = $equipo['modelo'];

                    $mensaje_notificacion = "Se registró un mantenimiento ($tipo) para el equipo ($placa_equipo) - $marca_equipo $modelo_equipo";
                    $modulo = "Mantenimiento de Equipos";
                    $fecha_actual = date('Y-m-d H:i:s');

                    $sql_notificacion = "INSERT INTO notificaciones (mensaje, modulo, fecha, leido) 
                                        VALUES ('$mensaje_notificacion', '$modulo', '$fecha_actual', 0)";
                    $conexion->query($sql_notificacion);

                    $msj = "Nuevo registro creado exitosamente";
                    $clase = "success";
                } else {
                    $msj = "Error: " . $conexion->error;
                    $clase = "error";
                }
            }
        }

        // Mostrar mensaje SweetAlert (éxito o error)
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            title: '" . ($clase === 'success' ? 'Éxito' : 'Error') . "',
            text: '" . addslashes($msj) . "',
            icon: '" . ($clase === 'success' ? 'success' : 'error') . "',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '" . ($clase === 'success' ? '#28a745' : '#dc3545') . "',
            customClass: { popup: 'alerta-pequena' }
        }).then(() => {
            if ('" . $clase . "' === 'success') {
                window.location = 'mantenimiento.php';
            }
        });
        });
        </script>";
    }
}
?>

<?php require("includes/encabezado.php"); ?>
<link rel="stylesheet" href="css/stylemantenimientos.css?v=<?php echo time(); ?>">


<main class="main">
    <div class="main-header">
        <h2>Mantenimiento de Equipos</h2>
    </div>
    <div class="form-contenedor">
        <div class="formulario">
            <h3 class="titulo-seccion">
                <i class='bx bx-desktop'></i>Listado de Equipos
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

        <h3 class="titulo-seccion"><i class='bx bx-cog'></i>Mantenimiento</h3>
        <form action="" method="post">
            <div class="formulariorow">
                <div class="campos">
                    <label for="id" class="required-field">Placa Equipo</label>
                    <input type="text" class="form-control text-end" id="id" name="id" readonly>
                </div>
                <div class="campos">
                    <label for="fecha_adquisicion" class="required-field">Fecha</label>
                    <input type="date" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion">
                </div>
                <div class="campos">
                    <label for="tecnico" class="required-field">Técnico Encargado</label>
                    <select class="form-control" id="tecnico" name="tecnico_nombre">
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
