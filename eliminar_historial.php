<?php
include 'includes/conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM historial_mantenimientos WHERE id_mantenimiento = $id";

    if ($conexion->query($sql) === TRUE) {
        header("Location: listadoMantenimiento.php?msg=eliminado");
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
} else {
    header("Location: listadoMantenimiento.php");
}
?>
