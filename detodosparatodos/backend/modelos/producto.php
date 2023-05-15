<?php
class producto{
    public $id;
    public $id_familia;
    public $imagen;
    public $nombre;
    public $precioCompra;
    public $precioVenta;
    public $stock;
    public $descripcion;

    public function __construct($id, $id_familia, $imagen, $nombre, $precioCompra, $precioVenta, $stock, $descripcion){
        $this->id=$id;
        $this->id_familia=$id_familia;
        $this->imagen=$imagen;
        $this->nombre=$nombre;
        $this->precioCompra=$precioCompra;
        $this->precioVenta=$precioVenta;
        $this->stock=$stock;
        $this->descripcion=$descripcion;
    }

}