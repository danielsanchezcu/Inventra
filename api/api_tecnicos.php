<?php
// api/api_tecnicos.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once("../includes/conexion.php");

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// === CREAR técnico ===
if ($method === 'POST' && $action === 'create') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['nombres']) || !isset($data['apellidos']) || !isset($data['identificacion'])) {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        exit;
    }

    $stmt = $conexion->prepare("INSERT INTO tecnicos (nombres, apellidos, identificacion, telefono, correo, especialidad, estado, observaciones) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssss",
        $data['nombres'],
        $data['apellidos'],
        $data['identificacion'],
        $data['telefono'],
        $data['correo'],
        $data['especialidad'],
        $data['estado'],
        $data['observaciones']
    );

    if ($stmt->execute()) {
        // 🔔 Crear notificación
        $fecha = date('Y-m-d H:i:s');
        $mensaje = "Se ha registrado un nuevo técnico: <b>" . $data['nombres'] . " " . $data['apellidos'] . "</b>.";
        $modulo = "Registro de Técnico";

        $conexion->query("INSERT INTO notificaciones (mensaje, modulo, fecha, leido)
                          VALUES ('$mensaje', '$modulo', '$fecha', 0)");

        echo json_encode(["success" => true, "message" => "Técnico registrado correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
}

// === LISTAR técnicos ===
elseif ($method === 'GET' && $action === 'read') {
    $result = $conexion->query("SELECT * FROM tecnicos ORDER BY fecha_registro DESC");
    $tecnicos = [];

    while ($row = $result->fetch_assoc()) {
        $tecnicos[] = $row;
    }

    echo json_encode($tecnicos);
}

// === EDITAR técnico ===
elseif ($method === 'PUT' && $action === 'update') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['id_tecnico'])) {
        echo json_encode(["success" => false, "message" => "ID del técnico es requerido"]);
        exit;
    }

    $stmt = $conexion->prepare("UPDATE tecnicos 
                                SET nombres=?, apellidos=?, identificacion=?, telefono=?, correo=?, especialidad=?, estado=?, observaciones=? 
                                WHERE id_tecnico=?");
    $stmt->bind_param(
        "ssssssssi",
        $data['nombres'],
        $data['apellidos'],
        $data['identificacion'],
        $data['telefono'],
        $data['correo'],
        $data['especialidad'],
        $data['estado'],
        $data['observaciones'],
        $data['id_tecnico']
    );

    if ($stmt->execute()) {
        // Crear notificación
        $fecha = date('Y-m-d H:i:s');
        $mensaje = "El técnico <b>" . $data['nombres'] . " " . $data['apellidos'] . "</b> ha sido actualizado.";
        $modulo = "Actualización de Técnico";

        $conexion->query("INSERT INTO notificaciones (mensaje, modulo, fecha, leido)
                        VALUES ('$mensaje', '$modulo', '$fecha', 0)");

        echo json_encode(["success" => true, "message" => "Técnico actualizado correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
}

// === ELIMINAR técnico ===
elseif ($method === 'DELETE' && $action === 'delete') {
    if (!isset($_GET['id'])) {
        echo json_encode(["success" => false, "message" => "ID no proporcionado"]);
        exit;
    }

    $id = intval($_GET['id']);

    // Obtener nombre antes de eliminar
    $nombre_tecnico = "Técnico desconocido";
    $res = $conexion->prepare("SELECT nombres, apellidos FROM tecnicos WHERE id_tecnico = ?");
    $res->bind_param("i", $id);
    $res->execute();
    $resultado = $res->get_result();
    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $nombre_tecnico = $fila['nombres'] . " " . $fila['apellidos'];
    }
    $res->close();

    $stmt = $conexion->prepare("DELETE FROM tecnicos WHERE id_tecnico=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // 🔔 Crear notificación
        $fecha = date('Y-m-d H:i:s');
        $mensaje = "El técnico <b>$nombre_tecnico</b> fue eliminado del sistema.";
        $modulo = "Eliminación de Técnico";

        $conexion->query("INSERT INTO notificaciones (mensaje, modulo, fecha, leido)
                        VALUES ('$mensaje', '$modulo', '$fecha', 0)");

        echo json_encode(["success" => true, "message" => "Técnico eliminado correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
}

// === ACCIÓN NO VÁLIDA ===
else {
    echo json_encode(["success" => false, "message" => "Acción no válida"]);
}

$conexion->close();
?>
