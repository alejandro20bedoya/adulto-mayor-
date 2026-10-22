<?php
class UsuariosModel extends Mysql
{
    private $intIdeUsuario;
    private $strIdentificacionUsuario;
    private $strNombresUsuario;  /// sonas de cambio 
    private $strApellidosUsuario;
    private $strCelularUsuario;
    private $strCorreoUsuario;
    private $strPassword;
    private $strRolUsuario;
    private $strStatusUsuario;
    private $strStatus;


    public function __construct()
    {
        parent::__construct();
    }

    // insertar datos a la base de datos o gregar usuario
    public function insertUsuario(
        string $identificacion,
        string $nombres,  // sonas de cambio 
        string $apellidos,
        string $celular,
        string $correo,
        // string $password,
        string $rol,
        string $status,
        $contrasena
    ) {
        $this->strIdentificacionUsuario = $identificacion;
        $this->strNombresUsuario = $nombres;
        $this->strApellidosUsuario = $apellidos;  /// sonas de cambio 
        $this->strCelularUsuario = $celular;  /// sonas de cambio 
        $this->strCorreoUsuario = $correo;  /// sonas de cambio 
        // $this->strPassword = $password;
        $this->strRolUsuario = $rol;
        $this->strStatusUsuario = $status;
        $this->strPassword = $contrasena;

        $return = 0;
        $sql = "SELECT * FROM tbl_usuarios WHERE
				identificacion = '{$this->strIdentificacionUsuario}'";
        $request = $this->select_all($sql);



        if (empty($request)) {

            // $rs = 1;
            $query_insert = "INSERT INTO tbl_usuarios(identificacion,nombres,apellidos,celular,correo,password,rolid,status)
            VALUES(?,?,?,?,?,?,?,?)";

            $arrData = array(
                $this->strIdentificacionUsuario,
                $this->strNombresUsuario,   // sonas de cambio 
                $this->strApellidosUsuario,
                $this->strCelularUsuario,
                $this->strCorreoUsuario,
                $this->strPassword,
                $this->strRolUsuario,
                $this->strStatusUsuario
            );

            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;

            if ($update) {
                return "editado";   // ← IMPORTANTE
            } else {
                return false;
            }
        } else {

            $return = "error";
        }
        return $return;
    }

    // LISTADO DE LA TABLA
    public function selectUsuarios()
    {
        $whereAdmin = "";
        if ($_SESSION['idUser'] != 1) {
            $whereAdmin = " and u.ideusuario != 1 ";
        }
        $sql = "SELECT u.ideusuario,u.identificacion,u.nombres,u.apellidos,u.celular,u.correo,u.rolid,u.status,r.idrol,r.nombrerol   
                FROM tbl_usuarios u 
                INNER JOIN rol r
                ON u.rolid = r.idrol " . $whereAdmin;

        // WHERE u.status != 0 ".$whereAdmin;
        $request = $this->select_all($sql);
        return $request;
    }

    /// buscar un usuario
    public function selectUsuario(int $ideusuario)
    {
        $this->intIdeUsuario = $ideusuario;
        $sql = "SELECT u.ideusuario,u.identificacion,u.nombres,u.apellidos,u.celular,u.correo,u.rolid,u.status,r.idrol,r.nombrerol
                FROM tbl_usuarios u
                INNER JOIN rol r
                ON u.rolid = r.idrol
                WHERE u.ideusuario = $this->intIdeUsuario";
        $request = $this->select($sql);

        // var_dump($request);

        return $request;
    }

    public function updateUsuario(
        int $ideusuario,
        string $identificacion,
        string $nombres,
        string $apellidos,
        string $celular,
        string $correo,
        int $rol,
        int $status,
        string $contrasena
    ) {

        // 🔹 Guardar valores en propiedades
        $this->intIdeUsuario = $ideusuario;
        $this->strIdentificacionUsuario = $identificacion;
        $this->strNombresUsuario = $nombres;
        $this->strApellidosUsuario = $apellidos;
        $this->strCelularUsuario = $celular;
        $this->strCorreoUsuario = $correo;
        $this->strRolUsuario = $rol;
        $this->strStatus = $status;
        $this->strPassword = $contrasena;

        // 🔹 Validación de duplicados
        $sql = "SELECT * FROM tbl_usuarios 
            WHERE identificacion = '{$this->strIdentificacionUsuario}' 
            AND ideusuario != {$this->intIdeUsuario}";

        $request = $this->select_all($sql);

        // Si no existe duplicado -> actualizar
        if (empty($request)) {

            $sql = "UPDATE tbl_usuarios 
                SET identificacion=?, nombres=?, apellidos=?, celular=?, correo=?, password=?, rolid=?, status=? 
                WHERE ideusuario = {$this->intIdeUsuario}";

            $arrData = array(
                $this->strIdentificacionUsuario,
                $this->strNombresUsuario,
                $this->strApellidosUsuario,
                $this->strCelularUsuario,
                $this->strCorreoUsuario,
                $this->strPassword,
                $this->strRolUsuario,
                $this->strStatus
            );

            $update = $this->update($sql, $arrData);

            if ($update) {
                return "editado";   // ← IMPORTANTE
            } else {
                return false;
            }
        } else {
            return "exist";         // ← Dato duplicado
        }
    }


    //eliminar usuario
    public function deleteUsuario(int $intIdeUsuario)
    {
        $this->intIdeUsuario = intval($intIdeUsuario); // seguridad

        // Consulta DELETE
        $sql = "DELETE FROM tbl_usuarios WHERE ideusuario = $this->intIdeUsuario";

        // Ejecutamos la consulta
        $request = $this->delete($sql, []); // ⚡ dependiendo de tu clase, pasa array vacío si exige 2 parámetros

        return $request;
    }


    public function selectRoles()
    {
        $sql = "SELECT idrol, nombrerol, descripcion, status
            FROM rol
            WHERE status != 0
            AND idrol != 1";

        $request = $this->select_all($sql);
        return $request;
    }
}
