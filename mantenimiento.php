<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra Web - Mantenimiento</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="mantenimientos.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>


<body>
    <div class="barrasuperior">
<div class="busqueda">
    <i class='bx bx-search icono-busqueda'></i>
    <input type="text" placeholder="Buscar..." />
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
    

    <nav class="menu">

    <a href="index.php" class="menu-item">
            <i class='bx bxs-home'></i>
            <span>Inicio</span>
        </a>

        <a href="panelcontrol.php" class="menu-item">
            <i class='bx bx-bar-chart-alt-2'></i>
            <span>Dashboard</span>
        </a>

<!-- Menú desplegable INVENTARIO -->
    <details class="menu-group">
    <summary>
            <i class='bx bx-desktop'></i>
            <span>Dispositivos</span>
            <i class='bx bx-chevron-right arrow'></i>
    </summary>
        <div class="submenu">
            <a href="registro.php"> Registrar Equipo</a>
            <a href="asignar.php"> Asignar Equipo</a>
            <a href="consultar.php"> Consultar Inventario</a>
        </div>
    </details>

    <a href="mantenimiento.php" class="menu-item">
    <i class='bx bxs-cog'></i>
    <span>Mantenimiento</span>
    </a>

    <a href="informes.php" class="menu-item">
    <i class='bx bxs-food-menu'></i>
    <span>Informes</span>
    </a>

    <a href="inicio.html" class="menu-item">
    <i class='bx bxs-exit'></i>
    <span>Salir</span>
    </a>
</nav>
</aside>
<!-- Contenido principal -->

<?php
// mantenimiento.php
// Aquí luego podrás incluir tu conexión a la BD y lógica en PHP
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo de Mantenimiento</title>
</head>
<body>

    <h1>Módulo de Mantenimiento</h1>

    <!-- Formulario para registrar mantenimiento -->
    <section>
        <h2>Registrar Mantenimiento</h2>
        <form action="procesar_mantenimiento.php" method="POST">
            <label for="id_equipo">Equipo:</label>
            <input type="text" id="id_equipo" name="id_equipo" required><br>

            <label for="tipo">Tipo de mantenimiento:</label>
            <select id="tipo" name="tipo" required>
                <option value="Preventivo">Preventivo</option>
                <option value="Correctivo">Correctivo</option>
            </select><br>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea><br>

            <label for="responsable">Responsable:</label>
            <input type="text" id="responsable" name="responsable" required><br>

            <label for="fecha_programada">Fecha programada:</label>
            <input type="date" id="fecha_programada" name="fecha_programada" required><br>

            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="Pendiente">Pendiente</option>
                <option value="En proceso">En proceso</option>
                <option value="Finalizado">Finalizado</option>
            </select><br>

            <button type="submit">Guardar</button>
        </form>
    </section>

    <!-- Listado de mantenimientos -->
    <section>
        <h2>Listado de Mantenimientos</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipo</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Responsable</th>
                    <th>Fecha programada</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se llenarán los registros desde la BD -->
                <?php
                // Ejemplo de registros simulados (esto luego se reemplaza por consulta a MySQL)
                $mantenimientos = [
                    ["id" => 1, "equipo" => "PC-001", "tipo" => "Preventivo", "descripcion" => "Limpieza interna", "responsable" => "Juan Pérez", "fecha" => "2025-09-21", "estado" => "Pendiente"],
                    ["id" => 2, "equipo" => "PC-002", "tipo" => "Correctivo", "descripcion" => "Cambio de disco duro", "responsable" => "Ana Gómez", "fecha" => "2025-09-22", "estado" => "En proceso"],
                ];

                foreach ($mantenimientos as $m) {
                    echo "<tr>";
                    echo "<td>{$m['id']}</td>";
                    echo "<td>{$m['equipo']}</td>";
                    echo "<td>{$m['tipo']}</td>";
                    echo "<td>{$m['descripcion']}</td>";
                    echo "<td>{$m['responsable']}</td>";
                    echo "<td>{$m['fecha']}</td>";
                    echo "<td>{$m['estado']}</td>";
                    echo "<td>
                            <a href='editar_mantenimiento.php?id={$m['id']}'>Editar</a> |
                            <a href='eliminar_mantenimiento.php?id={$m['id']}'>Eliminar</a>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

</body>
</html>
