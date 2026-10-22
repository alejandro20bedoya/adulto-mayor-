<?php

class Mysql extends Conexion
{
    private $conexion;
    private $strquery;
    private $arrValues;


    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->conect();

        if (!$this->conexion instanceof PDO) {
            throw new RuntimeException(
                'No se pudo establecer una conexión PDO con MySQL.'
            );
        }
    }


    // Insertar un registro
    // Insertar un registro (SOPORTA LONGBLOB)
    public function insert(string $query, array $arrValues)
    {
        try {
            $this->strquery = $query;
            $this->arrValues = $arrValues;

            $stmt = $this->conexion->prepare($this->strquery);

            foreach ($this->arrValues as $index => $value) {

                // Detectar datos binarios grandes (video)
                if (is_string($value) && strlen($value) > 100000) {
                    $stmt->bindValue($index + 1, $value, PDO::PARAM_LOB);
                } else {
                    $stmt->bindValue($index + 1, $value, PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            return $this->conexion->lastInsertId();
        } catch (PDOException $e) {
            error_log('ERROR INSERT MYSQL: ' . $e->getMessage());
            return 0;
        }
    }


    // Busca un registro
    public function select(string $query, array $arrValues = [])
    {
        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $result = $this->conexion->prepare($this->strquery);
        $result->execute($this->arrValues);
        $data = $result->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    // Devuelve todos los registros
    public function select_all(string $query, array $arrValues = [])
    {
        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $result = $this->conexion->prepare($this->strquery);
        $result->execute($this->arrValues);
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    // Actualiza registros
    public function update(string $query, array $arrValues)
    {
        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $update = $this->conexion->prepare($this->strquery);
        $resExecute = $update->execute($this->arrValues);
        return $resExecute;
    }

    // Eliminar un registro
    public function delete(string $query, array $arrValues = [])
    {
        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $result = $this->conexion->prepare($this->strquery);
        $del = $result->execute($this->arrValues);
        return $del;
    }
}
