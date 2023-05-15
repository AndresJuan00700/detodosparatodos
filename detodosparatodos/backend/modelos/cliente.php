<?php

class cliente{
    public $id;
    public $id_empleado;
    public $nombres;
    public $apellidos;

    public function __construct($id, $id_empleado, $nombres, $apellidos){
        $this->id=$id;
        $this->id_empleado=$id_empleado;
        $this->nombres=$nombres;
        $this->apellidos=$apellidos;        
    }

    public function __construct1($id){
        $this->id=$id;
    }
}

?>