<?php
require_once "../Config/Config.php";       // Configuración de conexión
require_once "../vendor/autoload.php";     // Carga automática de PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ==========================
// 🔹 CONEXIÓN A MYSQL
// ==========================
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// ==========================
// 🔹 PARÁMETRO DEL REPORTE
// ==========================
$tipo = $_GET['tipo'];

// ==========================
// 🔹 CREAR HOJA DE EXCEL
// ==========================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ==========================
// 🔹 CONFIGURACIÓN SEGÚN EL TIPO
// ==========================

if ($tipo === 'gestion') {
    $titulo = 'Gestion de videos';
    $encabezados = ['ID', 'Cedula', 'Nombre', 'Edad', 'Fecha', 'Telefono', 'tipo video', 'vereda', 'Apoderado', 'Cedula Apoderado', 'Fecha pago', 'Estado', 'Ciclo', 'Descripcion'];

    $sql = "SELECT 
            id AS ID,
            numerocedula AS Cedula,
            nombre AS Nombre,
            edad AS Edad,
            fecha AS Fecha,
            telefono AS Telefono,
            video_tipo AS `Tipo Video`,
            vereda AS Vereda,
            apoderado AS Apoderado,
            cedulaapoderado AS `Cedula Apoderado`,
            fecha_pago AS `Fecha Pago`,
            Estado AS Estado,
            ciclo AS Ciclo,
            descripción AS Descripcion
        FROM videosadultomayor";

    // ==========================================================
    // 🔸 TÍTULO centrado completamente (C → P)
    // ==========================================================
    $sheet->mergeCells('C2:P3');   // unimos toda la fila del título
    $sheet->setCellValue('C2', $titulo);

    // Alinear título
    $sheet->getStyle('C2')->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);

    // Estilo del texto del título
    $sheet->getStyle('C2')->getFont()
        ->setBold(true)->setSize(14)->getColor()->setARGB('FF003366');

    // Bordes del área del título
    $sheet->getStyle('C2:P3')->getBorders()->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    // Fondo gris del título
    $sheet->getStyle('C2:P2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB('E0E0E0');

    // ==========================================================
    // Espacio entre título y encabezado (fila 3 vacía)
    // ==========================================================
    $sheet->setCellValue('C3', '');

    // ==========================================================
    // Encabezados (fila 4)
    // ==========================================================
    $sheet->fromArray($encabezados, NULL, 'C4');

    // Estilo de encabezados
    $sheet->getStyle('C4:P4')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '41B300']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // ==========================================================
    // 🔹 CONSULTA Y LLENADO DE DATOS
    // ==========================================================
    $result = $conexion->query($sql);
    $fila = 5;

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columna = 'C';
            foreach ($row as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }
    } else {
        $sheet->setCellValue('C5', 'No se encontraron registros');
        $sheet->mergeCells('C5:P5');
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // ==========================================================
    // 🔹 ESTILOS FINALES
    // ==========================================================

    // Ajuste automático de columnas
    foreach (range('C', 'P') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Bordes de toda la tabla
    $ultimaFila = $fila - 1;
    $sheet->getStyle("C4:P$ultimaFila")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // Centrar datos
    $sheet->getStyle("C5:P$ultimaFila")->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // ==========================================================
    // 🔹 DESCARGA DEL ARCHIVO
    // ==========================================================
    $nombreArchivo = $tipo . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} else if ($tipo == 'usuarios') {
    $titulo = 'Reporte de Usuarios';
    $encabezados = ['ID', 'Identificación', 'Nombres', 'Apellidos', 'Celular', 'Correo', 'Rol'];

    $sql = "SELECT 
            u.ideusuario,
            u.identificacion,
            u.nombres,
            u.apellidos,
            u.celular,
            u.correo,
            r.nombrerol
        FROM tbl_usuarios AS u
        INNER JOIN rol AS r 
        ON u.rolid = r.idrol";

    // 🔸 Título (unir de C1 a K2)
    $sheet->mergeCells('C2:I3');
    $sheet->setCellValue('C2', $titulo);
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF003366');
    $sheet->getStyle('C2:I3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->getStyle('C2:I2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB('E0E0E0'); /// color gris para el fondo de la celda

    //  Espacio entre título y encabezado (fila vacía 3)
    $sheet->setCellValue('C3', ''); // fila vacía solo para espacio visual

    //  Encabezados (fila 4, desde C)
    $sheet->fromArray($encabezados, NULL, 'C4');

    // Estilo del encabezado
    $sheet->getStyle('C4:I4')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '41B300']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // ==========================
    // 🔹 CONSULTA Y LLENADO DE DATOS
    // ==========================
    $result = $conexion->query($sql);
    $fila = 5; // ⬅️ Comienza una fila más abajo (debajo del encabezado)

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columna = 'C'; // Inicia en C
            foreach ($row as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }
    } else {
        $sheet->setCellValue('C5', 'No se encontraron registros');
        $sheet->mergeCells('C5:I5');
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // ==========================
    // 🔹 ESTILOS FINALES
    // ==========================

    // Ajustar ancho automático (C → K)
    foreach (range('C', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Bordes para toda la tabla
    $ultimaFila = $fila - 1;
    $sheet->getStyle("C4:I$ultimaFila")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // Centrar el texto de los datos
    $sheet->getStyle("C5:I$ultimaFila")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // ==========================
    // 🔹 DESCARGA DEL ARCHIVO
    // ==========================
    $nombreArchivo = $tipo . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}