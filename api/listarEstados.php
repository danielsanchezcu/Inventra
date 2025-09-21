<?php
    require_once("../includes/conexion.php");


    $id=$_POST['opcion'];
    $sql="select * from asignacion where estado='$id'";
    $resultado = $conexion->query($sql);
    $data = [];
    while ($fila = $resultado->fetch_assoc()){
        $data[]=$fila;
    }
    
   echo json_encode($data);

    $conexion->close();
?>
