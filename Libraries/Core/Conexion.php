
<?php

class Conexion
{
    private $conect;

    public function __construct()
    {
        $connectionString = "mysql:host=" . DB_HOST
            . ";port=" . DB_PORT
            . ";dbname=" . DB_NAME
            . ";charset=" . DB_CHARSET;
        try {
            $this->conect = new PDO(
                $connectionString,
                DB_USER,
                DB_PASSWORD
            );

            $this->conect->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->conect->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
        } catch (PDOException $e) {
            error_log('Error de conexión MySQL: ' . $e->getMessage());

            throw new RuntimeException(
                'No fue posible conectar con la base de datos.',
                0,
                $e
            );
        }
    }

    public function conect()
    {
        return $this->conect;
    }
}
