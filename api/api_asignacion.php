<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once("../includes/conexion.php");

// Leer el JSON del body
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($data['placa_inventario'])) {
        echo json_encode(['success' => false, 'message' => 'Placa no proporcionada.']);
        exit;
    }

    $placa = $data['placa_inventario'];

    // Buscar ID del equipo
    $sql_id = "SELECT id FROM registro_equipos WHERE placa_inventario = ?";
    $stmt_id = $conexion->prepare($sql_id);
    $stmt_id->bind_param("s", $placa);
    $stmt_id->execute();
    $result = $stmt_id->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => "No existe un equipo con la placa $placa."]);
        exit;
    }

    $row = $result->fetch_assoc();
    $id_equipo = $row['id'];

    // Verificar si ya está asignado
    $check = "SELECT * FROM asignacion_equipo WHERE placa_inventario = ?";
    $stmt_check = $conexion->prepare($check);
    $stmt_check->bind_param("s", $placa);
    $stmt_check->execute();
    $res_check = $stmt_check->get_result();

    if ($res_check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => "El equipo $placa ya se encuentra asignado."]);
        exit;
    }

    // Recoger todos los datos del JSON
    $nombres = $data['nombres'];
    $apellidos = $data['apellidos'];
    $identificacion = $data['identificacion'];
    $correo = $data['correo_electronico'];
    $cargo = $data['cargo'];
    $tipo_contrato = $data['tipo_contrato'];
    $area = $data['area'];
    $sede = $data['sede'];
    $extension = $data['extension_telefono'];
    $accesorios = $data['accesorios_adicionales'];
    $fecha_asignacion = $data['fecha_asignacion'];
    $fecha_devolucion = $data['fecha_devolucion'];
    $observaciones = $data['observaciones'];

    // Insertar en la base de datos
    $sql_insert = "INSERT INTO asignacion_equipo (
        id_equipo, placa_inventario, nombres, apellidos, identificacion,
        correo_electronico, cargo, tipo_contrato, area, sede, extension_telefono,
        accesorios_adicionales, fecha_asignacion, fecha_devolucion, observaciones
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("issssssssssssss", $id_equipo, $placa, $nombres, $apellidos, $identificacion, $correo,
        $cargo, $tipo_contrato, $area, $sede, $extension, $accesorios,
        $fecha_asignacion, $fecha_devolucion, $observaciones);

    if ($stmt_insert->execute()) {
        $sql_update_estado = "UPDATE registro_equipos SET estado = 'Asignado' WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update_estado);
    $stmt_update->bind_param("i", $id_equipo);
    $stmt_update->execute();
    $stmt_update->close();

echo json_encode([
'success' => true,
'message' => "Equipo $placa asignado correctamente al.\nUsuario $nombres $apellidos"
]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar: ' . $stmt_insert->error]);
    }

    $stmt_insert->close();
    $conexion->close();
}