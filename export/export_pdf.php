<?php
require_once "../Config/Config.php";
require_once "../vendor/autoload.php"; // Carga automática de mPDF

use Mpdf\Mpdf;

// ==========================
// 🔹 CONEXIÓN A MYSQL
// ==========================
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

$tipo = $_GET['tipo'];

if ($tipo === 'gestion') {
  // ==========================
  // 🔹 CONSULTA DE DATOS
  // ==========================
  $sql = "SELECT 
                id AS ID,
                numerocedula AS Cedula,
                nombre AS Nombre,
                edad AS Edad,
                fecha AS Fecha,
                telefono AS Telefono,
                video_tipo AS TipoVideo,
                vereda AS Vereda,
                apoderado AS Apoderado,
                cedulaapoderado AS CedulaApoderado,
                fecha_pago AS FechaPago,
                Estado AS Estado,
                ciclo AS Ciclo,
                descripción AS Descripcion
            FROM videosadultomayor";

  $result = $conexion->query($sql);

  // 🔹 CREAR INSTANCIA DE MPDF 
  $mpdf = new Mpdf([
    'margin_top' => 5,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10
  ]);

  // ==========================
  // 🔹 CONTENIDO DEL PDF
  // ==========================
  $html = '
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #41B300;
            color: #000000;
            font-weight: bold;
        }
        td.fecha, td.hora {
            white-space: nowrap !important;
        }
        h3 {
            text-align: center;
            color: #000;
            margin-top: 0;
            font-size: 16px;
        }
    </style>

    <h3>GESTIÓN DE VIDEOS</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Fecha</th>
                <th>Teléfono</th>
                <th>Tipo Video</th>
                <th>Vereda</th>
                <th>Apoderado</th>
                <th>Cédula Apoderado</th>
                <th>Fecha Pago</th>
                <th>Estado</th>
                <th>Ciclo</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>';

  // ==========================
  // 🔹 LLENAR EL PDF CON LOS DATOS
  // ==========================
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

      $html .= '
            <tr>
                <td>' . htmlspecialchars($row['ID']) . '</td>
                <td>' . htmlspecialchars($row['Cedula']) . '</td>
                <td>' . htmlspecialchars($row['Nombre']) . '</td>
                <td>' . htmlspecialchars($row['Edad']) . '</td>
                <td>' . htmlspecialchars($row['Fecha']) . '</td>
                <td>' . htmlspecialchars($row['Telefono']) . '</td>
                <td>' . htmlspecialchars($row['TipoVideo']) . '</td>
                <td>' . htmlspecialchars($row['Vereda']) . '</td>
                <td>' . htmlspecialchars($row['Apoderado']) . '</td>
                <td>' . htmlspecialchars($row['CedulaApoderado']) . '</td>
                <td>' . htmlspecialchars($row['FechaPago']) . '</td>
                <td>' . htmlspecialchars($row['Estado']) . '</td>
                <td>' . htmlspecialchars($row['Ciclo']) . '</td>
                <td>' . htmlspecialchars($row['Descripcion']) . '</td>
            </tr>';
    }
  } else {
    $html .= '
            <tr>
                <td colspan="14" style="text-align:center; color:red;">No se encontraron registros.</td>
            </tr>';
  }

  $html .= '
        </tbody>
    </table>';

  // ==========================
  // 🔹 PIE DE PÁGINA
  // ==========================
  $mpdf->SetHTMLFooter('
        <hr>
        <div style="text-align:center; font-size:10px; color:#555;">
            SIGMA © ' . date('Y') . ' — Página {PAGENO}
        </div>
    ');

  // ==========================
  // 🔹 GENERAR Y DESCARGAR PDF
  // ==========================
  $mpdf->WriteHTML($html);
  $mpdf->Output('gestion_videos.pdf', 'D');
  exit;
} else if ($tipo === 'usuarios') {

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
  $result = $conexion->query($sql);

  // 🔹 CREAR INSTANCIA DE MPDF 
  $mpdf = new Mpdf([
    'margin_top' => 5,     // casi sin espacio superior
    'margin_bottom' => 15, // margen inferior normal
    'margin_left' => 10,
    'margin_right' => 10
  ]);


  // ==========================
  // 🔹 CONTENIDO DEL PDF
  // ==========================
  $html = '
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
      vertical-align: middle;
    }
    th {
      background-color: #41B300;
      color: #000000;
    }
    td.fecha, td.hora {
      white-space: nowrap !important;
    }
  </style>

  <h3 style="text-align:center; color:#000; margin-top:0;">LISTADO DE USUARIOS</h3>
  <table border="1" width="100%" cellspacing="0" cellpadding="6"
        style="border-collapse:collapse; font-size:11px; color:#000;">
      <thead>
          <tr style="background-color:#41B300; color:#000000; text-align:center; font-weight:bold;">
              <th width="10%">ID</th>
              <th width="15%">Identificación</th>
              <th width="15%">Nombres</th>
              <th width="15%">Apellidos</th>
              <th width="10%">Celular</th>
              <th width="15%">Correo</th>
              <th width="15%">Rol</th>
          </tr>
      </thead>
    <tbody>';

  // ==========================
  // 🔹 LLENAR LA TABLA CON LOS DATOS
  // ==========================
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $html .= '
        <tr style="text-align:center;">
            <td>' . htmlspecialchars($row['ideusuario']) . '</td>
            <td>' . htmlspecialchars($row['identificacion']) . '</td> 
            <td>' . htmlspecialchars($row['nombres']) . '</td>  
            <td>' . htmlspecialchars($row['apellidos']) . '</td>
            <td>' . htmlspecialchars($row['celular']) . '</td>
            <td>' . htmlspecialchars($row['correo']) . '</td>
            <td>' . htmlspecialchars($row['nombrerol']) . '</td>
        </tr>';
    }
  } else {
    $html .= '
        <tr>
            <td colspan="9" style="text-align:center; color:red;">No se encontraron registros.</td>
        </tr>';
  }

  $html .= '
    </tbody>
  </table>';

  // ==========================
  // 🔹 PIE DE PÁGINA
  // ==========================
  $mpdf->SetHTMLFooter('
  <hr>
  <div style="text-align:center; font-size:10px; color:#555;">
    SIGMA © ' . date('Y') . ' — Página {PAGENO}
  </div>
  ');

  // ==========================
  // 🔹 GENERAR Y DESCARGAR PDF
  // ==========================
  $mpdf->WriteHTML($html);
  $mpdf->Output('reporte_asignaciones.pdf', 'D');
  exit;
}
