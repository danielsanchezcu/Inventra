<?php
header("Content-Type: application/json");
require_once("../includes/conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['placa']) || empty($data['placa'])) {
    echo json_encode(["success" => false, "message" => "Placa no proporcionada"]);
    exit;
}

$placa = $conexion->real_escape_string($data['placa']);

$sql = "SELECT serial, marca, modelo, tipo_equipo FROM registro_equipos WHERE placa_inventario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $placa);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $equipo = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "serial" => $equipo["serial"],
        "marca" => $equipo["marca"],
        "modelo" => $equipo["modelo"],
        "tipo_equipo" => $equipo["tipo_equipo"]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Equipo no encontrado"]);
}

$stmt->close();
$conexion->close();
?>