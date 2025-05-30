<?php 
include("conexion.php");

//Recoger datos del formulario

$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$serial = $_POST['serial'];
$tipo = $_POST['tipo'];
$estado = $_POST['estado'];
$disco_duro = $_POST['disco_duro'];
$placa_inventario = $_POST['placa_inventario'];
$ram = $_POST['ram'];
$fecha_adquisicion = $_POST['fecha_adquisicion'];
$procesador = $_POST['procesador'];
$sistema_operativo = $_POST['sistema_operativo'];
$observaciones = $_POST['observaciones'];

// Preparar la consulta SQL
$sql = "INSERT INTO equipo (marca, modelo, serial, tipo, estado, disco_duro, placa_inventario, ram, fecha_adquisicion, procesador, sistema_operativo, observaciones)
VALUES ('$marca', '$modelo', '$serial', '$tipo', '$estado', '$disco_duro', '$placa_inventario', '$ram', '$fecha_adquisicion', '$procesador', '$sistema_operativo', '$observaciones')";

if ($conn->query($sql) === TRUE) {
    echo "Nuevo registro creado exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>