<?php
class detalles_ventas{
    public $id_venta;
    public $id_producto;
    public $cantidad;
    

    public function __construct($id_venta, $id_producto,$cantidad){
        $this->id_venta = $id_venta;
        $this->id_producto = $id_producto;
        $this->cantidad = $cantidad;      
    }
}