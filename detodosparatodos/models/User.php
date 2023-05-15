<?php

class User{
    private $id;
    private $nombres;
    private $apellidos;
    private $email;
    private $password;
    private $rol;
    private $image;
    private $numero;
    private static $db;

    public function __construct()
    {
        self::$db=connection::getConexion();
    }

    public function getId(){
        return $this->id;
    }

    public function setId(String $id){
        $this->id=$id;
    }

    public function getNombre(){
        return $this->nombres;
    }

    public function setNombre(String $nombres){
        $this->nombres=ucwords($nombres);
    }

    public function getApellidos(){
        return $this->apellidos;
    }

    public function setApellidos(String $apellidos){
        $this->apellidos=ucwords($apellidos);
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail(String $email){
        $this->email=$email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword(String $password){
        $this->password=$password;
    }

    public function getRol(){
        return $this->rol;
    }

    public function setRol($rol){
        $this->rol=ucfirst($rol);
    }

    public function getImage(){
        return $this->image;
    }

    public function setImage($image){
        $this->image=$image;
    }

    public function getNumero(){
        return $this->numero;
    }

    public function setNumero($numero){
        $this->numero=$numero;
    }

    public function getDb(){
        return self::$db;
    }

    public function save(){
        $operacion = 0;
        $id = $this->getId();
        $nombres = $this->getNombre();
        $apellidos = $this->getApellidos();
        $email = $this->getEmail();
        $pass = $this->getPassword();
        $rol = $this->getRol();
        $image = $this->getImage();
        $num = $this->getNumero();

        $sql = "CALL gestionar_usuario(:operacion, :id, :nombres, :apellidos, :email, :password, :rol, :imagen, :numero)";

        $sentencia = self::$db->prepare($sql);        
                
        $sentencia->bindParam(":operacion", $operacion, PDO::PARAM_INT);
        $sentencia->bindParam(":id", $id, PDO::PARAM_STR);
        $sentencia->bindParam(":nombres", $nombres, PDO::PARAM_STR);
        $sentencia->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
        $sentencia->bindParam(":email", $email, PDO::PARAM_STR);
        $sentencia->bindParam(":password", $pass, PDO::PARAM_STR);
        $sentencia->bindParam(":rol", $rol, PDO::PARAM_STR);
        $sentencia->bindParam(":imagen", $image, PDO::PARAM_LOB);
        $sentencia->bindParam(":numero", $num, PDO::PARAM_STR);

        $result = $sentencia->execute();

        return $result;
        
        
    }

}