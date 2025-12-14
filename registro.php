<?php
session_start();
require_once "includes/header_sesion.php";

// Redirigir a login si no hay sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

// Obtener rol del usuario
$rol = $_SESSION['rol'];

// Verificar permisos para este módulo (solo administradores)
$accesoPermitido = ($rol === 'administrador');

require("includes/encabezado.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Registrar Equipo</title>
    <link rel="icon" type="imagenes/Logo inventra.png" href="imagenes/Logo inventra.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styleregistroequipos.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>


<!-- Formulario de registrar equipo -->
<main class="main">
    <div class="main-header">
        <h2>Registrar Equipo</h2>
    </div>
    <div class="form-contenedor">
        <?php if ($accesoPermitido): ?>
        <div class="formulario">
            <h3 class="titulo-seccion">
                <i class='bx bx-desktop'></i> Especificaciones de equipo
            </h3>
            <form id="form-registro" enctype="multipart/form-data" novalidate>
                <div class="form-usuario">
                    <div class="form-group">
                        <label for="marca" class="required-field">Marca</label>
                        <input type="text" id="marca" name="marca" placeholder="Marca" required>
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
                        <input type="text" id="placa_inventario" name="placa_inventario" placeholder="Ej: INV-001" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ubicacion_fisica" class="required-field">Ubicación Física</label>
                        <input type="text" id="ubicacion_fisica" name="ubicacion_fisica" placeholder="Ubicación Física" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="proveedor" class="required-field">Proveedor</label>
                        <input type="text" id="proveedor" name="proveedor" placeholder="Proveedor" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="costo" class="required-field">Costo</label>
                        <input type="text" id="costo" name="costo" placeholder="$" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="numero_factura" class="required-field">Número de factura</label>
                        <input type="text" id="numero_factura" name="numero_factura" placeholder="Número de factura" required>
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

                    <div class="form-group">
                        <label for="estado" class="required-field">Estado</label>
                        <select id="estado" name="estado" required>
                            <option value="" disabled selected>Seleccione una opción</option>
                            <option value="Disponible">Disponible</option>
                            <option value="En mantenimiento">En mantenimiento</option>
                            <option value="Dado de baja">Dado de baja</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_garantia" class="required-field">Fecha fin de garantía</label>
                        <input type="date" id="fecha_garantia" name="fecha_garantia" required>
                    </div>
                    
                </div>
                
                <div class="form_equipo">
                    <h4 class="titulo-seccion">
                        <i class='bx bx-chip'></i> Especificaciones técnicas
                    </h4>
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

                        <div class="form-group">
                            <label for="imagen_equipo" class="required-field">Imagen del Equipo</label>
                            <input type="file" id="imagen_equipo" name="imagen_equipo" accept="image/*" required>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" placeholder="Ingrese cualquier observación relevante..."></textarea>
                    </div>
                </div>

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
    <?php else: ?>
    <!-- Mensaje para técnicos -->
<div class="alerta-permiso">
    <div class="icono-alerta">
        <i class='bx bx-lock-alt'></i>
    </div>
    <h3>Acceso Denegado</h3>
    <p>Lo sentimos, no tienes permisos para acceder a este módulo.</p>
    <a href="inicio.php" class="boton-ingreso">Volver al inicio</a>
</div>
<?php endif; ?>
</div>
</main>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if ($accesoPermitido): ?>
<script src="formulario.js"></script>
<?php endif; ?>

</body>
</html>
