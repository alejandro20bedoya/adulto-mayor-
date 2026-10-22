<?php

class Dashboard extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        //session_regenerate_id(true);
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(RADMINISTRADOR);
    }
    // Dashboard principal 
    public function dashboard()
    {
        $data['page_id'] = 2;
        $data['page_tag'] = "Administrador";
        $data['page_title'] = " Administrador";
        $data['page_name'] = "Administrador";
        $data['page_functions_js'] = "functions_dashboard.js";
        $data['usuarios'] = $this->model->cantUsuarios();


        if ($_SESSION['userData']['idrol'] == RCOORDINADOR) {
            $this->views->getView($this, "dashboardCoordinador", $data);
        } else {
            $this->views->getView($this, "dashboard", $data);
        }
    }

}
