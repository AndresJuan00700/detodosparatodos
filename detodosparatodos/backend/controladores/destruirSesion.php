<?php

session_start();

function destruir(){
    header("location: ../index.php");
    session_destroy();
    die();
}

?>