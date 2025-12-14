<?php
$base = '/Inventra-final/';
?>

<?php
// Configuración base global para Inventra

// Detecta automáticamente si se usa HTTP o HTTPS
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Construye la URL base completa
$base = $protocolo . $_SERVER['HTTP_HOST'] . "/";

// Ejemplo de resultado: http://localhost:8080/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="imagenes/Logo inventra.png" href="imagenes/Logo inventra.png">
    

    <!-- Fuentes y librerías externas -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!--Font Awesome (versión con integrity y referrerpolicy) -->
    <link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Tx1DE7Ku0M1trdD+hJ7XBRsQPU8y3sWfl8bE03m8/XoxxHybvRnx80OeTqAC/2LNk05BdsbVOR/n4N92gH6Yvw=="
        crossorigin="anonymous" 
        referrerpolicy="no-referrer"
    />

    <!-- Bootstrap y DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.css">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />

    <!-- Estilos locales -->
    <link rel="stylesheet" href="<?= $base ?>css/encabezado.css">
    <link rel="stylesheet" href="<?= $base ?>css/modalcerrar.css">

    <!-- Estilo global para asegurar la tipografía -->
    <style>
        * {
            font-family: "Montserrat", sans-serif !important;
        }
    </style>
</head>

<body>
    <!-- Barra superior -->
<div class="barrasuperior">
    <div class="busqueda">
        <i class='bx bx-search icono-busqueda'></i>
        <input id="buscador" type="text" placeholder="Buscar módulo..." autocomplete="off" />
        <ul id="resultados-busqueda" class="resultados"></ul>
    </div>

<div class="acciones">
    <div class="campana">
        <i class='bx bx-bell' id="campana-icono"></i>
        <span id="contador-notificaciones" class="contador"></span>
        <ul id="lista-notificaciones" class="notificaciones"></ul>
    </div>
</div>
</div>

<!-- 🔹 ESTILOS -->
<style>
.busqueda {
    position: relative;
    display: flex;
    align-items: center;
}

.busqueda input {
    padding-left: 35px;
}

.icono-busqueda {
    position: absolute;
    left: 10px;
    color: #888;
    font-size: 18px;
}

.resultados {
    position: absolute;
    top: 110%;
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    list-style: none;
    margin: 0;
    padding: 8px 0;
    z-index: 200;
    display: none;
    animation: fadeIn 0.15s ease-in-out;
}

.resultados li {
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.2s, transform 0.1s;
    font-size: 15px;
    color: #333;
}

.resultados li:hover {
    background-color: #f5f7ff;
    transform: scale(1.01);
}

.resultados i {
    font-size: 18px;
    color: #4b6cb7;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-3px); }
    to { opacity: 1; transform: translateY(0); }
}

.campana {
    position: relative;
}

.contador {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    font-size: 10px;
    padding: 2px 6px;
    display: none;
    box-shadow: 0 0 4px rgba(0,0,0,0.15);
}

/* Contenedor general */
.notificaciones {
    position: absolute;
    top: 120%;
    right: 0;
    width: 350px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
    list-style: none;
    margin: 0;
    padding: 10px 0;
    display: none;
    z-index: 999;
    animation: fadeIn 0.25s ease;
    overflow: hidden;
    max-height: 420px;
    overflow-y: auto;
}

/* Ítem de notificación */
.notificaciones li {
    padding: 12px 16px;
    border-bottom: 1px solid #f3f3f3;
    transition: background 0.25s ease;
    cursor: default;
}

.notificaciones li:hover {
    background: #f7faff;
}

.notificaciones li:last-child {
    border-bottom: none;
}

/* Texto principal */
.notificaciones li p {
    margin: 0;
    font-weight: 500;
    font-size: 13.5px;
    color: #222;
    line-height: 1.4;
}

/* Información secundaria */
.notificaciones li small {
    display: block;
    color: #888;
    font-size: 11px;
    font-style: italic;
    margin-top: 4px;
}

/* No leídas resaltadas */
.notificaciones li.no-leida {
    background: #eef5ff;
    border-left: 4px solid #3b82f6;
    font-weight: 600;
    border-radius: 10px;
    margin: 4px 8px;
}

/* Scroll elegante */
.notificaciones::-webkit-scrollbar {
    width: 6px;
}
.notificaciones::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 6px;
}

