<?php
include("conexion.php");

$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$area = $_POST['area'];
$correo = $_POST['correo'];
$cargo = $_POST['cargo'];
$identificacion = $_POST['identificacion'];
$equipo = $_POST['equipo'];
$serial = $_POST['serial'];
$estado = $_POST['estado'];
$fecha_asignacion = $_POST['fecha_asignacion'];
$fecha_devolucion = isset($_POST['fecha-devolucion']) ? $_POST['fecha-devolucion'] : null;
$observaciones = $_POST['observaciones'];

$sql_asignar = "INSERT INTO asignacion_prueba (nombre, apellidos, area, correo, cargo, identificacion, equipo, serial, estado, fecha_asignacion, fecha_devolucion, observaciones)
VALUES ('$nombre', '$apellidos', '$area', '$correo', '$cargo', '$identificacion', '$equipo', '$serial', '$estado', '$fecha_asignacion', " . ($fecha_devolucion ? "'$fecha_devolucion'" : "NULL") . ",  '$observaciones')";


if ($conn->query($sql_asignar) === TRUE) {
    echo "Asignación registrada exitosamente para el usuario: $nombre $apellidos";
} else {
    echo "Error: " . $sql_asignar . "<br>" . $conn->error;
}
$conn->close();
?>
