<?php
class proveedor{
    public $codigo;
    public $nombre;
    public $telefono;

    public function __construct($codigo, $nombre, $telefono){
        $this->codigo=$codigo;
        $this->nombre=$nombre;
        $this->telefono=$telefono;
    }
}