/* Efecto de entrada */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Mensaje cuando no hay notificaciones */
.sin-notificaciones {
    text-align: center;
    color: #777;
    padding: 25px 10px;
    font-size: 14px;
}

.sin-notificaciones i {
    font-size: 22px;
    margin-bottom: 8px;
    color: #bbb;
    display: block;
}



</style>

<!-- 🔹 FUNCIONALIDAD BUSCAR-->
<script>
const modulos = [
    { nombre: "Inicio", url: "inicio.php" },
    { nombre: "Dashboard", url: "panelcontrol.php" },
    { nombre: "Registrar Equipo", url: "registro.php" },
    { nombre: "Asignar Equipo", url: "asignar.php" },
    { nombre: "Consultar Inventario", url: "consultar-inventario/index.php" },
    { nombre: "Registrar Mantenimiento", url: "mantenimiento.php" },
    { nombre: "Agregar Repuestos", url: "repuestos.php" },
    { nombre: "Registrar Técnicos", url: "registrar_tecnico.php" },
    { nombre: "Historial Mantenimientos", url: "listadoMantenimiento.php" },
    { nombre: "Informes", url: "informes.php" },
];

const buscador = document.getElementById('buscador');
const resultados = document.getElementById('resultados-busqueda');

// 🔍 Filtrar resultados en tiempo real
buscador.addEventListener('keyup', () => {
    const texto = buscador.value.toLowerCase().trim();
    resultados.innerHTML = '';

    if (texto === '') {
        resultados.style.display = 'none';
        return;
    }

    const filtrados = modulos.filter(m => m.nombre.toLowerCase().includes(texto));

    if (filtrados.length === 0) {
        resultados.style.display = 'none';
        return;
    }

    filtrados.forEach(m => {
    const li = document.createElement('li');
    const icono = document.createElement('i');
    
    // Asigna íconos según el módulo (Boxicons)
    switch (m.nombre) {
        case "Inicio": icono.className = 'bx bx-home-alt'; break;
        case "Dashboard": icono.className = 'bx bx-bar-chart'; break;
        case "Registrar Equipo": icono.className = 'bx bx-laptop'; break;
        case "Asignar Equipo": icono.className = 'bx bx-transfer-alt'; break;
        case "Consultar Inventario": icono.className = 'bx bx-search-alt-2'; break;
        case "Registrar Mantenimiento": icono.className = 'bx bx-wrench'; break;
        case "Agregar Repuestos": icono.className = 'bx bx-package'; break;
        case "Registrar Técnicos": icono.className = 'bx bx-id-card'; break;
        case "Historial Mantenimientos": icono.className = 'bx bx-history'; break;
        case "Informes": icono.className = 'bx bx-pie-chart-alt'; break;
        default: icono.className = 'bx bx-folder';
    }

    li.appendChild(icono);
    li.appendChild(document.createTextNode(m.nombre));

    li.addEventListener('click', () => {
        window.location.href = m.url;
    });

    resultados.appendChild(li);
});


    resultados.style.display = 'block';
});

// Ocultar lista si el usuario hace clic fuera del buscador
document.addEventListener('click', (e) => {
    if (!e.target.closest('.busqueda')) {
        resultados.style.display = 'none';
    }
});

// Atajo de teclado: Ctrl + K o /
document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey && e.key === 'k') || e.key === '/') {
        e.preventDefault();
        buscador.focus();
    }
});
</script>

