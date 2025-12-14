<?php
require_once "includes/header_sesion.php";
include 'includes/conexion.php';
require("includes/encabezado.php");

// Variables de mensaje
$mensaje = '';
$tipo = '';

// --- ELIMINACIÓN DE REGISTRO ---
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']); // Seguridad

    // 1️⃣ Obtener información antes de eliminar
    $sqlInfo = "SELECT e.placa_inventario, m.tipo AS tipo_mantenimiento, m.tecnico_nombre 
                FROM historial_mantenimientos h
                INNER JOIN registro_equipos e ON h.id_equipo = e.id
                INNER JOIN mantenimiento m ON h.id_mantenimiento = m.id_mantenimiento
                WHERE h.id_mantenimiento = $idEliminar
                LIMIT 1";
    $resultadoInfo = $conexion->query($sqlInfo);

    if ($resultadoInfo && $resultadoInfo->num_rows > 0) {
        $datos = $resultadoInfo->fetch_assoc();
        $placa = $datos['placa_inventario'] ?? 'Desconocida';
        $tipoMant = ucfirst($datos['tipo_mantenimiento'] ?? 'Desconocido');
        $tecnico = $datos['tecnico_nombre'] ?? 'No especificado';
    } else {
        $placa = 'Desconocida';
        $tipoMant = 'Desconocido';
        $tecnico = 'No especificado';
    }

    // 2️⃣ Eliminar historial
    $sqlDelete = "DELETE FROM historial_mantenimientos WHERE id_mantenimiento = $idEliminar";
    if ($conexion->query($sqlDelete)) {
        $mensaje = "El historial se eliminó correctamente.";
        $tipo = "success";

        // 3️⃣ Crear notificación de eliminación
        $fecha_actual = date('Y-m-d H:i:s');
        $mensaje_notificacion = "Se eliminó el historial de mantenimiento <b>$tipoMant</b> 
                                 del equipo con placa <b>$placa</b> realizado por <b>$tecnico</b>.";
        $modulo = "Eliminación de Historial";

        $sql_notificacion = "INSERT INTO notificaciones (mensaje, modulo, fecha, leido)
                             VALUES ('$mensaje_notificacion', '$modulo', '$fecha_actual', 0)";
        $conexion->query($sql_notificacion);
    } else {
        $mensaje = "No se pudo eliminar el registro.";
        $tipo = "error";
    }
}

// Valores de filtros
$busqueda = isset($_GET['busqueda']) ? $conexion->real_escape_string($_GET['busqueda']) : '';
$estado   = isset($_GET['estado']) ? $conexion->real_escape_string($_GET['estado']) : '';
$tipo     = isset($_GET['tipo']) ? $conexion->real_escape_string($_GET['tipo']) : '';

// Consulta base
$sql = "SELECT h.id_mantenimiento, e.placa_inventario, e.serial, e.marca, e.modelo, e.tipo_equipo,
            m.tipo AS tipo_mantenimiento, m.tecnico_nombre, m.fecha_mantenimiento,
            h.descripcion AS descripcion_historial, h.repuestos_usados, h.estado AS estado_historial,
            a.nombres, a.apellidos, a.identificacion, a.correo_electronico, a.cargo, 
            a.tipo_contrato, a.area, a.sede, a.extension_telefono, 
            a.accesorios_adicionales, a.fecha_asignacion, a.fecha_devolucion, a.observaciones
        FROM historial_mantenimientos h
        INNER JOIN registro_equipos e ON h.id_equipo = e.id
        INNER JOIN mantenimiento m ON h.id_mantenimiento = m.id_mantenimiento
        LEFT JOIN asignacion_equipo a ON e.placa_inventario = a.placa_inventario
        WHERE 1=1";

if (!empty($busqueda)) {
    $sql .= " AND (e.placa_inventario LIKE '%$busqueda%'
                OR e.serial LIKE '%$busqueda%'
                OR e.marca LIKE '%$busqueda%'
                OR e.modelo LIKE '%$busqueda%'
                OR m.tecnico_nombre LIKE '%$busqueda%'
                OR h.descripcion LIKE '%$busqueda%')";
}

if (!empty($estado)) {
    $sql .= " AND h.estado = '$estado'";
}

if (!empty($tipo)) {
    $sql .= " AND m.tipo = '$tipo'";
}

