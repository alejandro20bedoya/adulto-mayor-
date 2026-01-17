<?php
class ProgramasModel extends Mysql
{
    private $intIdePrograma;
    private $intIdeAdulto;
    private $intIdPrograma;
    private $strCodigoPrograma;
    private $strNivelPrograma;
    private $strNombrePrograma;
    private $strHorasPrograma;
    private $strStatus;


    public function __construct()
    {
        parent::__construct();
    }

    public function updateAdultomayor(
        int $id,
        int $numerocedula,
        string $nombre,
        int $edad,
        string $fecha,
        string $telefono,
        $videoData,
        $videoType,
        string $vereda,
        string $apoderado,
        string $cedulaapoderado,
        string $fechapago,
        string $estado,
        string $numerociclos,
        string $descripcion
    ) {

        // ▶ Si viene video nuevo
        if ($videoData !== null && $videoType !== null) {

            $sql = "UPDATE videosadultomayor 
            SET numerocedula = ?, 
                nombre = ?, 
                edad = ?, 
                fecha = ?, 
                telefono = ?, 
                video = ?, 
                video_tipo = ?, 
                vereda = ?,
                apoderado = ?,
                cedulaapoderado = ?,
                fecha_pago = ?,
                Estado = ?,
                ciclo = ?,
                descripción = ?
            WHERE id = ?";

            $arrData = array(
                $numerocedula,
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
                $descripcion,
                $id
            );
        } else {

            // ▶ Sin video
            $sql = "UPDATE videosadultomayor 
            SET numerocedula = ?, 
                nombre = ?, 
                edad = ?, 
                fecha = ?, 
                telefono = ?,
                vereda = ?,
                apoderado = ?,
                cedulaapoderado = ?,
                fecha_pago = ?,
                Estado = ?,
                ciclo = ?,
                descripción = ?
            WHERE id = ?";

            $arrData = array(
                $numerocedula,
                $nombre,
                $edad,
                $fecha,
                $telefono,
                $vereda,
                $apoderado,
                $cedulaapoderado,
                $fechapago,
                $estado,
                $numerociclos,
                $descripcion,
                $id
            );
        }

        return $this->update($sql, $arrData);
    }


    // LISTADO DE LA TABLA
    public function selectProgramas()
    {
        $sql = "SELECT 
                id,
                numerocedula,
                nombre,
                edad,
                fecha,
                telefono,
                video_tipo,
                Estado AS status_adultomayor
            FROM videosadultomayor";
        $request = $this->select_all($sql);
        return $request;
    }

    // para reproduci el video
    public function selectVideo(int $id)
    {
        $sql = "SELECT video, video_tipo 
                FROM videosadultomayor 
                WHERE id = $id";

        $request = $this->select($sql);
        return $request;
    }

    //VISTA INFORMACIÓN PROGRAMA
    public function selectPrograma(int $ideprograma)
    {


        $this->intIdePrograma = $ideprograma;

        $sql = "SELECT 
    			id,
    			numerocedula,
    			nombre,
    			edad,
    			fecha,
    			telefono,
    			video_tipo
    			FROM videosadultomayor
    			WHERE id = $this->intIdePrograma";
        $request = $this->select($sql);
        return $request;
    }

    //VISTA INFORMACIÓN PROGRAMA
    public function selectadultomayor(int $ideprograma)
    {


        $this->intIdePrograma = $ideprograma;

        $sql = "SELECT 
    			id,
    			numerocedula,
    			nombre,
    			edad,
    			fecha,
    			telefono,
    			video_tipo
    			FROM videosadultomayor
    			WHERE id = $this->intIdePrograma";
        $request = $this->select($sql);
        return $request;
    }

    //VISTA INFORMACIÓN PROGRAMA
    public function selectadultomayorver(int $ideprograma)
    {


        $this->intIdePrograma = $ideprograma;

        $sql = "SELECT 
    			id,
    			numerocedula,
    			nombre,
    			edad,
    			fecha,
    			telefono,
    			video_tipo,
                vereda,
                apoderado,
                cedulaapoderado,
                fecha_pago,
                Estado as estado,
                ciclo,
                descripción as descripcion
    			FROM videosadultomayor
    			WHERE id = $this->intIdePrograma";
        $request = $this->select($sql);
        return $request;
    }

    // vista del editar
    public function selectadultomayoreditar(int $ideprograma)
    {


        $this->intIdePrograma = $ideprograma;

        $sql = "SELECT 
    			id,
    			numerocedula,
    			nombre,
    			edad,
    			fecha,
    			telefono,
    			video_tipo,
                vereda,
                apoderado,
                cedulaapoderado,
                fecha_pago,
                Estado as estado,
                ciclo,
                descripción as descripcion
    			FROM videosadultomayor
    			WHERE id = $this->intIdePrograma";
        $request = $this->select($sql);
        return $request;
    }

    // eliminar 
    public function deleteAdulto(int $idAdultoMayor)
    {
        $this->intIdeAdulto = $idAdultoMayor;
        $sql = "DELETE FROM videosadultomayor WHERE id = $this->intIdeAdulto";
        $request = $this->delete($sql);
        return $request;
    }
}
