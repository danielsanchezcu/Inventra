<?php
ob_start();

date_default_timezone_set('America/Bogota');

require_once __DIR__ . '/../includes/conexion.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

$report = $_GET['report'] ?? '';
$format = $_GET['format'] ?? 'excel';
$filter = $_GET['filter'] ?? '';
$search = $_GET['search'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

try {
    if (!isset($pdo)) {
        throw new Exception("No se encontró la conexión PDO (\$pdo).");
    }

    $rows = [];
    $params = [];

    switch ($report) {
        case 'asignados':
            $sql = "SELECT a.id_asignacion, a.nombres, a.apellidos, a.area, a.sede, a.cargo,
                           e.marca, e.modelo, e.placa_inventario, e.serial, e.estado AS estado_equipo,
                           a.fecha_asignacion, a.fecha_devolucion, a.observaciones
                    FROM asignacion_equipo a
                    LEFT JOIN registro_equipos e ON a.id_equipo = e.id
                    WHERE 1=1";
            if ($filter && $filter !== 'todas') {
                $sql .= " AND a.area LIKE :filter";
                $params[':filter'] = "%{$filter}%";
            }
            if ($search) {
                $sql .= " AND (a.nombres LIKE :search OR a.apellidos LIKE :search OR e.serial LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
            $sql .= " ORDER BY a.fecha_asignacion DESC";
            break;

        case 'disponibles':
            $sql = "SELECT id, marca, modelo, serial, placa_inventario, tipo_equipo, estado, fecha_registro
                    FROM registro_equipos
                    WHERE estado LIKE '%Disponible%'";
            if ($filter && $filter !== 'todos') {
                if ($filter === 'portatiles') $sql .= " AND tipo_equipo LIKE '%Laptop%'";
                elseif ($filter === 'escritorio') $sql .= " AND tipo_equipo LIKE '%Desktop%'";
            }
            if ($search) {
                $sql .= " AND (marca LIKE :search OR modelo LIKE :search OR serial LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
            $sql .= " ORDER BY fecha_registro DESC";
            break;

        case 'mantenimiento':
            $sql = "SELECT m.id_mantenimiento, m.fecha_mantenimiento, m.estado, m.descripcion,
                           m.tecnico_nombre, e.marca, e.modelo, e.serial, e.tipo_equipo
                    FROM mantenimiento m
                    LEFT JOIN registro_equipos e ON m.id_equipo = e.id
                    WHERE 1=1";
            if ($filter) {
                $sql .= " AND m.estado LIKE :filter";
                $params[':filter'] = "%{$filter}%";
            }
            if ($search) {
                $sql .= " AND (m.tecnico_nombre LIKE :search OR e.serial LIKE :search OR e.marca LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
            $sql .= " ORDER BY m.fecha_mantenimiento DESC";
            break;

        case 'asignaciones_fecha':
            $sql = "SELECT a.id_asignacion, a.nombres, a.apellidos, a.area, a.sede, a.fecha_asignacion,
                           e.marca, e.modelo, e.serial, e.placa_inventario
                    FROM asignacion_equipo a
                    LEFT JOIN registro_equipos e ON a.id_equipo = e.id
                    WHERE 1=1";
            if ($date_from) {
                $sql .= " AND a.fecha_asignacion >= :date_from";
                $params[':date_from'] = $date_from;
            }
            if ($date_to) {
                $sql .= " AND a.fecha_asignacion <= :date_to";
                $params[':date_to'] = $date_to;
            }
            $sql .= " ORDER BY a.fecha_asignacion DESC";
            break;

        case 'historial':
            $sql = "
                SELECT
                    m.id_mantenimiento AS 'ID MANTENIMIENTO',
                    m.fecha_mantenimiento AS 'FECHA MANTENIMIENTO',
                    COALESCE(CONCAT(t.nombres, ' ', t.apellidos), m.tecnico_nombre) AS 'TECNICO',
                    m.estado AS 'ESTADO',
                    m.descripcion AS 'DESCRIPCION',
                    e.marca AS 'MARCA',
                    e.modelo AS 'MODELO',
                    e.serial AS 'SERIAL',
                    e.tipo_equipo AS 'TIPO EQUIPO'
                FROM mantenimiento m
                LEFT JOIN registro_equipos e ON m.id_equipo = e.id
                LEFT JOIN tecnicos t ON m.tecnico_id = t.id_tecnico
                WHERE 1=1
            ";
            if ($filter === 'por-tecnico' && $search) {
                $sql .= " AND (t.nombres LIKE :search OR t.apellidos LIKE :search OR m.tecnico_nombre LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
            $sql .= " ORDER BY m.fecha_mantenimiento DESC";
            break;

        default:
            throw new Exception("Parámetro report inválido o no especificado.");
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // === Si no hay resultados: PDF informativo ===
    if (empty($rows)) {
        $mpdf = new \Mpdf\Mpdf(['default_font' => 'Montserrat']);

        // ✅ Rutas absolutas dentro del contenedor Docker
        $basePath = '/var/www/html/imagenes/';
        $mpdf->showImageErrors = true;

        // ✅ Marca de agua (logo translúcido)
        $mpdf->SetWatermarkImage($basePath . 'Logo inventra.png', 0.15, [120, 120]);
        $mpdf->showWatermarkImage = true;

        // Encabezado del PDF
        $html = '
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <div style="flex: 1;">
                <img src="' . $basePath . 'logo2.png" width="80">
            </div>
            <div style="flex: 3; text-align: center;">
                <h2 style="margin: 0; font-weight: bold;">Reporte Inventra</h2>
                <p style="margin: 3px 0;">Tu sistema para la gestión inteligente de equipos y asignaciones</p>
                <p style="margin: 0; font-size: 12px;">Desarrollado por <b>Daniel Felipe Sánchez</b></p>
            </div>
        </div>
        <hr style="margin: 10px 0;">

        <div style="text-align: center; margin-top: 60px;">
            <p style="font-size: 20px; font-weight: bold; color: #333;">
                ⚠ No se encontraron datos para este informe.
            </p>
            <p style="font-size: 14px; color: #555;">
                Verifica los filtros o intenta con un rango de fechas diferente.
            </p>
        </div>

        <hr style="margin-top: 80px;">
        <p style="text-align: right; font-size: 10px; color: gray;">
            Fecha de generación: ' . date("d/m/Y H:i:s") . '
        </p>';

        $nombreReporte = match ($report) {
            'asignados' => 'Equipos-Asignados',
            'disponibles' => 'Equipos-Disponibles',
            'mantenimiento' => 'Equipos-Mantenimiento',
            'asignaciones_fecha' => 'Asignaciones-por-Fecha',
            'historial' => 'Historial-Mantenimientos',
            default => 'Reporte-Inventra',
        };

        $mpdf->WriteHTML($html);
        $mpdf->Output("{$nombreReporte}.pdf", 'D');
        exit;
    }

    // === Crear Excel ===
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte Inventra');
    $headers = array_keys($rows[0]);

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', strtoupper(str_replace('_', ' ', $header)));
        $col++;
    }

    $rowNum = 2;
    foreach ($rows as $row) {
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $rowNum, $row[$header]);
            $col++;
        }
        $rowNum++;
    }

    $nombreReporte = match ($report) {
        'asignados' => 'Equipos-Asignados',
        'disponibles' => 'Equipos-Disponibles',
        'mantenimiento' => 'Equipos-Mantenimiento',
        'asignaciones_fecha' => 'Asignaciones-por-Fecha',
        'historial' => 'Historial-Mantenimientos',
        default => 'Reporte-Inventra',
    };

    if ($format === 'excel') {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$nombreReporte}.xlsx\"");
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // === Generar PDF ===
    if ($format === 'pdf') {
        $mpdf = new Mpdf(['format' => 'A4-L', 'margin_top' => 60]);

        // ✅ Rutas absolutas dentro de Docker
        $basePath = '/var/www/html/imagenes/';
        $mpdf->showImageErrors = true;

        // ✅ Marca de agua
        $mpdf->SetWatermarkImage($basePath . 'Logo inventra.png', 0.15, [120, 120]);
        $mpdf->showWatermarkImage = true;

        // ✅ Logo principal
        $logoPath = $basePath . 'logo2.png';

        $headerHTML = '
            <table width="100%" cellpadding="0" cellspacing="0" style="border:none;">
                <tr>
                    <td width="20%" align="left" style="border:none;">
                        <img src="' . $logoPath . '" width="80">
                    </td>
                    <td width="80%" align="center" style="border:none;">
                        <div style="font-family:Montserrat, sans-serif;">
                            <h2 style="margin:0;">Reporte de ' . htmlspecialchars($nombreReporte) . '</h2>
                            <p style="margin:2px 0; font-size:12px;">Tu sistema para la gestión inteligente de equipos y asignaciones</p>
                            <p style="margin:0; font-size:11px;">Desarrollado por <b>Daniel Felipe Sánchez</b></p>
                        </div>
                    </td>
                </tr>
            </table>
            <hr style="border:2px solid #007BFF; margin-bottom:10px;">
        ';

        $mpdf->SetHTMLHeader($headerHTML);
        $mpdf->SetHTMLFooter('
            <div style="text-align:center; font-size:10px; color:#777; font-family:Montserrat, sans-serif;">
                Inventra © ' . date("Y") . ' - Todos los derechos reservados
            </div>
        ');

        $html = '
            <style>
                body { font-family: Montserrat, sans-serif; font-size: 11px; margin-top: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
                th { background-color: #007BFF; color: white; }
            </style>
            <table>
                <thead><tr>';
        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($headers as $header) {
                $html .= '<td>' . htmlspecialchars($row[$header]) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>
            <p style="text-align:right; font-size:10px; color:#777;">Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>';

        $mpdf->WriteHTML($html);
        $mpdf->Output("$nombreReporte.pdf", "D");
        exit;
    }

} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
