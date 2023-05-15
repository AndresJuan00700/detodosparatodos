<?php

class empleado{
    public $id;
    public $perfil_id;
    public $nombres;
    public $apellidos;
    public $telefono;

    public function __construct($id, $perfil_id, $nombres, $apellidos, $telefono){
        $this->id=$id;
        $this->perfil_id=$perfil_id;
        $this->nombres=$nombres;
        $this->apellidos=$apellidos;
        $this->telefono=$telefono;
    }

    public function __construct1($id){
        $this->id=$id;
    }
}

?>