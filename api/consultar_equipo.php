<?php
require_once("../includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["placa"])) {
    $placa = $conexion->real_escape_string($_GET["placa"]);

    $sql = "SELECT serial, marca, modelo, tipo_equipo FROM registro_equipos WHERE placa_inventario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $placa);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $equipo = $resultado->fetch_assoc();
        echo json_encode([
            "success" => true,
            "serial" => $equipo["serial"],
            "marca" => $equipo["marca"],
            "modelo" => $equipo["modelo"],
            "tipo_equipo" => $equipo["tipo_equipo"]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "No se encontró equipo"]);
    }

    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(["success" => false, "message" => "Solicitud inválida"]);
}
?>

