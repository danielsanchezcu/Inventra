<?php
header('Content-Type: application/json');
require_once("../includes/conexion.php");

// Consultar las 10 más recientes
$sql = "SELECT id, mensaje, modulo, fecha, leido 
        FROM notificaciones 
        ORDER BY fecha DESC 
        LIMIT 10";
$result = $conexion->query($sql);

$notificaciones = [];
while ($row = $result->fetch_assoc()) {
    $notificaciones[] = $row;
}

// Contar no leídas
$sql_contar = "SELECT COUNT(*) AS no_leidas FROM notificaciones WHERE leido = 0";
$res_contar = $conexion->query($sql_contar);
$no_leidas = $res_contar->fetch_assoc()['no_leidas'] ?? 0;

// Enviar respuesta JSON
echo json_encode([
    'notificaciones' => $notificaciones,
    'no_leidas' => $no_leidas
]);
?>
