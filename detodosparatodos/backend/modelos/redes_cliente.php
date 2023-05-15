<?php

class redes_cliente{
    public $codigo_red;
    public $id_cliente;
    public $nombre_usuario;

    public function __construct($codigo_red, $id_cliente, $nombre_usuario){
        $this->codigo_red=$codigo_red;
        $this->id_cliente=$id_cliente;
        $this->nombre_usuario=$nombre_usuario;  
    }

    public function __construct1($id){
        $this->id=$id;
    }
}

?>