<script>
function cargarNotificaciones() {
    fetch('api/api_notificaciones.php')
        .then(res => res.json())
        .then(data => {
            const lista = document.getElementById('lista-notificaciones');
            const contador = document.getElementById('contador-notificaciones');
            lista.innerHTML = '';

            // Mostrar cantidad de no leídas
            if (data.no_leidas > 0) {
                contador.style.display = 'inline';
                contador.textContent = data.no_leidas;
            } else {
                contador.style.display = 'none';
            }

            // ✅ Si no hay notificaciones, mostrar mensaje con ícono
            if (data.notificaciones.length === 0) {
                const li = document.createElement('li');
                li.innerHTML = `
                    <div style="text-align:center; color:#777; padding:5px;">
                        <i class='bx bx-bell-off' style="font-size:18px; margin-bottom:4px; display:block;"></i>
                        No tienes notificaciones pendientes.
                    </div>
                `;
                lista.appendChild(li);
                return;
            }

            // Mostrar lista de notificaciones
            data.notificaciones.forEach(n => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <div class="notificacion-item ${n.leido == 0 ? 'no-leida' : ''}">
                        <p>${n.mensaje}</p>
                        <small>${n.modulo} • ${new Date(n.fecha).toLocaleString()}</small>
                    </div>
                `;
                lista.appendChild(li);
            });
        })
        .catch(err => console.error('Error al cargar notificaciones:', err));

    // Limpiar notificaciones con más de 1 día
    fetch('api/api_eliminar_notificaciones_antiguas.php', { method: 'POST' })
        .catch(err => console.error('Error limpiando notificaciones antiguas:', err));
}

// Cargar automáticamente cada 15 segundos
setInterval(cargarNotificaciones, 15000);
cargarNotificaciones();

// Mostrar / ocultar lista al hacer clic
document.getElementById('campana-icono').addEventListener('click', () => {
    const lista = document.getElementById('lista-notificaciones');
    const visible = lista.style.display === 'block';
    lista.style.display = visible ? 'none' : 'block';

    if (!visible) {
        // ✅ Marcar como leídas (sin eliminar)
        fetch('api/api_marcar_leidas.php', { method: 'POST' })
            .catch(err => console.error('Error al marcar como leídas:', err));
    }
});

// Cerrar lista si se hace clic fuera
document.addEventListener('click', (e) => {
    if (!e.target.closest('.campana')) {
        document.getElementById('lista-notificaciones').style.display = 'none';
    }
});
</script>




    <!-- Barra lateral -->
    <aside class="lateral">
        <div class="logo">
            <img src="<?= $base ?>imagenes/Logo inventra.png" alt="Logo">
        </div>
        
        <nav class="menu">
            <a href="<?= $base ?>inicio.php" class="menu-item">
                <i class='bx bxs-home'></i>
                <span>Inicio</span>
            </a>

            <a href="<?= $base ?>panelcontrol.php" class="menu-item">
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
                    <a href="<?= $base ?>registro.php">Registrar Equipo</a>
                    <a href="<?= $base ?>asignar.php">Asignar Equipo</a>
                    <a href="<?= $base ?>consultar_inventario.php">Consultar Inventario</a>
                </div>
            </details>

            <details class="menu-group">
                <summary>
                    <i class='bx bxs-wrench'></i>
                    <span>Mantenimiento</span>
                    <i class='bx bx-chevron-right arrow'></i>
                </summary>
                <div class="submenu">
                    <a href="<?= $base ?>mantenimiento.php">Registrar Mantenimiento</a>
                    <a href="<?= $base ?>repuestos.php">Agregar Repuestos</a>
                    <a href="<?= $base ?>registrar_tecnico.php">Registrar Técnicos</a>
                    <a href="<?= $base ?>listadoMantenimiento.php">Historial Mantenimientos</a>
                </div>
            </details>

            <a href="<?= $base ?>informes.php" class="menu-item">
                <i class='bx bxs-food-menu'></i>
                <span>Informes</span>
            </a>

            <!-- Botón de salir -->
            <a href="#" class="menu-item" onclick="abrirModalSalir()">
                <i class='bx bxs-exit'></i>
                <span>Salir</span>
            </a>
        </nav>
    </aside>

    <!-- Modal de confirmación -->
    <div id="modalSalir" class="inventra-modal">
        <div class="inventra-contenido">
            <div class="modal-logo">
                <img src="<?= $base ?>imagenes/Logo inventra.png" alt="Logo Inventra">
            </div>
            <h2>¿Cerrar sesión?</h2>
            <p>¿Estás seguro que deseas salir de Inventra?</p>
            <div class="modal-botones">
                <button onclick="cerrarModalSalir()" class="inventra-btn-cancelar">Cancelar</button>
                <a href="<?= $base ?>logout.php" class="inventra-btn-confirmar">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <!-- Script para controlar el modal -->
    <script>
        function abrirModalSalir() {
            document.getElementById('modalSalir').style.display = 'flex';
        }

        function cerrarModalSalir() {
            document.getElementById('modalSalir').style.display = 'none';
        }
    </script>
</body>
</html>
