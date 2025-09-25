<?php
require("includes/encabezado.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Asignación de Equipo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleasignacion.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="imagenes/Logo inventra.png" href="imagenes/Logo inventra.png">
</head>
<main class="main">
<div class="main-header">
  <h2>Asignación de equipo</h2>
</div>

<div class="form-contenedor">
  <div class="formulario">
    <h3 class="titulo-seccion">
      <i class="bx bx-user"></i> Información del usuario
    </h3>
    
    <form action="asignar_equipo.php" method="POST" id="asignacionForm">
      <div class="form-usuario">
        
        <div class="form-group">
          <label for="nombres" class="required-field">Nombres</label>
          <input type="text" id="nombres" name="nombres" placeholder="Ingrese nombres completos" required>
        </div>
        <div class="form-group">
          <label for="apellidos" class="required-field">Apellidos</label>
          <input type="text" id="apellidos" name="apellidos" placeholder="Ingrese apellidos completos" required>
        </div>
        <div class="form-group">
          <label for="area" class="required-field">Área</label>
          <input type="text" id="area" name="area" placeholder="Ej: Recursos Humanos" required>
        </div>

        <div class="form-group">
          <label for="sede" class="required-field">Sede</label>
          <input type="text" id="sede" name="sede" placeholder="Sede" required>
        </div>

        <div class="form-group">
          <label for="correo" class="required-field">Correo electrónico</label>
          <input type="email" id="correo" name="correo" placeholder="ejemplo@inventra.com" required>
        </div>

        <div class="form-group">
          <label for="cargo" class="required-field">Cargo</label>
          <input type="text" id="cargo" name="cargo" placeholder="Ej: Analista de RH" required>
        </div>

       <div class="form-group">
            <label for="contrato" class="required-field">Tipo de Contrato</label>
            <select id="contrato" name="contrato" required>
              <option value="" disabled selected>Seleccione una opción</option>
              <option value="planta">Planta</option>
              <option value="prestacion">Prestación de servicios</option>
              <option value="pasante">Pasante</option>
              <option value="temporal">Temporal</option>
            </select>
          </div>

        <div class="form-group">
          <label for="identificacion" class="required-field">Identificación</label>
          <input type="text" id="identificacion" name="identificacion" placeholder="Sin puntos ni guiones" required>
        </div>

        <div class="form-group">
          <label for="extension">Extensión / Teléfono </label>
          <input type="text" id="extension" name="extension" placeholder="Extensión / Teléfono">
        </div>
      </div>

       
  
      <div class="form_equipo">
        <h3 class="titulo-seccion">
          <i class="bx bx-laptop"></i> Información del equipo
        </h3>
        

        <div class="form-grid">
          <div class="form-group">
            <label for="placa_inventario" class="required-field">Placa de Inventario</label>
            <input type="text" id="placa_inventario" name="placa_inventario" placeholder="Ej: INV-001" required>
            <div id="mensaje-error" style="color: red; font-size: 12px; margin-top: 5px;"></div>
          </div>
          

          <div class="form-group">
              <label for="serial" class="required-field">Serial</label>
              <input type="text" id="serial" name="serial" placeholder="Número de serial del equipo" readonly>
          </div>

          <div class="form-group">
              <label for="marca" class="required-field">Marca</label>
              <input type="text" id="marca" name="marca" placeholder="Marca" readonly>
          </div>
                    
          <div class="form-group">
              <label for="modelo" class="required-field">Modelo</label>
              <input type="text" id="modelo" name="modelo" placeholder="Modelo" readonly>
          </div>

          <div class="form-group">
              <label for="tipo" class="required-field">Tipo de Equipo</label>
              <input type="text" id="tipo" name="tipo" placeholder="Desktop,Laptop,Workstation,Otro" readonly>
          </div>
                    
          <div class="form-group">
            <label for="estado" class="required-field">Estado</label>
            <select id="estado" name="estado" required>
              <option value="" disabled selected>Seleccione una opción</option>
              <option value="asignado">Asignado</option>
              <option value="sin-asignacion">Sin asignación</option>
              <option value="mantenimiento">En mantenimiento</option>
              <option value="baja">Dado de baja</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="accesorios" class="required-field">Accesorios adicionales</label>
            <input type="text" id="accesorios" name="accesorios" placeholder="Cargador, base refrigerante, maleta, adaptadores." required>
          </div>        

          <div class="form-group">
            <label for="fecha_asignacion" class="required-field">Fecha de Asignación</label>
            <input type="date" id="fecha_asignacion" name="fecha_asignacion" required>
          </div>
          <div class="form-group">
            <label for="fecha_devolucion">Fecha de Devolución</label>
            <input type="date" id="fecha_devolucion" name="fecha_devolucion">
            <p class="form-note">Complete solo si aplica</p>
          </div>
        </div>

        <div class="form-group" style="margin-top: 1.5rem;">
          <label for="observaciones">Observaciones</label>
          <textarea id="observaciones" name="observaciones" placeholder="Observaciones adicionales..."></textarea>
        </div>
      </div>

      <div id="mensaje" class="alerta" style="display: none;"></div>
      <div id="mensajeRespuesta" class="mensaje-respuesta"></div>
      <div class="buttons">
        <button type="reset">
          <i class="bx bx-x"></i> Cancelar
        </button>
        <button type="submit">
          <i class="bx bx-save icono-guardar"></i> Guardar
        </button>
      </div>
    </form>
  </div>
    </div>
</main>


<script src="asignacion.js"></script>
<script src="asignar.js"></script>

</body>
</html>