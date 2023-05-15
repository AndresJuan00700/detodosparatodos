<?php

class telefono{    
    public $id_cliente;    
    public $numero;

    public function __construct($id_cliente, $numero){        
        $this->id_cliente=$id_cliente;        
        $this->numero=$numero;        
    }

    public function __construct1($id){
        $this->id=$id;
    }
}

?>