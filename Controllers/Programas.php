<?php

class Programas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();

        // Para evitar regenerar ID en cada petición AJAX
        if (empty($_SESSION['regenerated'])) {
            session_regenerate_id(true);
            $_SESSION['regenerated'] = true;
        }

        // Métodos permitidos sin redirect
        $rutasPermitidas = [
            "getAdultoMayor",
            "getAdultoMayor",
            "geteditarAdultoMayor",
            "getAdultoMayor",
            "verVideo",
        ];

        $metodoActual = $_GET['method'] ?? "";

        // Si NO hay sesión y el método NO está permitido
        if (empty($_SESSION['login']) && !in_array($metodoActual, $rutasPermitidas)) {
            header("Location: " . base_url() . "/login");
            exit;
        }

        getPermisos(MPROGRAMAS);
    }

    public function Programas()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Programas";
        $data['page_title'] = "Gestion de videos";
        $data['page_name'] = "programas";
        $data['page_functions_js'] = "functions_programas.js";
        $this->views->getView($this, "programas", $data);
    }

    // editar adulto mayor
    public function setAdultomayor()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 0);

        if ($_POST || $_FILES) {

            $id = intval($_POST['adultoid'] ?? 0);

            if ($id <= 0) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'ID inválido o faltante'
                ]);
                die();
            }

            // ✔ CORREGIDO PARA COINCIDIR CON EL FORM
            $cedula   = strClean($_POST['txtNumeroCedula'] ?? '');
            $nombre   = strClean($_POST['txtNombreAdulto'] ?? '');
            $edad     = intval($_POST['txtEdadAdulto'] ?? 0);
            $fecha    = strClean($_POST['txtFecha'] ?? '');
            $telefono = strClean($_POST['txttelefono'] ?? '');
            $vereda   = strClean($_POST['txtVereda'] ?? '');
            $zona     = strClean($_POST['zona'] ?? '');

            // Manejo del video
            $videoData = null;
            $videoType = null;

            // informacion del apoderado
            $apoderado = strClean($_POST['txtNombreApoderado'] ?? '');
            $cedulaapoderado    = strClean($_POST['txtNumeroCedulaApoderado'] ?? '');
            $fechapago    = strClean($_POST['txtFechapago'] ?? '');
            $estado    = strClean($_POST['txtEstado'] ?? '');
            $numerociclos    = strClean($_POST['txtNumeroCiclos'] ?? '');
            $descripcion    = strClean($_POST['txtDescripcion'] ?? '');


            if (isset($_FILES['video']) && $_FILES['video']['error'] === 0) {

                if ($_FILES['video']['size'] > 50 * 1024 * 1024) {
                    echo json_encode([
                        'status' => false,
                        'msg' => 'El video supera el tamaño permitido'
                    ]);
                    die();
                }

                $videoType = $_FILES['video']['type'];
                $videoData = file_get_contents($_FILES['video']['tmp_name']);
            }

            $request = $this->model->updateAdultomayor(
                $id,
                $cedula,
                $nombre,
                $edad,
                $fecha,
                $telefono,
                $videoData,
                $videoType,
                $vereda,
                $zona,
                $apoderado,
                $cedulaapoderado,
                $fechapago,
                $estado,
                $numerociclos,
                $descripcion
            );

            echo json_encode([
                'status' => true,
                'msg'    => 'Registro actualizado correctamente'
            ]);
            die();
        }
    }

    // lista de la tabla de programas
    public function getAdultoMayor()
    {
        if ($_SESSION['permisosMod']['r']) {

            $arrData = $this->model->selectProgramas();

            for ($i = 0; $i < count($arrData); $i++) {

                // STATUS ADULTO MAYOR
                $status = $arrData[$i]['status_adultomayor'] ?? 0;

                $arrData[$i]['status_adultomayor'] = ($status == 1)
                    ? '<span class="badge bg-success">Verificado</span>'
                    : '<span class="badge bg-danger">No verificado</span>';

                // ID DEL REGISTRO (aduto mayor)
                $idAdultoMayor = $arrData[$i]['id'] ?? 0;

                // Botones
                $btnView = $btnEdit = $btnDelete = $btnVideo = '';

                // 1️⃣ BOTÓN REPRODUCIR VIDEO
                $btnVideo = '
                <button class="btn btn-primary btn-sm" 
                        onClick="verVideo(' . $idAdultoMayor . ')" 
                        title="Reproducir Video">
                    <i class="bi bi-play-circle"></i>
                </button>';

                // 2️⃣ BOTÓN VER DETALLES
                if ($_SESSION['permisosMod']['r']) {
                    $btnView = '
                    <button class="btn btn-info btn-sm" 
                            onClick="fntViewInfo(' . $idAdultoMayor . ')" 
                            title="Ver información">
                        <i class="bi bi-eye"></i>
                    </button>';
                }

                // 3️⃣ BOTÓN EDITAR
                if ($_SESSION['permisosMod']['u']) {
                    $btnEdit = '
                    <button class="btn btn-warning btn-sm" 
                            onClick="fntEditInfo(this,' . $idAdultoMayor . ')" 
                            title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                // 4️⃣ BOTÓN ELIMINAR
                if ($_SESSION['permisosMod']['d']) {
                    $btnDelete = '
                    <button class="btn btn-danger btn-sm" 
                            onClick="fntDelInfo(' . $idAdultoMayor . ')" 
                            title="Eliminar">
                        <i class="bi bi-trash3"></i>
                    </button>';
                }

                // OPCIONES
                $arrData[$i]['options'] = '
                <div class="text-center">
                    ' . $btnVideo . ' 
                    ' . $btnView . ' 
                    ' . $btnEdit . ' 
                    ' . $btnDelete . '
                </div>';
            }

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    // ver video del adulto mayor// Reproducir video del adulto mayor
    public function getVideo($id) {
        $id = intval($id);

        if ($id <= 0) {
            http_response_code(400);
            exit('ID no válido');
        }

        $data = $this->model->selectVideo($id);

        if (!$data || empty($data['video'])) {
            http_response_code(404);
            exit('No se encontró el video');
        }

        $video = $data['video'];
        $tamano = strlen($video);

        $tipo = $data['video_tipo'] ?? 'video/mp4';

        // Permitir solamente tipos de video conocidos
        $tiposPermitidos = [
            'video/mp4',
            'video/webm',
            'video/ogg',
            'video/quicktime'
        ];

        if (!in_array(strtolower($tipo), $tiposPermitidos, true)) {
            $tipo = 'application/octet-stream';
        }

        // Evitar que otros datos se mezclen con el video
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: ' . $tipo);
        header('Accept-Ranges: bytes');
        header('Cache-Control: private, max-age=3600');
        header('X-Content-Type-Options: nosniff');

        // Revisar si el navegador solicita una parte del video
        $inicio = 0;
        $fin = $tamano - 1;

        if (isset($_SERVER['HTTP_RANGE'])) {

            if (
                !preg_match(
                    '/^bytes=(\d*)-(\d*)$/',
                    trim($_SERVER['HTTP_RANGE']),
                    $coincidencias
                )
            ) {
                http_response_code(416);
                header("Content-Range: bytes */$tamano");
                exit;
            }

            $rangoInicio = $coincidencias[1];
            $rangoFin = $coincidencias[2];

            if ($rangoInicio === '' && $rangoFin === '') {
                http_response_code(416);
                header("Content-Range: bytes */$tamano");
                exit;
            }

            // Rango de los últimos bytes del video
            if ($rangoInicio === '') {
                $cantidad = intval($rangoFin);

                if ($cantidad <= 0) {
                    http_response_code(416);
                    header("Content-Range: bytes */$tamano");
                    exit;
                }

                $inicio = max(0, $tamano - $cantidad);
            } else {
                $inicio = intval($rangoInicio);

                if ($rangoFin !== '') {
                    $fin = intval($rangoFin);
                }
            }

            if (
                $inicio >= $tamano ||
                $fin < $inicio ||
                $inicio < 0
            ) {
                http_response_code(416);
                header("Content-Range: bytes */$tamano");
                exit;
            }

            $fin = min($fin, $tamano - 1);

            http_response_code(206);

            header(
                "Content-Range: bytes $inicio-$fin/$tamano"
            );
        }

        $longitud = $fin - $inicio + 1;

        header('Content-Length: ' . $longitud);

        echo substr($video, $inicio, $longitud);
        exit;
    }

    // vista del modal ver
    public function getVistaAdultoMayor($ideprograma)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ideprograma = intval($ideprograma);
            if ($ideprograma > 0) {
                $arrData = $this->model->selectadultomayorver($ideprograma);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    // vista de la infoamcion en el modal editar
    public function getVistaAdultoMayorEditar($ideprograma)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ideprograma = intval($ideprograma);
            if ($ideprograma > 0) {
                $arrData = $this->model->selectadultomayoreditar($ideprograma);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    public function getEliminarAdulto()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['d']) {
                $idAdultoMayor = intval($_POST['idadulto']);
                $requestDelete = $this->model->deleteAdulto($idAdultoMayor);

                if ($requestDelete) {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Programa');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar al Programa.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
}
