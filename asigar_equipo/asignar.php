<?php
include ("conexion.php");
include ("menu_principal.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inventra Web - Asignar Equipo</title>
    <link rel="stylesheet" href="estilo_asignar.css">
   
</head>
      <main class="main">
        <div class="main-header">
          <h2>ASIGNAR EQUIPO</h2>
          <h3>INVENTRA</h3>
        </div>
      <div class="form-contenedor">
        <div class="formulario">
            <h3>USUARIO ASIGNACIÓN</h3>
            <form action="asignar_equipo.php" method="POST" id="asignacionForm">
                <div class="form-usuario">
                    <div class="form-group">
                        <label for="nombre" class="required-field">Nombres</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingrese nombres completos" required>
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
                        <label for="correo" class="required-field">Correo electrónico</label>
                        <input type="email" id="correo" name="correo" placeholder="ejemplo@inventra.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cargo" class="required-field">Cargo</label>
                        <input type="text" id="cargo" name="cargo" placeholder="Ej: Analista de RH" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="identificacion" class="required-field">Identificación del colaborador</label>
                        <input type="text" id="identificacion" name="identificacion" placeholder="Número de identificación" required>
                        <p class="form-note">Ingrese el documento sin puntos ni guiones</p>
                    </div>
                </div>
                
                <div class="form_equipo">
                    <h3>DATOS DEL EQUIPO</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="equipo" class="required-field">Equipo (Placa de Inventario)</label>
                            <input type="text" id="equipo" name="equipo" placeholder="Ej: INV-001" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="serial" class="required-field">Serial</label>
                            <input type="text" id="serial" name="serial" placeholder="Número de serie del equipo" required>
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
                            <label for="fecha_asignacion" class="required-field">Fecha de Asignación</label>
                            <input type="date" id="fecha_asignacion" name="fecha_asignacion" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="fecha_devolucion">Fecha de Devolución</label>
                            <input type="date" id="fecha_devolucion" name="fecha_devolucion">
                            <p class="form-note">Complete solo si aplica</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" placeholder="Ingrese cualquier observación relevante..."></textarea>
                    </div>
                </div>
                
                <div class="buttons">
                    <button type="reset">Cancelar</button>
                    <button type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
      </main>

</body>
</html>