<?php
include ("conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Asignar Equipo</title>
    <link rel="stylesheet" href="estilo_asignar.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>


<body>
    <aside class="lateral">
        <div class="logo">
          <img src="imagenes/Logo inventra.png" alt="Logo">
        </div>

        <div class="detalle1">
            <img src="imagenes/detalle1.png" alt="">
        </div>

    <div class="menu-item">

         <a class="secciones" href="dash.html"><i class='bx bxs-home' ></i>PANEL</a>
          
        <input type="checkbox" id="inventario-toggle">
        <label for="inventario-toggle" class="menu-toggle-label">
        <span><i class='bx bx-desktop'></i>INVENTARIO</span>
        <span class="arrow">▶</span>
        </label>

      <div class="submenu">
        <a class="menu-link" href="registro.php">·Registrar Equipo</a>
        <a class="menu-link" href="asignar.php">·Asignar Equipo</a>
        <a class="menu-link" href="consultar.php">·Consultar Inventario</a>
      </div>

        <a class="secciones" href="mantenimiento.html"><i class='bx bxs-cog'></i>MANTENIMIENTO</a>
        <a class="secciones"  href="informes.html"><i class='bx bxs-food-menu'></i>INFORMES</a>
        <a class="secciones" href="inicio.html"><i class='bx bxs-exit'></i>SALIR</a>
    </div>       
    </aside>
    
    <div class="detalle2">
        <img src="imagenes/detalle2.png" alt="">
    </div>

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