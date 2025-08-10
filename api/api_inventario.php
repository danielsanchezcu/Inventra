<?php
// Configuración de cabeceras para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(204);
    exit;
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluir el archivo de conexión
require_once("../includes/conexion.php");

// Verificar conexión
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $conexion->connect_error]);
    exit;
}

// Capturar filtros
$busqueda = isset($_GET['busqueda']) ? $conexion->real_escape_string($_GET['busqueda']) : '';
$area = isset($_GET['area']) ? $conexion->real_escape_string($_GET['area']) : 'Todos';
$tipo = isset($_GET['tipo']) ? $conexion->real_escape_string($_GET['tipo']) : 'Todos';

// Construir consulta
$query = "SELECT 
            ae.id_asignacion,
            ae.nombres,
            ae.apellidos,
            ae.identificacion,
            ae.tipo_contrato,
            ae.area,
            ae.fecha_asignacion,
            ae.placa_inventario,
            e.serial,
            e.tipo_equipo,
            e.marca,
            e.modelo,
            e.estado
        FROM asignacion_equipo ae 
        JOIN registro_equipos e ON ae.placa_inventario = e.placa_inventario 
        WHERE 1=1";

if (!empty($busqueda)) {
    $query .= " AND (ae.nombres LIKE '%$busqueda%' 
                OR ae.apellidos LIKE '%$busqueda%' 
                OR ae.identificacion LIKE '%$busqueda%')";
}

if ($area !== 'Todos') {
    $query .= " AND ae.area = '$area'";
}

if ($tipo !== 'Todos') {
    $query .= " AND e.tipo_equipo = '$tipo'";
}

// Ejecutar consulta
$result = $conexion->query($query);

$asignaciones = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $asignaciones[] = $row;
    }
}

// Devolver JSON
echo json_encode($asignaciones, JSON_UNESCAPED_UNICODE);

// Cerrar conexión
$conexion->close();
