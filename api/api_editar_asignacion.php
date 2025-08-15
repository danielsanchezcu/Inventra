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

$id_asignacion          = $data['id_asignacion'] ?? null;
$id_equipo              = $data['id_equipo'] ?? null;
$nombres                = $data['nombres'] ?? '';
$apellidos              = $data['apellidos'] ?? '';
$identificacion         = $data['identificacion'] ?? '';
$cargo                  = $data['cargo'] ?? '';
$sede                   = $data['sede'] ?? '';
$correo_electronico     = $data['correo_electronico'] ?? '';
$tipo_contrato          = $data['tipo_contrato'] ?? '';
$extension_telefono     = $data['extension_telefono'] ?? '';
$accesorios_adicionales = $data['accesorios_adicionales'] ?? '';
$observaciones          = $data['observaciones'] ?? '';
$area                   = $data['area'] ?? '';
$fecha_asignacion       = $data['fecha_asignacion'] ?? '';
$estado                 = $data['estado'] ?? '';

if (!$id_asignacion || !$id_equipo) {
    echo json_encode(["success" => false, "message" => "ID de asignación o equipo no especificado"]);
    exit;
}

$conexion->begin_transaction();

try {
    // UPDATE usando COALESCE(NULLIF(?,''), campo) para no sobreescribir con vacío
    $query1 = "UPDATE asignacion_equipo SET
        nombres                = COALESCE(NULLIF(?, ''), nombres),
        apellidos              = COALESCE(NULLIF(?, ''), apellidos),
        identificacion         = COALESCE(NULLIF(?, ''), identificacion),
        cargo                  = COALESCE(NULLIF(?, ''), cargo),
        sede                   = COALESCE(NULLIF(?, ''), sede),
        correo_electronico     = COALESCE(NULLIF(?, ''), correo_electronico),
        tipo_contrato          = COALESCE(NULLIF(?, ''), tipo_contrato),
        extension_telefono     = COALESCE(NULLIF(?, ''), extension_telefono),
        accesorios_adicionales = COALESCE(NULLIF(?, ''), accesorios_adicionales),
        observaciones          = COALESCE(NULLIF(?, ''), observaciones),
        area                   = COALESCE(NULLIF(?, ''), area),
        fecha_asignacion       = COALESCE(NULLIF(?, ''), fecha_asignacion)
    WHERE id_asignacion = ?";

    $stmt1 = $conexion->prepare($query1);
    if (!$stmt1) {
        throw new Exception("Error al preparar query1: " . $conexion->error);
    }

    $stmt1->bind_param(
        "ssssssssssssi",
        $nombres,
        $apellidos,
        $identificacion,
        $cargo,
        $sede,
        $correo_electronico,
        $tipo_contrato,
        $extension_telefono,
        $accesorios_adicionales,
        $observaciones,
        $area,
        $fecha_asignacion,
        $id_asignacion
    );

    if (!$stmt1->execute()) {
        throw new Exception("Error al ejecutar query1: " . $stmt1->error);
    }

    // Actualizar estado del equipo (si viene vacío, no cambia)
    $query2 = "UPDATE registro_equipos
                SET estado = COALESCE(NULLIF(?, ''), estado)
                WHERE id = ?";

    $stmt2 = $conexion->prepare($query2);
    if (!$stmt2) {
        throw new Exception("Error al preparar query2: " . $conexion->error);
    }
    $stmt2->bind_param("si", $estado, $id_equipo);

    if (!$stmt2->execute()) {
        throw new Exception("Error al ejecutar query2: " . $stmt2->error);
    }

    $conexion->commit();
    echo json_encode(["success" => true, "message" => "Asignación y estado actualizados correctamente"]);

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conexion->close();
