<?php
session_start();
session_unset();   // limpia variables
session_destroy(); // destruye la sesión
header("Location: login.php"); // redirige al login
exit();
?>
