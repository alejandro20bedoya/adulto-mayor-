<?php

class Fichas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        session_regenerate_id(true);
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(MFICHAS);
    }

    public function Fichas()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Fichas";
        $data['page_title'] = "Formulario de Registro";
        $data['page_name'] = "fichas";
        $data['page_functions_js'] = "functions_fichas.js";
        $this->views->getView($this, "fichas", $data);
    }

    // CONTROLADOOR DE INSERCION DE FICHAS
    public function setFicha()
    {

        // MONITOR DE ERRORES
        error_reporting(E_ALL);
        ini_set('display_errors', 0);

        if ($_POST || $_FILES) {

            $cedula = strClean($_POST['cedula'] ?? '');
            $nombre = strClean($_POST['nombre'] ?? '');
            $edad   = intval($_POST['edad'] ?? 0);

            $videoData = null;
            $videoType = null;

            if (isset($_FILES['video']) && $_FILES['video']['error'] === 0) {

                // Validar tamaño (ej: 50MB)
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

            $request = $this->model->insertFicha(
                $cedula,
                $nombre,
                $edad,
                $videoData,
                $videoType
            );

            echo json_encode([
                'status' => $request > 0,
                'msg' => 'Registro guardado correctamente'
            ]);
            die();
        }
    }


    // mostrar lo de la tabla toda
    public function getFichas()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!empty($_SESSION['permisosMod']['r'])) {
            $arrData = $this->model->selectFichas();

            foreach ($arrData as &$row) {
                $row['status_ficha'] = ($row['status_ficha'] == 1)
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-danger">Inactivo</span>';

                $btnView = $btnEdit = $btnDelete = '';

                if (!empty($_SESSION['permisosMod']['r'])) {
                    $btnView = '<button class="btn btn-info" 
                        onClick="fntViewInfo(' . $row['ideficha'] . ')" 
                        title="Ver Ficha">
                        <i class="bi bi-eye"></i>
                    </button>';
                }

                if (!empty($_SESSION['permisosMod']['u'])) {
                    $btnEdit = '<button class="btn btn-warning" 
                        onClick="fntEditInfo(this,' . $row['ideficha'] . ')" 
                        title="Editar Ficha">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if (!empty($_SESSION['permisosMod']['d'])) {
                    $btnDelete = '<button class="btn btn-danger btnDelRol" 
                          onClick="fntDelInfo(' . $row['ideficha'] . ')" 
                          title="Eliminar Ficha">
                          <i class="bi bi-trash3"></i>
                      </button>';
                }

                $row['options'] = '<div class="text-center">'
                    . $btnView . ' ' . $btnEdit . ' ' . $btnDelete .
                    '</div>';
            }

            // 🔍 Ver qué se está devolviendo
            error_log('JSON enviado: ' . json_encode($arrData, JSON_UNESCAPED_UNICODE));

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([], JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    // MOSTRAR EN EL FORMULARIO DE EDICION
    public function getFicha($ideficha)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ideficha = intval($ideficha);
            if ($ideficha > 0) {
                $arrData = $this->model->selectFicha($ideficha);
                $arrResponse = empty($arrData)
                    ? ['status' => false, 'msg' => 'Datos no encontrados.']
                    : ['status' => true, 'data' => $arrData];
                header('Content-Type: application/json');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }


    //funcion para eliminar
    public function delFicha()
    {
        if ($_POST) {
            // Validar permisos
            if ($_SESSION['permisosMod']['d']) {
                $intIdeFicha = intval($_POST['ideficha']);
                $requestDelete = $this->model->deleteFicha($intIdeFicha);
                $arrResponse = $requestDelete
                    ? array('status' => true, 'msg' => 'Se ha eliminado la Ficha')
                    : array('status' => false, 'msg' => 'Error al eliminar la Ficha.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    /// funcion para mostrar el programa EN EL FORMULARIO
    public function getPrograma($codprograma)
    {
        // Validar permisos
        if (!isset($_SESSION['permisosMod']['r']) || $_SESSION['permisosMod']['r'] != 1) {
            echo json_encode(['status' => false, 'msg' => 'Sin permisos.'], JSON_UNESCAPED_UNICODE);
            die();
        }

        // Limpiar código (puede ser numérico o alfanumérico)
        $codprograma = strClean($codprograma);

        if ($codprograma != '') {
            $arrData = $this->model->selectPrograma($codprograma);
            $arrResponse = empty($arrData)
                ? ['status' => false, 'msg' => 'Datos no encontrados.']
                : ['status' => true, 'data' => $arrData];

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['status' => false, 'msg' => 'Código vacío.'], JSON_UNESCAPED_UNICODE);
        }

        die();
    }

    // MOSTRAR EL INSTRUTOR EN EL FORMULARIO
    public function getInstructor($identificacion)
    {

        if ($_SESSION['permisosMod']['r']) {
            $identificacion = strClean($identificacion); // 🔹 antes estaba intval()
            if (!empty($identificacion)) {
                $arrData = $this->model->selectInstructor($identificacion);
                $arrResponse = empty($arrData)
                    ? array('status' => false, 'msg' => 'Datos no encontrados.')
                    : array('status' => true, 'data' => $arrData);
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
}
