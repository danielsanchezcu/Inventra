<?php
// CORS y configuración inicial
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    http_response_code(204);
    exit;
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Conexión
require_once("../includes/conexion.php");

// Recibir datos JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Datos no recibidos"]);
    exit;
}

$id_asignacion = $conexion->real_escape_string($data['id_asignacion']);
$id_equipo = $conexion->real_escape_string($data['id_equipo']); // Lo mandas desde el modal
$nombres = $conexion->real_escape_string($data['nombres']);
$apellidos = $conexion->real_escape_string($data['apellidos']);
$identificacion = $conexion->real_escape_string($data['identificacion']);
$cargo = $conexion->real_escape_string($data['cargo']);
$sede = $conexion->real_escape_string($data['sede']);
$correo = $conexion->real_escape_string($data['correo']);
$contrato = $conexion->real_escape_string($data['contrato']);
$extension = $conexion->real_escape_string($data['extension']);
$accesorios = $conexion->real_escape_string($data['accesorios']);
$observaciones = $conexion->real_escape_string($data['observaciones']);
$area = $conexion->real_escape_string($data['area']);
$estado = $conexion->real_escape_string($data['estado']);

// Iniciar transacción
$conexion->begin_transaction();

try {
    // 1. Actualizar asignación
    $query1 = "UPDATE asignacion_equipo SET 
        nombres = '$nombres',
        apellidos = '$apellidos',
        identificacion = '$identificacion',
        cargo = '$cargo',
        sede = '$sede',
        correo = '$correo',
        contrato = '$contrato',
        extension = '$extension',
        accesorios = '$accesorios',
        observaciones = '$observaciones',
        area = '$area'
        WHERE id_asignacion = '$id_asignacion'";

    if (!$conexion->query($query1)) {
        throw new Exception("Error al actualizar asignación: " . $conexion->error);
    }

    // 2. Actualizar estado del equipo
    $query2 = "UPDATE registro_equipos SET 
        estado = '$estado'
        WHERE id = '$id_equipo'";

    if (!$conexion->query($query2)) {
        throw new Exception("Error al actualizar equipo: " . $conexion->error);
    }

    // Confirmar cambios
    $conexion->commit();
    echo json_encode(["success" => true, "message" => "Asignación y estado actualizados correctamente"]);

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conexion->close();
?>
