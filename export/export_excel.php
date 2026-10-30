<?php

ob_start();

require_once "../Config/Config.php";
require_once "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ==========================================================
// CONEXIÓN A MYSQL
// ==========================================================

mysqli_report(MYSQLI_REPORT_OFF);

$conexion = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME
);

if ($conexion->connect_error) {
    ob_end_clean();
    http_response_code(500);
    exit("Error de conexión a MySQL.");
}

$conexion->set_charset("utf8mb4");

// ==========================================================
// VALIDAR TIPO DE REPORTE
// ==========================================================

$tipo = $_GET['tipo'] ?? '';

if (!in_array($tipo, ['gestion', 'usuarios'], true)) {
    ob_end_clean();
    http_response_code(400);
    exit("Tipo de reporte no válido.");
}

// ==========================================================
// CREAR ARCHIVO EXCEL
// ==========================================================

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Reporte');

// ==========================================================
// ESTILOS GENERALES
// ==========================================================

$estiloEncabezado = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => '000000']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '41B300']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
];

$estiloBordes = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
];

// ==========================================================
// REPORTE DE GESTIÓN DE VIDEOS
// ==========================================================

if ($tipo === 'gestion') {

    $titulo = 'Gestión de videos';

    $encabezados = [
        'ID',
        'Cedula',
        'Nombre',
        'Edad',
        'Fecha',
        'Telefono',
        'Tipo Video',
        'Vereda',
        'Zona',
        'Apoderado',
        'Cedula Apoderado',
        'Fecha Pago',
        'Estado',
        'Ciclo',
        'Descripcion'
    ];

    $sql = "SELECT
                id AS ID,
                numerocedula AS Cedula,
                nombre AS Nombre,
                edad AS Edad,
                fecha AS Fecha,
                telefono AS Telefono,
                video_tipo AS Tipo_Video,
                vereda AS Vereda,
                zona AS Zona,
                apoderado AS Apoderado,
                cedulaapoderado AS Cedula_Apoderado,
                fecha_pago AS Fecha_Pago,
                Estado AS Estado,
                ciclo AS Ciclo,
                descripción AS Descripcion
            FROM videosadultomayor
            ORDER BY id ASC";

    $result = $conexion->query($sql);

    if ($result === false) {
        ob_end_clean();
        $conexion->close();
        http_response_code(500);
        exit("Error al consultar los registros de gestión.");
    }

    // Título: 15 columnas, desde C hasta Q.
    $sheet->mergeCells('C2:Q3');
    $sheet->setCellValue('C2', $titulo);

    $sheet->getStyle('C2')->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->getStyle('C2')->getFont()
        ->setBold(true)
        ->setSize(14)
        ->getColor()
        ->setARGB('FF003366');

    $sheet->getStyle('C2:Q3')
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    $sheet->getStyle('C2:Q2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('E0E0E0');

    // Encabezados.
    $sheet->fromArray($encabezados, null, 'C4');

    $sheet->getStyle('C4:Q4')->applyFromArray(
        $estiloEncabezado
    );

    // Datos.
    $fila = 5;

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $columna = 'C';

            foreach ($row as $valor) {
                $sheet->setCellValue(
                    $columna . $fila,
                    $valor
                );

                $columna++;
            }

            $fila++;
        }
    } else {

        $sheet->mergeCells('C5:Q5');

        $sheet->setCellValue(
            'C5',
            'No se encontraron registros'
        );

        $sheet->getStyle('C5')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $fila = 6;
    }

    $result->free();

    // Ajustar ancho de las 15 columnas.
    foreach (range('C', 'Q') as $columna) {
        $sheet->getColumnDimension($columna)
            ->setAutoSize(true);
    }

    // Bordes de encabezados y datos.
    $ultimaFila = max(5, $fila - 1);

    $sheet->getStyle(
        "C4:Q{$ultimaFila}"
    )->applyFromArray($estiloBordes);

    // Centrar los datos.
    if ($fila > 5) {
        $sheet->getStyle(
            "C5:Q" . ($fila - 1)
        )->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // Congelar encabezados.
    $sheet->freezePane('C5');

    // ==========================================================
    // REPORTE DE USUARIOS
    // ==========================================================

} elseif ($tipo === 'usuarios') {

    $titulo = 'Reporte de usuarios';

    $encabezados = [
        'ID',
        'Identificación',
        'Nombres',
        'Apellidos',
        'Celular',
        'Correo',
        'Rol'
    ];

    $sql = "SELECT
                u.ideusuario AS ID,
                u.identificacion AS Identificacion,
                u.nombres AS Nombres,
                u.apellidos AS Apellidos,
                u.celular AS Celular,
                u.correo AS Correo,
                r.nombrerol AS Rol
            FROM tbl_usuarios AS u
            INNER JOIN rol AS r
                ON u.rolid = r.idrol
            ORDER BY u.ideusuario ASC";

    $result = $conexion->query($sql);

    if ($result === false) {
        ob_end_clean();
        $conexion->close();
        http_response_code(500);
        exit("Error al consultar los usuarios.");
    }

    // Título: siete columnas, desde C hasta I.
    $sheet->mergeCells('C2:I3');
    $sheet->setCellValue('C2', $titulo);

    $sheet->getStyle('C2')->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->getStyle('C2')->getFont()
        ->setBold(true)
        ->setSize(14)
        ->getColor()
        ->setARGB('FF003366');

    $sheet->getStyle('C2:I3')
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    $sheet->getStyle('C2:I2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('E0E0E0');

    // Encabezados.
    $sheet->fromArray($encabezados, null, 'C4');

    $sheet->getStyle('C4:I4')->applyFromArray(
        $estiloEncabezado
    );

    // Datos.
    $fila = 5;

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $columna = 'C';

            foreach ($row as $valor) {
                $sheet->setCellValue(
                    $columna . $fila,
                    $valor
                );

                $columna++;
            }

            $fila++;
        }
    } else {

        $sheet->mergeCells('C5:I5');

        $sheet->setCellValue(
            'C5',
            'No se encontraron usuarios'
        );

        $sheet->getStyle('C5')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $fila = 6;
    }

    $result->free();

    // Ajustar las siete columnas.
    foreach (range('C', 'I') as $columna) {
        $sheet->getColumnDimension($columna)
            ->setAutoSize(true);
    }

    // Bordes.
    $ultimaFila = max(5, $fila - 1);

    $sheet->getStyle(
        "C4:I{$ultimaFila}"
    )->applyFromArray($estiloBordes);

    // Centrar datos.
    if ($fila > 5) {
        $sheet->getStyle(
            "C5:I" . ($fila - 1)
        )->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    $sheet->freezePane('C5');
}

// ==========================================================
// GENERAR Y DESCARGAR EL ARCHIVO
// ==========================================================

$nombreArchivo = $tipo . '.xlsx';

try {

    // Evitar que advertencias o espacios dañen el archivo.
    if (ob_get_length() !== false && ob_get_length() > 0) {
        ob_clean();
    }

    $conexion->close();

    header(
        'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );

    header(
        'Content-Disposition: attachment; filename="' . $nombreArchivo . '"'
    );

    header('Cache-Control: max-age=0');
    header('Pragma: public');

    $writer = new Xlsx($spreadsheet);

    $writer->save('php://output');

    $spreadsheet->disconnectWorksheets();

    exit;
} catch (\Throwable $e) {

    if (ob_get_level() > 0) {
        ob_end_clean();
    }

    if (!headers_sent()) {
        http_response_code(500);
    }

    error_log(
        'Error al exportar Excel: ' . $e->getMessage()
    );

    exit('No fue posible generar el archivo Excel. Revisa el registro de errores de PHP.');
}
