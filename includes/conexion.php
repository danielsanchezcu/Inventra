<?php
$servername = "localhost";
$username = "root";
$password = "";
$bd = "inventrabd";
// Create connection
$conexion = new mysqli($servername, $username, $password, $bd);


// Check connection
if ($conexion->connect_error) {
  die("Connection failed: " . $conexion->connect_error);
}
?>