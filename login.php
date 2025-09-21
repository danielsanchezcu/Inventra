<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio de Sesión | Inventra</title>
  <link rel="icon" type="imagenes/Logo inventra.png" href="imagenes/Logo inventra.png">

  <!-- Estilos -->
  <link rel="stylesheet" href="stylelogin.css">

  <!-- Tipografía Montserrat -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

  <!-- Íconos Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
  <div class="contenedor-principal">
    <div class="contenedor-login">

      <!-- Logo -->
      <div class="logo-inventra">
        <img src="imagenes/logo inventra.png" alt="Logo Inventra">
      </div>

      <!-- Título -->
      <h2>Iniciar Sesión</h2>

      <!-- Formulario -->
      <form id="form-login">
        <div class="grupo-campo">
          <input type="text" id="correo" name="correo" placeholder="Correo" required>
          <i class='bx bx-user'></i>
        </div>

        <div class="grupo-campo">
          <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
          <i class='bx bx-show-alt icono-ojo' id="toggleClave"></i>
        </div>
        <div id="mensaje" class="mensaje-oculto"></div>
        <button type="submit" class="boton-ingresar">Ingresar</button>
        
        <!-- Script externo de login (API) -->
        <script src="login.js"></script>
      </form>

      <!-- Pie -->
      <div class="pie-login">
        ¿Olvidaste tu contraseña? <a href="#">Recupérala aquí</a>
      </div>
        <h3>© 2025 | INVENTRA</h3>
        <h3>Desarrollado por Daniel Felipe Sánchez Currea</h3>
    </div>
  </div>
</body>
</html>
