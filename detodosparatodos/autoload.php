<?php
//De momento se ocultaran los warning
error_reporting(E_ALL ^ E_WARNING);

function controllers_autoloader($classname){
    try {
        include 'controllers/'.$classname.'.php';
    } catch (\Throwable $th) {
        //throw $th;
        die();
    }   
}

spl_autoload_register('controllers_autoloader');