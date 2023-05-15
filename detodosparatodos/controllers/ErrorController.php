<?php
require_once './config/parameters.php';
class ErrorController{
    public function index(){        
        echo ' <div id="error">
                    <img src="'.base_url.'assets/img/camiseta.png" alt="Logo camiseta">
                    <h2>No se encontró lo que solicitó</h2>
                </div>  ';
    }
}