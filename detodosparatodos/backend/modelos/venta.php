<?php
class venta{
    public $id;
    public $id_vendedor;
    public $id_cliente;
    public $fecha;
    public $total;

    public function __construct($id, $id_vendedor, $id_cliente, $fecha, $total){
        $this->id = $id;
        $this->id_vendedor = $id_vendedor;
        $this->id_cliente = $id_cliente;
        $this->fecha = $fecha;
        $this->total = $total;
    }
}