$sql .= " ORDER BY h.fecha DESC";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/stylelistadoMantenimiento.css">
    <title>Historial de Mantenimientos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<main class="main">
    <div class="main-header">
        <h1>Historial de Mantenimientos</h1>
    </div>

    <!-- Formulario de búsqueda y filtros -->
    <form method="GET" class="filtros">
        <input type="text" name="busqueda" placeholder="Buscar..." value="<?= htmlspecialchars($busqueda) ?>">
        <select name="estado">
            <option value="">-- Estado --</option>
            <option value="Pendiente"   <?= $estado == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="En Proceso"  <?= $estado == 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
            <option value="Finalizado"  <?= $estado == 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
        </select>
        <select name="tipo">
            <option value="">-- Tipo --</option>
            <option value="preventivo"  <?= $tipo == 'preventivo' ? 'selected' : '' ?>>Preventivo</option>
            <option value="correctivo"  <?= $tipo == 'correctivo' ? 'selected' : '' ?>>Correctivo</option>
        </select>
        <button type="submit">Filtrar</button>
        <a href="listadoMantenimiento.php" class="btn-reset">Reset</a>
    </form>

    <div class="form-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Placa</th>
                    <th>Serial</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Tipo Equipo</th>
                    <th>Tipo Mantenimiento</th>
                    <th>Fecha</th>
                    <th>Técnico</th>
                    <th>Descripción</th>
                    <th>Repuestos Usados</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id_mantenimiento'] ?></td>
                            <td><?= $row['placa_inventario'] ?></td>
                            <td><?= $row['serial'] ?></td>
                            <td><?= $row['marca'] ?></td>
                            <td><?= $row['modelo'] ?></td>
                            <td><?= $row['tipo_equipo'] ?></td>
                            <td><?= ucfirst($row['tipo_mantenimiento']) ?></td>
                            <td><?= $row['fecha_mantenimiento'] ?></td>
                            <td><?= $row['tecnico_nombre'] ?></td>
                            <td><?= $row['descripcion_historial'] ?></td>
                            <td><?= $row['repuestos_usados'] ?></td>
                            <td class="<?= str_replace(' ', '\\ ', $row['estado_historial']) ?>"><?= $row['estado_historial'] ?></td>
                            <td>
                                <button type="button" class="btn-detalles" onclick='verDetalles(<?= json_encode($row) ?>)'>
                                    <i class='bx bx-show'></i>
                                </button>

                                <button type="button" class="btn-eliminar" onclick="confirmarEliminacion(<?= $row['id_mantenimiento'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="13" style="text-align:center;">No se encontraron mantenimientos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- MODAL DETALLES -->
<div id="modalDetalles" class="modal" style="display:none;">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <h2>Detalles del Mantenimiento</h2>
        <div id="detallesContenido" class="grid-2col"></div>
    </div>
</div>

<script>
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        customClass: { popup: "alerta-pequena" },
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = 'listadoMantenimiento.php?eliminar=' + id;
        }
    });
}

function verDetalles(data) {
    let contenedor = document.getElementById("detallesContenido");
    contenedor.innerHTML = `
        <p><strong>Placa:</strong> ${data.placa_inventario}</p>
        <p><strong>Serial:</strong> ${data.serial}</p>
        <p><strong>Marca:</strong> ${data.marca}</p>
        <p><strong>Modelo:</strong> ${data.modelo}</p>
        <p><strong>Tipo Equipo:</strong> ${data.tipo_equipo}</p>
        <p><strong>Tipo Mantenimiento:</strong> ${data.tipo_mantenimiento}</p>
        <p><strong>Fecha:</strong> ${data.fecha_mantenimiento}</p>
        <p><strong>Técnico:</strong> ${data.tecnico_nombre}</p>
        <p><strong>Descripción:</strong> ${data.descripcion_historial}</p>
        <p><strong>Repuestos Usados:</strong> ${data.repuestos_usados}</p>
        <p><strong>Estado:</strong> ${data.estado_historial}</p>
        <hr>
        <h3>Información de Asignación</h3>
        <p><strong>Nombres:</strong> ${data.nombres ?? ''} ${data.apellidos ?? ''}</p>
        <p><strong>Identificación:</strong> ${data.identificacion ?? ''}</p>
        <p><strong>Correo:</strong> ${data.correo_electronico ?? ''}</p>
        <p><strong>Cargo:</strong> ${data.cargo ?? ''}</p>
        <p><strong>Contrato:</strong> ${data.tipo_contrato ?? ''}</p>
        <p><strong>Área:</strong> ${data.area ?? ''}</p>
        <p><strong>Sede:</strong> ${data.sede ?? ''}</p>
        <p><strong>Extensión:</strong> ${data.extension_telefono ?? ''}</p>
        <p><strong>Accesorios:</strong> ${data.accesorios_adicionales ?? ''}</p>
        <p><strong>Fecha Asignación:</strong> ${data.fecha_asignacion ?? ''}</p>
        <p><strong>Fecha Devolución:</strong> ${data.fecha_devolucion ?? ''}</p>
        <p><strong>Observaciones:</strong> ${data.observaciones ?? ''}</p>
    `;
    document.getElementById("modalDetalles").style.display = "block";
}

function cerrarModal() {
    document.getElementById("modalDetalles").style.display = "none";
}
</script>

<?php if (!empty($mensaje)): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
  Swal.fire({
    title: "Éxito",
    text: '<?= $mensaje ?>',
    icon: "success",
    confirmButtonText: 'Entendido',
    customClass: { popup: 'alerta-pequena' }
  }).then(() => {
    if ('<?= $tipo ?>' === 'success') {
      window.location = 'listadoMantenimiento.php';
    }
  });
});
</script>
<?php endif; ?>
</html>
