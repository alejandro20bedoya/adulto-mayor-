<?php
class FichasModel extends Mysql
{
    private $intIdeFicha;       // ideficha (PK)
    private $intProgramaIde;    // programaide (FK a tbl_programas)
    private $intNumeroFicha;    // numeroficha
    private $intUsuarioIde;     // usuarioide (FK a tbl_usuarios → instructor/líder)
    private $intStatus;
    private $intCodPrograma;
    private $intIdentificacion;

    public function __construct()
    {
        parent::__construct();
    }


    // lo que se va a ver en la tabla de fichas 
    public function selectFichas()
    {
        $sql = "SELECT 
                tf.ideficha,
                tf.numeroficha,
                tf.status AS status_ficha,
                tp.nombreprograma,
                tu.nombres
            FROM tbl_fichas tf
            LEFT JOIN tbl_programas tp ON tp.ideprograma = tf.programaide
            LEFT JOIN tbl_usuarios tu  ON tu.ideusuario = tf.usuarioide
            ORDER BY tf.ideficha DESC"; // 👈 NUEVAS PRIMERO

        return $this->select_all($sql);
    }

    /// insertar ficha A LA BASE DE DATOS 
    public function insertFicha(
        $cedula,
        $nombre,
        $edad,
        $fecha,
        $telefono,
        $video,
        $videoType,
        $vereda,
        $apoderado,
        $cedulaapodera,
        $fechapago,
        $estado,
        $ciclo,
        $descripcion
    ) {

        // 1️⃣ Verificar si la cédula ya existe
        $sqlExist = "SELECT id FROM videosadultomayor WHERE numerocedula = ?";
        $arrExist = array($cedula);
        $exist = $this->select($sqlExist, $arrExist);

        if (!empty($exist)) {
            return -1; // ya existe
        }

        // 2️⃣ Insertar nuevo registro
        $sql = "INSERT INTO videosadultomayor 
        (numerocedula, nombre, edad, fecha, telefono, video, video_tipo, vereda, apoderado, cedulaapoderado, fecha_pago, Estado, ciclo, descripción)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $arrData = array(
            $cedula,
            $nombre,
            $edad,
            $fecha,
            $telefono,
            $video,
            $videoType,
            $vereda,
            $apoderado,
            $cedulaapodera,
            $fechapago,
            $estado,
            $ciclo,
            $descripcion
        );

        return $this->insert($sql, $arrData);
    }


    //ACTUALIZAR FICHA
    public function updateFicha(int $ideficha, int $codigoprograma, int $codigoficha, int $ideinstructor, int $status)
    {

        $this->intIdeFicha = $ideficha;
        $this->intProgramaIde = $codigoprograma;
        $this->intNumeroFicha = $codigoficha;
        $this->intUsuarioIde = $ideinstructor;
        $this->intStatus = $status;

        $sql = "UPDATE tbl_fichas SET programaide = ?, numeroficha = ?, usuarioide = ?, status = ? WHERE ideficha = ? ";
        $arrData = [$this->intProgramaIde, $this->intNumeroFicha, $this->intUsuarioIde, $this->intStatus, $this->intIdeFicha,];
        $request = $this->update($sql, $arrData);

        return $request;
    }

    //eliminar ficha
    public function deleteFicha(int $intIdeFicha)
    {
        $this->intIdeFicha = intval($intIdeFicha); // seguridad

        // Consulta DELETE
        $sql = "DELETE FROM tbl_fichas WHERE ideficha = $this->intIdeFicha";

        // Ejecutamos la consulta
        $request = $this->delete($sql, []); // ⚡ dependiendo de tu clase, pasa array vacío si exige 2 parámetros

        return $request;
    }


    //VISTA INFORMACIÓN PROGRAMA
    public function selectPrograma(string $codprograma)
    {
        $this->intCodPrograma = $codprograma;
        $sql = "SELECT *
            FROM tbl_programas
            WHERE codigoprograma = '{$this->intCodPrograma}'";
        $request = $this->select($sql);
        return $request;
    }

    //VISTA INFORMACIÓN PROGRAMA
    public function selectInstructor(string $identificacion)
    {
        $sql = "SELECT identificacion, nombres
            FROM tbl_usuarios
            WHERE identificacion = $identificacion 
              AND rolid = 4";

        $request = $this->select($sql); // ✅ ejecuta la consulta
        return $request;
    }
}
