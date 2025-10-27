<?php
$servername = "localhost";
$username = "root";
$password = "";
$bd = "inventrabd";

// === Conexión MYSQLI (para módulos antiguos) ===
$conexion = new mysqli($servername, $username, $password, $bd);
if ($conexion->connect_error) {
  die("Connection failed: " . $conexion->connect_error);
}

// === Conexión PDO (para nuevos módulos como 'Informes') ===
try {
  $pdo = new PDO("mysql:host=$servername;dbname=$bd;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  // Si falla PDO, no interrumpe el resto del sistema
  $pdo = null;
}
?>
