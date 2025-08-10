<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    http_response_code(204);
    exit;
}

// Permitir CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json; charset=UTF-8');

require_once("../includes/conexion.php");

if (!isset($_GET['id_asignacion'])) {
    echo json_encode([
        "success" => false,
        "message" => "Falta el parámetro id_asignacion"
    ]);
    exit;
}

$id_asignacion = intval($_GET['id_asignacion']);

$sql = "SELECT 
            a.id_asignacion,
            a.id_equipo,
            a.nombres,
            a.apellidos,
            a.identificacion,
            a.correo_electronico,
            a.cargo,
            a.tipo_contrato,
            a.area,
            a.sede,
            a.extension_telefono,
            a.accesorios_adicionales,
            a.fecha_asignacion,
            a.fecha_devolucion,
            a.observaciones,
            a.placa_inventario,
            r.tipo_equipo,
            r.serial,
            r.marca,
            r.modelo,
            r.estado
        FROM asignacion_equipo a
        LEFT JOIN registro_equipos r ON a.id_equipo = r.id
        WHERE a.id_asignacion = ?";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error prepare: " . $conexion->error]);
    exit;
}
$stmt->bind_param("i", $id_asignacion);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "asignacion" => $row
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No se encontró la asignación"
    ]);
}

$stmt->close();
$conexion->close();
