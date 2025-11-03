<?php
header('Content-Type: application/json');
require_once("../includes/conexion.php");

// Eliminar notificaciones más antiguas de 1 día
$sql = "DELETE FROM notificaciones WHERE fecha < NOW() - INTERVAL 1 DAY";
if ($conexion->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conexion->error]);
}
?>
