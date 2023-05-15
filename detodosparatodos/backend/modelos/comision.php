<?php
class comision{
    public $codigo;
    public $volumen_ventas;
    public $porcentaje;
    public $year;

    public function __construct($codigo, $volumen_ventas, $porcentaje, $year){
        $this->codigo=$codigo;
        $this->volumen_ventas=$volumen_ventas;
        $this->porcentaje=$porcentaje;
        $this->year=$year;
    }

}