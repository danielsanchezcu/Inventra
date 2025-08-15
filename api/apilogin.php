<?php
header('Content-Type: application/json');

// Incluir conexión
require_once("../includes/conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

// Validar si los datos existen
$correo = isset($data['correo']) ? trim($data['correo']) : '';
$contrasena = isset($data['contrasena']) ? $data['contrasena'] : '';

if (empty($correo) || empty($contrasena)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Faltan datos"]);
    exit;
}

// Consulta segura con prepared statements
$sql = "SELECT * FROM usuario WHERE correo = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error en la consulta"]);
    exit;
}

$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
    exit;
}

$usuario = $result->fetch_assoc();

// Verificar contraseña

if (!password_verify($contrasena, $usuario['contrasena'])) {
    echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
    exit;
}

// Validación de permisos
if ($usuario['permisos'] !== 'administrador') {
    echo json_encode(["success" => false, "message" => "No tienes permisos para acceder"]);
    exit;
}


echo json_encode([
    "success" => true,
    "id_usuario" => $usuario['id_usuario'],
    "nombre" => $usuario['nombre'],
    "correo" => $usuario['correo'],
    "permisos" => $usuario['permisos']
]);
