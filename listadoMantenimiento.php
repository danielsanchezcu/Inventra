<?php
include 'includes/conexion.php';
require("includes/encabezado.php");

// Valores de filtros
$busqueda = isset($_GET['busqueda']) ? $conexion->real_escape_string($_GET['busqueda']) : '';
$estado   = isset($_GET['estado']) ? $conexion->real_escape_string($_GET['estado']) : '';
$tipo     = isset($_GET['tipo']) ? $conexion->real_escape_string($_GET['tipo']) : '';

// Consulta base
$sql = "SELECT h.id_mantenimiento, e.placa_inventario, e.serial, e.marca, e.modelo, e.tipo_equipo,
               m.tipo AS tipo_mantenimiento, m.tecnico_nombre, m.fecha_mantenimiento,
               h.descripcion AS descripcion_historial, h.repuestos_usados, h.estado AS estado_historial
        FROM historial_mantenimientos h
        INNER JOIN registro_equipos e ON h.id_equipo = e.id
        INNER JOIN mantenimiento m ON h.id_mantenimiento = m.id_mantenimiento
        WHERE 1=1";

// Filtros dinámicos
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
    <link rel="stylesheet" href="listadoMantenimiento.css">
    <title>Historial de Mantenimientos</title>
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
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" style="text-align:center;">No se encontraron mantenimientos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</html>
