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
                            title="Ver Programa">
                        <i class="bi bi-eye"></i>
                    </button>';
                }

                // 3️⃣ BOTÓN EDITAR
                if ($_SESSION['permisosMod']['u']) {
                    $btnEdit = '
                    <button class="btn btn-warning btn-sm" 
                            onClick="fntEditInfo(this,' . $idAdultoMayor . ')" 
                            title="Editar Programa">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                // 4️⃣ BOTÓN ELIMINAR
                if ($_SESSION['permisosMod']['d']) {
                    $btnDelete = '
                    <button class="btn btn-danger btn-sm" 
                            onClick="fntDelInfo(' . $idAdultoMayor . ')" 
                            title="Eliminar Programa">
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

    // ver video del adulto mayor
    public function getVideo($id)
    {
        $id = intval($id);
        $data = $this->model->selectVideo($id);

        if ($data) {

            $videoBase64 = "data:" . $data["video_tipo"] . ";base64," . base64_encode($data["video"]);

            $arrResponse = array(
                "status" => true,
                "data" => array(
                    "video_url"  => $videoBase64,
                    "video_tipo" => $data["video_tipo"]
                )
            );
        } else {
            $arrResponse = array("status" => false, "msg" => "No se encontró el video.");
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
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
