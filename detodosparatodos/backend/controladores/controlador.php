<?php
require_once '../componentes/conectar.php';
class controlador{
    private $servidor="localhost";
    private $nombreBD="detodosparatodos";
    private $usuarioBD="root";
    private $passwordBD="";
    private $tabla;
    private $conexion;

    public function __construct($tabla){                    
        $conexionMySQL = new conectar($this->servidor, $this->nombreBD, $this->usuarioBD, $this->passwordBD);   
        $this->tabla=$tabla;         
        $this->conexion= $conexionMySQL->getConexion();                           
    }

    public function guardar($procedimiento, $datos){
        $sql="CALL $procedimiento(0";
        //Se necesita recorrer el array de datos
        /*
            Se completa la sentencia para llamar al
            procedimiento almacenado
        */
        foreach($datos as $dato){
            if(is_null($dato)){
                $sql.=", null";
            }else if(is_string($dato)){
                $sql.=", '$dato'";
            }else{
                $sql.=", $dato";
            }
            
        }
        $sql.=");";        

        $sentencia = $this->conexion->prepare($sql);
        
        $resultado = $sentencia->execute();        
        
        if($resultado){
            return true;           
        }else{
            var_dump($sentencia->errorinfo());
            return false;
        }        
        
    }

    public function eliminar($procedimiento, $datos){
        $sql="CALL $procedimiento(1";
        //Se necesita recorrer el array de datos
        /*
            Se completa la sentencia para llamar al
            procedimiento almacenado
        */
        foreach($datos as $dato){
            if(is_null($dato)){
                $sql.=", null";
            }else if(is_string($dato)){
                $sql.=", '$dato'";
            }else{
                $sql.=", $dato";
            }
            
        }
        $sql.=");";

        $sentencia = $this->conexion->prepare($sql);
        $resultado = $sentencia->execute();        
        
        if($resultado){
            return true;           
        }else{
            var_dump($sentencia->errorinfo());
            return false;
        } 
    }

    public function listar(){
        $sentencia = "SELECT * FROM $this->tabla";
        $sentencia = $this->conexion->prepare($sentencia);
        $sentencia->execute();

        $info = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        return $info;
    }
}