<?php

class connection{
    private static $host = "localhost";
    private static $nombreBD = "tienda";
    private static $usuario = "root";
    private static $password = "";

    private static $conexion;

    public static function getConexion()
    {

        try {
            self::$conexion = new PDO(
                "mysql:host=" . self::$host .
                    ";dbname=" . self::$nombreBD,
                    self::$usuario,
                    self::$password
            );
        } catch (PDOException $e) {
            print json_encode($e->getMessage());
            die();
        }

        return self::$conexion;
    }

    public static function desconectar()
    {        
        self::$conexion = null;
    }
}
