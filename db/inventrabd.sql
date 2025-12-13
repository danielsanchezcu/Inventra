-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-11-2025 a las 17:47:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventrabd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_admin`, `id_usuario`) VALUES
(3, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_equipo`
--

CREATE TABLE `asignacion_equipo` (
  `id_asignacion` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `tipo_contrato` enum('Planta','Prestación de servicios','Pasante','Temporal') NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `sede` varchar(100) DEFAULT NULL,
  `extension_telefono` varchar(50) DEFAULT NULL,
  `accesorios_adicionales` text DEFAULT NULL,
  `fecha_asignacion` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `placa_inventario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion_equipo`
--

INSERT INTO `asignacion_equipo` (`id_asignacion`, `id_equipo`, `nombres`, `apellidos`, `identificacion`, `correo_electronico`, `cargo`, `tipo_contrato`, `area`, `sede`, `extension_telefono`, `accesorios_adicionales`, `fecha_asignacion`, `fecha_devolucion`, `observaciones`, `created_at`, `placa_inventario`) VALUES
(68, 29, 'Daniel Felipe', 'Sanchez Currea', '1000250958', 'daniel.sanchez@inventra.com.co', 'Analista', 'Planta', 'Recursos Humanos', 'Bogota', '3202407896', 'Cargador, Maletin y Guaya', '2025-10-28', '0000-00-00', '', '2025-10-29 02:59:14', 'INV-001'),
(69, 30, 'Gustavo', 'Sanchez Currea', '1000250958', 'daniel.sanchez@inventra.com.co', 'Analista', 'Planta', 'Recursos Humanos', 'Bogota', '3202407896', 'Cargador, Maletin y Guaya', '2025-10-28', '0000-00-00', '', '2025-10-29 03:01:17', 'INV-002');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_equipo`
--

CREATE TABLE `categoria_equipo` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria_equipo`
--

INSERT INTO `categoria_equipo` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Desktop', NULL),
(2, 'Laptop', NULL),
(3, 'Servidor', NULL),
(4, 'Workstation', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_mantenimientos`
--

CREATE TABLE `historial_mantenimientos` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `tipo` enum('Preventivo','Correctivo') NOT NULL,
  `fecha` date NOT NULL,
  `tecnico` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `repuestos_usados` varchar(255) DEFAULT NULL,
  `estado` enum('Finalizado','En Proceso','Pendiente') DEFAULT 'Finalizado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_mantenimientos`
--

INSERT INTO `historial_mantenimientos` (`id_mantenimiento`, `id_equipo`, `tipo`, `fecha`, `tecnico`, `descripcion`, `repuestos_usados`, `estado`) VALUES
(33, 29, 'Preventivo', '2025-11-02', 'Daniel Felipe Sanchez Currea', 'se finaliza', NULL, 'Finalizado'),
(34, 29, 'Preventivo', '2025-11-02', 'Daniel Felipe Sanchez Currea', 'se deja en pendiente por disponibilidad', NULL, 'Pendiente'),
(35, 29, 'Preventivo', '2025-11-02', 'Daniel Felipe Sanchez Currea', 'Mantenimiento finalizado', NULL, 'Finalizado'),
(36, 29, 'Preventivo', '2025-11-02', 'Daniel Felipe Sanchez Currea', 'En proceso', NULL, 'En Proceso'),
(37, 29, 'Preventivo', '2025-11-02', 'Daniel Felipe Sanchez Currea', 'finalizado', NULL, 'Finalizado'),
(38, 29, 'Preventivo', '2025-11-02', 'Daniel Felipe Sanchez Currea', 'jj', NULL, 'Pendiente'),
(39, 29, 'Preventivo', '2025-11-02', 'Daniel Felipe Sanchez Currea', 'fin', NULL, 'Finalizado'),
(40, 30, 'Preventivo', '2025-11-02', 'Daniel Felipe Sanchez Currea', 'ok', NULL, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

CREATE TABLE `mantenimiento` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `tecnico_nombre` varchar(100) NOT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `fecha_mantenimiento` date NOT NULL,
  `tipo` enum('preventivo','correctivo') NOT NULL,
  `estado` enum('Pendiente','En Proceso','Finalizado') DEFAULT 'Pendiente',
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mantenimiento`
--

INSERT INTO `mantenimiento` (`id_mantenimiento`, `id_equipo`, `tecnico_nombre`, `tecnico_id`, `fecha_mantenimiento`, `tipo`, `estado`, `descripcion`) VALUES
(32, 29, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'En Proceso', 'hola'),
(33, 29, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'Finalizado', 'se finaliza'),
(34, 29, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'Pendiente', 'se deja en pendiente por disponibilidad'),
(35, 29, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'Finalizado', 'Mantenimiento finalizado'),
(36, 29, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'En Proceso', 'En proceso'),
(37, 29, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'Finalizado', 'finalizado'),
(38, 29, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'Pendiente', 'jj'),
(39, 29, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'Finalizado', 'fin'),
(40, 30, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'Pendiente', 'ok'),
(41, 30, 'Daniel Felipe Sanchez Currea', 16, '2025-11-02', 'preventivo', 'Finalizado', 'asaas');

--
-- Disparadores `mantenimiento`
--
DELIMITER $$
CREATE TRIGGER `after_insert_mantenimiento` AFTER INSERT ON `mantenimiento` FOR EACH ROW BEGIN
    INSERT INTO historial_mantenimientos (id_mantenimiento, id_equipo, tipo, fecha, tecnico, descripcion, estado)
    VALUES (
        NEW.id_mantenimiento,
        NEW.id_equipo,
        NEW.tipo,
        NEW.fecha_mantenimiento,
        NEW.tecnico_nombre,
        NEW.descripcion,
        NEW.estado
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `mantenimiento_before_insert` BEFORE INSERT ON `mantenimiento` FOR EACH ROW BEGIN
    IF NEW.tecnico_id IS NOT NULL THEN
        SET NEW.tecnico_nombre = (SELECT CONCAT(nombres, ' ', apellidos) FROM tecnicos WHERE id_tecnico = NEW.tecnico_id LIMIT 1);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_repuesto`
--

CREATE TABLE `mantenimiento_repuesto` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_repuesto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  `modulo` varchar(100) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `mensaje`, `modulo`, `fecha`, `leido`) VALUES
(21, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 07:45:10', 1),
(22, '🔧 El repuesto <b>Memoria Ram 16</b> fue actualizado correctamente.', 'Actualización de Repuesto', '2025-11-03 07:56:09', 1),
(23, 'Se ha registrado un nuevo repuesto: <b>Memoria Ram 8</b>.', 'Nuevo Repuesto', '2025-11-03 07:56:44', 1),
(24, 'El repuesto <b>Memoria Ram 8</b> fue actualizado correctamente.', 'Actualización de Repuesto', '2025-11-03 07:57:03', 1),
(25, 'El repuesto <b>Repuesto desconocido</b> fue eliminado del inventario.', 'Eliminación de Repuesto', '2025-11-03 08:03:17', 1),
(26, 'Se ha registrado un nuevo repuesto: <b>Memoria Ram</b>.', 'Nuevo Repuesto', '2025-11-03 08:06:31', 1),
(27, 'El repuesto <b>Memoria Ram</b> fue eliminado del inventario.', 'Eliminación de Repuesto', '2025-11-03 08:06:49', 1),
(28, 'El técnico <b>Daniel Felipe Sanchez Currea</b> ha sido actualizado.', 'Actualización de Técnico', '2025-11-03 08:11:01', 1),
(29, 'Se ha registrado un nuevo técnico: <b>Gustavo Vega Currea</b>.', 'Registro de Técnico', '2025-11-03 08:12:01', 1),
(30, 'El técnico <b>Gustavo Vega Currea</b> fue eliminado del sistema.', 'Eliminación de Técnico', '2025-11-03 08:12:22', 1),
(31, 'Se eliminó el historial de mantenimiento <b>Preventivo</b> \r\n                                 del equipo con placa <b>INV-001</b> realizado por <b>Daniel Felipe Sanchez Currea</b>.', 'Eliminación de Historial', '2025-11-03 08:30:43', 1),
(32, 'Se eliminó el historial de mantenimiento <b>Preventivo</b> \r\n                                 del equipo con placa <b>INV-001</b> realizado por <b>Daniel Felipe Sanchez Currea</b>.', 'Eliminación de Historial', '2025-11-03 08:31:02', 1),
(33, 'Se eliminó el historial de mantenimiento <b>Preventivo</b> \r\n                                 del equipo con placa <b>INV-001</b> realizado por <b>Daniel Felipe Sanchez Currea</b>.', 'Eliminación de Historial', '2025-11-03 08:31:08', 1),
(34, 'Se eliminó el historial de mantenimiento <b>Preventivo</b> \r\n                                 del equipo con placa <b>INV-001</b> realizado por <b>Daniel Felipe Sanchez Currea</b>.', 'Eliminación de Historial', '2025-11-03 08:31:12', 1),
(35, 'Se eliminó el historial de mantenimiento <b>Preventivo</b> \r\n                                 del equipo con placa <b>INV-001</b> realizado por <b>Daniel Felipe Sanchez Currea</b>.', 'Eliminación de Historial', '2025-11-03 08:31:15', 1),
(36, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:09:43', 1),
(37, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:10:41', 1),
(38, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:18:31', 1),
(39, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:19:25', 1),
(40, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:20:23', 1),
(41, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:21:13', 1),
(42, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:21:47', 1),
(43, 'Se registró un mantenimiento (preventivo) para el equipo (INV-001) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:22:17', 1),
(44, 'Se registró un mantenimiento (preventivo) para el equipo (INV-002) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:31:12', 1),
(45, 'Se registró un mantenimiento (preventivo) para el equipo (INV-002) - LENOVO THINKPAD', 'Mantenimiento de Equipos', '2025-11-03 09:32:09', 1),
(46, 'Se eliminó el historial de mantenimiento <b>Preventivo</b> \r\n                                 del equipo con placa <b>INV-002</b> realizado por <b>Daniel Felipe Sanchez Currea</b>.', 'Eliminación de Historial', '2025-11-03 09:52:36', 1),
(47, 'Se eliminó el historial de mantenimiento <b>Preventivo</b> \r\n                                 del equipo con placa <b>INV-001</b> realizado por <b>Daniel Felipe Sanchez Currea</b>.', 'Eliminación de Historial', '2025-11-03 09:52:41', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_equipos`
--

CREATE TABLE `registro_equipos` (
  `id` int(11) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `serial` varchar(20) NOT NULL,
  `placa_inventario` varchar(50) NOT NULL,
  `ubicacion_fisica` varchar(100) NOT NULL,
  `proveedor` varchar(100) NOT NULL,
  `costo` decimal(10,2) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `tipo_equipo` enum('Desktop','Laptop','WorkStation','Otro') NOT NULL,
  `teclado` varchar(50) DEFAULT NULL,
  `mouse` varchar(50) DEFAULT NULL,
  `estado` enum('Disponible','En mantenimiento','Dado de baja','Dado de baja','Asignado') DEFAULT NULL,
  `fecha_garantia` date DEFAULT NULL,
  `procesador` varchar(100) NOT NULL,
  `sistema_operativo` varchar(100) NOT NULL,
  `ram` varchar(20) NOT NULL,
  `disco_duro` varchar(20) NOT NULL,
  `fecha_registro` date NOT NULL,
  `imagen_equipo` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_equipos`
--

INSERT INTO `registro_equipos` (`id`, `marca`, `modelo`, `serial`, `placa_inventario`, `ubicacion_fisica`, `proveedor`, `costo`, `numero_factura`, `tipo_equipo`, `teclado`, `mouse`, `estado`, `fecha_garantia`, `procesador`, `sistema_operativo`, `ram`, `disco_duro`, `fecha_registro`, `imagen_equipo`, `observaciones`) VALUES
(29, 'LENOVO', 'THINKPAD', 'PF5J01', 'INV-001', 'Sala 1 Bodega', 'LENOVO', 280000.00, '0001', 'Desktop', 'serial1', 'serial1', 'En mantenimiento', '2025-10-28', 'INTEL', 'WINDOWS 11', '32', '256', '2025-10-28', '6901724215c44_EJEMPLO - IMAGEN.webp', ''),
(30, 'LENOVO', 'THINKPAD', 'PF5J02', 'INV-002', 'Sala 1 Bodega', 'LENOVO', 280000.00, '0001', 'Desktop', 'serial2', 'serial2', 'Disponible', '2025-10-28', 'INTEL', 'WINDOWS 11', '16', '256', '2025-10-28', '6901751d6bf16_EJEMPLO - IMAGEN.webp', ''),
(31, 'LENOVO', 'THINKPAD', 'PF5J03', 'INV-003', 'Sala 1 Bodega', 'LENOVO', 280000.00, '0001', 'Laptop', '', '', 'Disponible', '2025-10-28', 'INTEL', 'WINDOWS 11', '16', '128', '2025-10-28', '6901872abfe9a_EJEMPLO - IMAGEN.webp', ''),
(32, 'LENOVO', 'THINKPAD', 'PF5J04', 'INV-004', 'Sala 1 Bodega', 'LENOVO', 280000.00, '0001', 'Laptop', '', '', 'Disponible', '2025-10-28', 'INTEL', 'WINDOWS 11', '8', '128', '2025-10-28', '69018a9653499_EJEMPLO - IMAGEN.webp', ''),
(33, 'LENOVO', 'THINKPAD', 'PF5J05', 'INV-005', 'Sala 1 Bodega', 'LENOVO', 280000.00, '0001', 'WorkStation', '', '', 'Disponible', '2025-10-28', 'INTEL', 'WINDOWS 11', '64', '1 tb', '2025-10-28', '69018c8053f27_EJEMPLO - IMAGEN.webp', ''),
(34, 'LENOVO', 'THINKPAD', 'PF5J07', 'INV-008', 'Sala 1 Bodega', 'LENOVO', 280000.00, '0001', 'Laptop', '', '', 'Disponible', '2025-10-28', '', 'WINDOWS 11', '8', '128', '2025-10-28', '69018fd799ad3_EJEMPLO - IMAGEN.webp', ''),
(35, 'LENOVO', 'THINKPAD', 'PF5J08', 'INV-009', 'Sala 1 Bodega', 'LENOVO', 280000.00, '0001', 'Laptop', '', '', 'Disponible', '2025-10-28', 'INTEL', 'WINDOWS 11', '8', '256', '2025-10-28', '6901910ad3fb7_EJEMPLO - IMAGEN.webp', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE `reporte` (
  `id_reporte` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `fecha_generacion` date NOT NULL,
  `contenido` text DEFAULT NULL,
  `estado` enum('pendiente','finalizado') DEFAULT 'pendiente',
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repuesto`
--

CREATE TABLE `repuesto` (
  `id_repuesto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT 0.00,
  `cantidad` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id_tecnico` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Suspendido') NOT NULL DEFAULT 'Activo',
  `observaciones` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id_tecnico`, `nombres`, `apellidos`, `identificacion`, `telefono`, `correo`, `especialidad`, `estado`, `observaciones`, `fecha_registro`) VALUES
(16, 'Daniel Felipe', 'Sanchez Currea', '1000250958', '3117382042', 'daniel.sanchez@inventra.com.co', 'N/A', 'Activo', '', '2025-11-03 00:23:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(100) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `permisos` enum('cliente','tecnico','administrador') NOT NULL,
  `area` varchar(15) DEFAULT NULL,
  `cargo` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `correo`, `contrasena`, `identificacion`, `permisos`, `area`, `cargo`) VALUES
(19, 'Daniel Sanchez', 'daniel.sanchez@inventra.com.co', '$2y$10$8UGgQTOoZdFSJmjup78OYe/0F7pTlWaGqQBkCaxec/KD1COTX2OMe', '1000250958', 'administrador', 'direccion de te', 'funcionario'),
(24, 'Juan Perez', 'juan.perez@inventra.com.co', '$2y$10$8UGgQTOoZdFSJmjup78OYe/0F7pTlWaGqQBkCaxec/KD1COTX2OMe', '1022400769', 'tecnico', 'DTI', 'Tecnico');

--
-- Disparadores `usuario`
--
DELIMITER $$
CREATE TRIGGER `after_insert_usuario_admin` AFTER INSERT ON `usuario` FOR EACH ROW BEGIN
  IF NEW.permisos = 'administrador' THEN
    INSERT INTO administrador (id_usuario)
    VALUES (NEW.id_usuario);
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `traslado_cliente` AFTER INSERT ON `usuario` FOR EACH ROW BEGIN
  IF NEW.permisos = 'cliente' THEN
    INSERT INTO cliente (id_usuario)
    VALUES (NEW.id_usuario);
  END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `asignacion_equipo`
--
ALTER TABLE `asignacion_equipo`
  ADD PRIMARY KEY (`id_asignacion`),
  ADD KEY `id_equipo` (`id_equipo`),
  ADD KEY `placa_inventario` (`placa_inventario`);

--
-- Indices de la tabla `categoria_equipo`
--
ALTER TABLE `categoria_equipo`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `historial_mantenimientos`
--
ALTER TABLE `historial_mantenimientos`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `fk_mantenimiento_registro` (`id_equipo`);

--
-- Indices de la tabla `mantenimiento_repuesto`
--
ALTER TABLE `mantenimiento_repuesto`
  ADD PRIMARY KEY (`id_mantenimiento`,`id_repuesto`),
  ADD KEY `mantenimiento_repuesto_ibfk_2` (`id_repuesto`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_equipos`
--
ALTER TABLE `registro_equipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial` (`serial`),
  ADD UNIQUE KEY `placa_inventario` (`placa_inventario`),
  ADD UNIQUE KEY `placa_inventario_2` (`placa_inventario`),
  ADD UNIQUE KEY `placa_inventario_3` (`placa_inventario`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `repuesto`
--
ALTER TABLE `repuesto`
  ADD PRIMARY KEY (`id_repuesto`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id_tecnico`),
  ADD UNIQUE KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `identificacion` (`identificacion`),
  ADD UNIQUE KEY `identificacion_2` (`identificacion`),
  ADD UNIQUE KEY `correo_2` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `asignacion_equipo`
--
ALTER TABLE `asignacion_equipo`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `categoria_equipo`
--
ALTER TABLE `categoria_equipo`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `historial_mantenimientos`
--
ALTER TABLE `historial_mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `registro_equipos`
--
ALTER TABLE `registro_equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `repuesto`
--
ALTER TABLE `repuesto`
  MODIFY `id_repuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id_tecnico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asignacion_equipo`
--
ALTER TABLE `asignacion_equipo`
  ADD CONSTRAINT `asignacion_equipo_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `registro_equipos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_placa` FOREIGN KEY (`placa_inventario`) REFERENCES `registro_equipos` (`placa_inventario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_mantenimientos`
--
ALTER TABLE `historial_mantenimientos`
  ADD CONSTRAINT `historial_mantenimientos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `registro_equipos` (`id`);

--
-- Filtros para la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD CONSTRAINT `fk_mantenimiento_registro` FOREIGN KEY (`id_equipo`) REFERENCES `registro_equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mantenimiento_repuesto`
--
ALTER TABLE `mantenimiento_repuesto`
  ADD CONSTRAINT `mantenimiento_repuesto_ibfk_1` FOREIGN KEY (`id_mantenimiento`) REFERENCES `mantenimiento` (`id_mantenimiento`) ON DELETE CASCADE,
  ADD CONSTRAINT `mantenimiento_repuesto_ibfk_2` FOREIGN KEY (`id_repuesto`) REFERENCES `repuesto` (`id_repuesto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD CONSTRAINT `reporte_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
