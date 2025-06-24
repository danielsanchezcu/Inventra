<?php
include ("conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-registro.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>


<body>
    <div class="barrasuperior">
        <div class="busqueda">
            <input type="text" placeholder="Buscar...">
            <button type="submit"><i class='bx bx-search'></i></button>
        </div>

        <div class="acciones">
            <i class='bx bx-bell'></i>
            <i class='bx bx-user'></i>
        </div>
    </div>
    
<!-- Barra lateral -->
    <aside class="lateral">
        <div class="logo">
          <img src="imagenes/Logo inventra.png" alt="Logo">
        </div>

<!-- Imagen inferior de la barra lateral-->
        <div class="detalle1">
            <img src="imagenes/detalle1.png" alt=""> 
        </div>

    <nav class="menu">
        <a href="dash.html" class="menu-item">
            <i class='bx bxs-home'></i>
            <span>Dashboard</span>
        </a>

<!-- Menú desplegable INVENTARIO -->
    <details class="menu-group">
      <summary>
            <i class='bx bx-desktop'></i>
            <span>Iventario</span>
            <i class='bx bx-chevron-right arrow'></i>
      </summary>
        <div class="submenu">
            <a href="registro.php">· Registrar Equipo</a>
            <a href="asignar.php">· Asignar Equipo</a>
            <a href="consultar.php">· Consultar Inventario</a>
        </div>
    </details>

    <a href="mantenimiento.html" class="menu-item">
      <i class='bx bxs-cog'></i>
      <span>Mantenimiento</span>
    </a>

    <a href="informes.html" class="menu-item">
      <i class='bx bxs-food-menu'></i>
      <span>Informes</span>
    </a>

    <a href="inicio.html" class="menu-item">
      <i class='bx bxs-exit'></i>
      <span>Salir</span>
    </a>
  </nav>
  </aside>
</body>

<main class="main">
        <div class="main-header">
        </div>
      <div class="form-contenedor">
        <div class="formulario">
            <h3>REGISTRO DE EQUIPO</h3>
            <form action="registrar_equipo.php" method="POST">
                <div class="form-usuario">
                    <div class="form-group">
                        <label for="nombre" class="required-field">Marca</label>
                        <input type="text" id="nombre" name="marca" placeholder="Marca" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modelo" class="required-field">Modelo</label>
                        <input type="text" id="modelo" name="modelo" placeholder="Modelo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="serial" class="required-field">Serial</label>
                        <input type="text" id="serial" name="serial" placeholder="Serial" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="placa_inventario" class="required-field">Placa de Inventario</label>
                        <input type="text" id="placa_inventario" name="placa_inventario" placeholder="Ej: INV - 001" required>
                    </div>
                    
                   <div class="form-group">
                            <label for="estado" class="required-field">Estado</label>
                            <select id="estado" name="estado" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="disponible">Disponible</option>
                                <option value="asignado">Asignado</option>
                                <option value="en mantenimiento">En mantenimiento</option>
                                <option value="baja">Dado de baja</option>
                            </select>
                        </div>

                   <div class="form-group">
                            <label for="tipo" class="required-field">Tipo de Equipo</label>
                            <select id="tipo" name="tipo" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Desktop">Desktop</option>
                                <option value="Laptop">Laptop</option>
                                <option value="WorkStation">WorkStation</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        
                
                </div>
                
                <div class="form_equipo">
                    <h3>ESPECIFICACIONES TECNICAS</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="procesador" class="required-field">Procesador</label>
                            <input type="text" id="procesador" name="procesador" placeholder="Procesador" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="sistema_operativo" class="required-field">Sistema Operativo</label>
                            <input type="text" id="sistema_operativo" name="sistema_operativo" placeholder="Sistema Operativo" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ram" class="required-field">Memoria RAM</label>
                            <select id="ram" name="ram" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="8">8 GB</option>
                                <option value="16">16 GB</option>
                                <option value="32">32 GB</option>
                                <option value="64">64 GB</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="disco_duro" class="required-field">Disco Duro</label>
                            <select id="disco_duro" name="disco_duro" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="128">128 GB</option>
                                <option value="256">256 GB</option>
                                <option value="500">500 GB</option>
                                <option value="1 tb">1 TB</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_adquisicion" class="required-field">Fecha de Registro</label>
                            <input type="date" id="fecha_adquisicion" name="fecha_adquisicion" required>
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