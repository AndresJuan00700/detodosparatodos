<?php
class compra{
    public $id;
    public $id_admin;
    public $cod_proveedor;
    public $fecha;
    public $total;

    public function __construct($id, $id_admin, $cod_proveedor, $fecha, $total){
        $this->id=$id;
        $this->id_admin=$id_admin;
        $this->cod_proveedor=$cod_proveedor;
        $this->fecha=$fecha;
        $this->total=$total;
    }
}