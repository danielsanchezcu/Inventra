<?php
// Permitir CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Incluir conexión a la base de datos
require_once("../includes/conexion.php");

// Leer datos JSON del cuerpo de la solicitud
$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Validar que se recibió el ID
if (isset($input['id_asignacion'])) {
    $id_asignacion = $input['id_asignacion'];

    // 1. Obtener la placa del equipo antes de eliminar
    $stmtPlaca = $conexion->prepare("SELECT placa_inventario FROM asignacion_equipo WHERE id_asignacion = ?");
    $stmtPlaca->bind_param("i", $id_asignacion);
    $stmtPlaca->execute();
    $resultado = $stmtPlaca->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $placa = $fila['placa_inventario'];

        // 2. Eliminar la asignación
        $stmtEliminar = $conexion->prepare("DELETE FROM asignacion_equipo WHERE id_asignacion = ?");
        $stmtEliminar->bind_param("i", $id_asignacion);
        $eliminado = $stmtEliminar->execute();

        if ($eliminado) {
            // 3. Actualizar el estado del equipo a "DISPONIBLE"
            $stmtActualizar = $conexion->prepare("UPDATE registro_equipos SET estado = 'DISPONIBLE' WHERE placa_inventario = ?");
            $stmtActualizar->bind_param("s", $placa);
            $stmtActualizar->execute();

            echo json_encode(["success" => true, "message" => "Asignación eliminada y estado actualizado"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al eliminar la asignación"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "No se encontró la asignación"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ID de asignación no recibido"]);
}
?>
