<?php
// Conexión a la base de datos
require_once("../includes/conexion.php");

header("Content-Type: application/json");

if ($conexion->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "❌ Error de conexión a la base de datos."
    ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Procesar imagen si existe
    $imagen_nombre = null;
    if (isset($_FILES['imagen_equipo']) && $_FILES['imagen_equipo']['error'] === 0) {
        $imagen_nombre = uniqid() . "_" . basename($_FILES["imagen_equipo"]["name"]);
        $ruta_destino = "../uploads/" . $imagen_nombre;
        move_uploaded_file($_FILES["imagen_equipo"]["tmp_name"], $ruta_destino);
    }

    // Recibir datos del formulario
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $serial = $_POST['serial'];
    $placa = $_POST['placa_inventario'];
    $ubicacion = $_POST['ubicacion_fisica'];
    $proveedor = $_POST['proveedor'];
    $costo = $_POST['costo'];
    $factura = $_POST['numero_factura'];
    $tipo = $_POST['tipo'];
    $teclado = $_POST['teclado'] ?? null;
    $mouse = $_POST['mouse'] ?? null;
    $estado = $_POST['estado'] ?? 'Disponible';
    $garantia = $_POST['fecha_garantia'] ?? null;
    $procesador = $_POST['procesador'];
    $sistema = $_POST['sistema_operativo'];
    $ram = $_POST['ram'];
    $disco = $_POST['disco_duro'];
    $fecha_registro = $_POST['fecha_adquisicion'];
    $observaciones = $_POST['observaciones'] ?? null;

    // === Validar duplicados
    $errores = [];

    // Verificar duplicado de serial
    $checkSerial = $conexion->prepare("SELECT id FROM registro_equipos WHERE serial = ?");
    $checkSerial->bind_param("s", $serial);
    $checkSerial->execute();
    $checkSerial->store_result();
    if ($checkSerial->num_rows > 0) {
        $errores[] = "⚠️ Registro fallido: ya existe un equipo con este serial";
    }
    $checkSerial->close();

    // Verificar duplicado de placa
    $checkPlaca = $conexion->prepare("SELECT id FROM registro_equipos WHERE placa_inventario = ?");
    $checkPlaca->bind_param("s", $placa);
    $checkPlaca->execute();
    $checkPlaca->store_result();
    if ($checkPlaca->num_rows > 0) {
        $errores[] = "⚠️ Registro fallido: ya existe un equipo con esa placa de  inventario";
    }
    $checkPlaca->close();

    if (!empty($errores)) {
        echo json_encode([
            "success" => false,
            "message" => implode("\n", $errores)
        ]);
        exit;
    }

    // === Insertar registro si no hay duplicados
    $sql = "INSERT INTO registro_equipos (marca, modelo, serial, placa_inventario, ubicacion_fisica, proveedor,costo, numero_factura, tipo_equipo, teclado, mouse, estado, fecha_garantia, procesador, sistema_operativo, ram, disco_duro, fecha_registro, imagen_equipo, observaciones) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssdsssssssssssss", $marca, $modelo, $serial, $placa, $ubicacion, $proveedor, $costo, $factura, $tipo, $teclado, $mouse, $estado, $garantia, $procesador, $sistema, $ram, $disco, $fecha_registro, $imagen_nombre, $observaciones);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "✅ Equipo <strong>$placa</strong> registrado correctamente."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "❌ Error al registrar el equipo: " . $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "❌ Método no permitido."
    ]);
}

$conexion->close();
?>
