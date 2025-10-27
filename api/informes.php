<?php
// api/informes.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/conexion.php';

// ✅ Agregamos soporte para PhpSpreadsheet
require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    if (!isset($pdo) && isset($conn) && $conn instanceof PDO) {
        $pdo = $conn;
    }
    if (!isset($pdo)) {
        throw new Exception("No se encontró la conexión PDO (\$pdo). Ajusta includes/conexion.php");
    }

    // === PARÁMETROS ===
    $report = $_GET['report'] ?? '';
    $formato = $_GET['formato'] ?? ''; // <- nuevo parámetro (excel o pdf)
    $date_from = $_GET['date_from'] ?? null;
    $date_to   = $_GET['date_to'] ?? null;
    $area      = $_GET['area'] ?? null;
    $sede      = $_GET['sede'] ?? null;
    $filter    = $_GET['filter'] ?? null;
    $search    = $_GET['search'] ?? null;

    $rows = [];

    $validateDate = function($d) {
        if (!$d) return false;
        $t = date_parse($d);
        return checkdate($t['month'] ?? 0, $t['day'] ?? 0, $t['year'] ?? 0);
    };

    switch ($report) {
        case 'asignados':
            $sql = "SELECT a.id_asignacion, a.placa_inventario, a.nombres, a.apellidos,
                        a.identificacion, a.correo_electronico, a.cargo, a.tipo_contrato,
                        a.area, a.sede, a.extension_telefono, a.accesorios_adicionales,
                        a.fecha_asignacion, a.fecha_devolucion, a.observaciones,
                        e.marca, e.modelo, e.serial, e.tipo_equipo, e.estado AS estado_equipo
                    FROM asignacion_equipo a
                    LEFT JOIN registro_equipos e ON a.id_equipo = e.id
                    WHERE 1=1";
            $params = [];

            if (!empty($filter) && $filter !== 'todas') {
                $sql .= " AND a.area LIKE :filter";
                $params[':filter'] = "%{$filter}%";
            }

            if ($search) {
                $sql .= " AND (
                    a.nombres LIKE :search OR 
                    a.apellidos LIKE :search OR 
                    a.placa_inventario LIKE :search OR 
                    e.serial LIKE :search
                )";
                $params[':search'] = "%{$search}%";
            }

            $sql .= " ORDER BY a.fecha_asignacion DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $titulo = "Equipos Asignados";
            break;

        case 'disponibles':
            $sql = "SELECT id, marca, modelo, serial, placa_inventario, ubicacion_fisica, tipo_equipo, estado, fecha_registro
                    FROM registro_equipos
                    WHERE (estado = 'Disponible' OR estado LIKE '%Disponible%')";
            $params = [];
            if ($filter && $filter !== 'todos') {
                if ($filter === 'portatiles') {
                    $sql .= " AND tipo_equipo IN ('Laptop','WorkStation','Otro','Laptop')";
                } elseif ($filter === 'escritorio') {
                    $sql .= " AND tipo_equipo = 'Desktop'";
                } else {
                    $sql .= " AND tipo_equipo LIKE :filter";
                    $params[':filter'] = "%{$filter}%";
                }
            }
            if ($search) {
                $sql .= " AND (placa_inventario LIKE :search OR serial LIKE :search OR marca LIKE :search OR modelo LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
            $sql .= " ORDER BY fecha_registro DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $titulo = "Equipos Disponibles";
            break;

        case 'mantenimiento':
            $sql = "SELECT m.id_mantenimiento, m.id_equipo, m.tecnico_nombre, m.fecha_mantenimiento, m.tipo, m.estado, m.descripcion,
                        e.placa_inventario, e.marca, e.modelo, e.serial
                    FROM mantenimiento m
                    LEFT JOIN registro_equipos e ON m.id_equipo = e.id
                    WHERE 1=1";
            $params = [];
            if ($filter && in_array($filter, ['Pendiente','En Proceso','Finalizado','pendiente','en-proceso','finalizado'])) {
                $sql .= " AND m.estado = :estado";
                $params[':estado'] = $filter;
            }
            if ($date_from && $validateDate($date_from)) {
                $sql .= " AND m.fecha_mantenimiento >= :date_from";
                $params[':date_from'] = $date_from;
            }
            if ($date_to && $validateDate($date_to)) {
                $sql .= " AND m.fecha_mantenimiento <= :date_to";
                $params[':date_to'] = $date_to;
            }
            if ($search) {
                $sql .= " AND (e.placa_inventario LIKE :search OR e.serial LIKE :search OR m.tecnico_nombre LIKE :search OR m.descripcion LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
            $sql .= " ORDER BY m.fecha_mantenimiento DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $titulo = "Equipos en Mantenimiento";
            break;

        case 'asignaciones_fecha':
            $sql = "SELECT a.id_asignacion, a.nombres, a.apellidos, a.area, a.sede, a.cargo, a.fecha_asignacion, a.fecha_devolucion,
                        e.marca, e.modelo, e.placa_inventario, e.serial, e.estado as estado_equipo
                    FROM asignacion_equipo a
                    LEFT JOIN registro_equipos e ON a.id_equipo = e.id
                    WHERE 1=1";
            $params = [];
            if ($date_from && $validateDate($date_from)) {
                $sql .= " AND a.fecha_asignacion >= :date_from";
                $params[':date_from'] = $date_from;
            }
            if ($date_to && $validateDate($date_to)) {
                $sql .= " AND a.fecha_asignacion <= :date_to";
                $params[':date_to'] = $date_to;
            }
            $sql .= " ORDER BY a.fecha_asignacion DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $titulo = "Asignaciones por Fecha";
            break;

        case 'historial':
            $sql = "SELECT 
                        m.id_mantenimiento,
                        m.id_equipo,
                        m.fecha_mantenimiento,
                        m.estado,
                        m.descripcion,
                        m.sede,
                        m.tecnico,
                        e.marca,
                        e.modelo,
                        e.serial,
                        e.tipo_equipo
                    FROM mantenimiento m
                    LEFT JOIN registro_equipos e ON m.id_equipo = e.id
                    WHERE 1=1";
            $params = [];

            if (!empty($filter) && $filter === 'por-tecnico' && !empty($search)) {
                $sql .= " AND m.tecnico LIKE :search";
                $params[':search'] = "%{$search}%";
            } elseif (!empty($filter) && $filter === 'por-sede' && !empty($search)) {
                $sql .= " AND m.sede LIKE :search";
                $params[':search'] = "%{$search}%";
            } elseif (!empty($filter) && $filter === 'por-estado' && !empty($search)) {
                $sql .= " AND m.estado LIKE :search";
                $params[':search'] = "%{$search}%";
            }

            $sql .= " ORDER BY m.fecha_mantenimiento DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $titulo = "Historial de Mantenimientos";
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Parámetro report inválido']);
            exit;
    }

    // === EXPORTAR A EXCEL SI SE SOLICITA ===
    if ($formato === 'excel') {
        if (empty($rows)) die("No hay datos para exportar.");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($titulo);

        $headers = array_keys($rows[0]);
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', strtoupper(str_replace('_', ' ', $header)));
            $col++;
        }

        $rowNum = 2;
        foreach ($rows as $row) {
            $col = 'A';
            foreach ($row as $value) {
                $sheet->setCellValue($col . $rowNum, $value);
                $col++;
            }
            $rowNum++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$titulo}.xlsx\"");
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // === RESPUESTA NORMAL JSON ===
    echo json_encode(['status' => 'ok', 'data' => $rows], JSON_UNESCAPED_UNICODE);

} catch (PDOException $ex) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error DB: ' . $ex->getMessage()]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
