<?php
class detalle_compra{
    public $id_compra;
    public $id_producto;
    public $cantidad;

    public function __construct($id_compra, $id_producto, $cantidad){
        $this->id_compra=$id_compra;
        $this->id_producto=$id_producto;
        $this->cantidad=$cantidad;
    }
}