<?php
header('Content-Type: application/json');
include 'includes/conexion.php'; // Asegúrate que la ruta esté bien

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 🧠 Validar campos requeridos
    $campos = ['nombre', 'descripcion', 'precio', 'cantidad'];
    foreach ($campos as $campo) {
        if (empty($_POST[$campo])) {
            echo json_encode([
                'success' => false,
                'message' => "Por favor completa todos los campos antes de continuar."
            ]);
            exit;
        }
    }

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // 💾 Guardar el repuesto en la base de datos
    $sql = "INSERT INTO repuestos (nombre, descripcion, precio, cantidad)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $ok = $stmt->execute([$nombre, $descripcion, $precio, $cantidad]);

    if ($ok) {
        echo json_encode([
            'success' => true,
            'message' => 'Repuesto registrado exitosamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al registrar el repuesto en la base de datos.'
        ]);
    }
}
