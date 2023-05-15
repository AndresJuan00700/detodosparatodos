<?php

class porcentaje_anual{    
    public $valor;
    public $year;
    public $hora_registro;

    public function __construct($valor, $year, $hora_registro){        
        $this->valor=$valor;
        $this->year=$year;  
        $this->hora_registro=$hora_registro;  
    }

    public function __construct1($id){
        $this->id=$id;
    }
}

?>