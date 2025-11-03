<?php
header('Content-Type: application/json');
require_once("../includes/conexion.php");

// Marcar todas como leídas
$sql = "UPDATE notificaciones SET leido = 1 WHERE leido = 0";
if ($conexion->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conexion->error]);
}
?>
