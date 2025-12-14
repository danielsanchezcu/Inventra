<?php
// Inicia sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tiempo máximo de inactividad en segundos (15 min)
$tiempoInactividad = 600;

// Evitar que el navegador almacene la página en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Validar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) { // coincide con tu apilogin.php
    header("Location: ../index.php"); // Ajusta la ruta según tu estructura
    exit;
}

// Expiración automática por inactividad
if (isset($_SESSION['ultimo_acceso']) && (time() - $_SESSION['ultimo_acceso']) > $tiempoInactividad) {
    session_unset();
    session_destroy();
    header("Location: ../index.php?mensaje=sesion_expirada");
    exit;
}

// Actualiza el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();
?>
