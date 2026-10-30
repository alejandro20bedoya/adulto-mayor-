<?php

class Errors extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function notFound()
    {
        http_response_code(404);

        $data['page_tag'] = NOMBRE_EMPESA;
        $data['page_title'] = NOMBRE_EMPESA . ' - Página no encontrada';
        $data['page_name'] = 'Página no encontrada';

        // Evita que la vista intente leer una página inexistente.
        $data['page'] = [
            'titulo' => 'Página no encontrada',
            'contenido' => 'La página que buscas no existe o no está disponible.',
            'portada' => ''
        ];

        $this->views->getView($this, 'error', $data);
    }
}

$notFound = new Errors();
$notFound->notFound();