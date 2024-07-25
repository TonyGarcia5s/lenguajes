<?php
require_once '../config/Conexion.php';

class Datos extends Conexion
{
        protected static $cnx;
    private $id = null;
    private $inicio = null;
    private $fin = null;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getInicio()
    {
        return $this->inicio;
    }
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    }
    public function getFin()
    {
        return $this->fin;
    }
    public function setFin($fin)
    {
        $this->fin = $fin;
    }
##metodoss
    public static function getConexion()
    {
        self::$cnx = Conexion::conectar();
    }

    public static function desconectar()
    {
        self::$cnx = null;
    }

    public function listarTodosDb()
    {
        $query = "SELECT * FROM curso_escolar";
        $arr = array();
        try {
            self::getConexion();
            $resultado = self::$cnx->prepare($query);
            $resultado->execute();
            self::desconectar();
            foreach ($resultado->fetchAll() as $encontrado) {
                $user = new Datos();
                $user->setId($encontrado['id']);
                $user->setInicio($encontrado['anyo_inicio']);
                $user->setFin($encontrado['anyo_fin']);
                $arr[] = $user;
            }
            return $arr;
        } catch (PDOException $Exception) {
            self::desconectar();
            $error = "Error " . $Exception->getCode() . ": " . $Exception->getMessage();;
            return json_encode($error);
        }
    }

    public function verificarExistenciaDb()
    {
        $query = "SELECT * FROM curso_escolar where inicio=:incio";
        try {
            self::getConexion();
            $resultado = self::$cnx->prepare($query);
            $inicio = $this->getInicio();
            $resultado->bindParam(":inicio", $inicio, PDO::PARAM_STR);
            $resultado->execute();
            self::desconectar();
            $encontrado = false;
            foreach ($resultado->fetchAll() as $reg) {
                $encontrado = true;
            }
            return $encontrado;
        } catch (PDOException $Exception) {
            self::desconectar();
            $error = "Error " . $Exception->getCode() . ": " . $Exception->getMessage();
            return $error;
        }
    }

    public function guardarEnDb()
    {
        $query = "INSERT INTO `curso_escolar`(`inicio`,`fin`) VALUES (:inicio,:fin,now())";
        try {
            self::getConexion();
            $inicio = $this->getInicio();
            $fin = $this->getFin();

            $resultado = self::$cnx->prepare($query);
            $resultado->bindParam(":inicio", $inicio, PDO::PARAM_STR);
            $resultado->bindParam(":fin", $fin, PDO::PARAM_STR);
            $resultado->execute();
            self::desconectar();
        } catch (PDOException $Exception) {
            self::desconectar();
            $error = "Error " . $Exception->getCode() . ": " . $Exception->getMessage();;
            return json_encode($error);
        }
    }

    public static function mostrar($correo)
    {
        $query = "SELECT * FROM curso_escolar where inicio=:id";
        $id = $correo;
        try {
            self::getConexion();
            $resultado = self::$cnx->prepare($query);
            $resultado->bindParam(":id", $id, PDO::PARAM_STR);
            $resultado->execute();
            self::desconectar();
            return $resultado->fetch();
        } catch (PDOException $Exception) {
            self::desconectar();
            $error = "Error " . $Exception->getCode() . ": " . $Exception->getMessage();
            return $error;
        }
    }

}
