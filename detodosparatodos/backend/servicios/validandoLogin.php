<?php
if($_SERVER['REQUEST_METHOD']=='POST'){ 
    if(!isset($_SESSION)){
        session_start();
    }
    $correo = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $pass = !empty($_POST['pass']) ? trim($_POST['pass']) : null;

    $errores = array();

    if(is_null($correo)){
        $errores['correo']="No puede dejar vacio su correo";
    }

    if(is_null($pass)){
        $errores['pass']="No puede dejar vacio su correo";
    }
    
    if(count($errores)==0){        
        require_once './listando.php';
        $pass_original=obtenerPassword("correo","'".$correo."'");  
        $verificacion=false;
        //En caso de que se encuentre algo y no venga vacio
        if(!empty($pass_original)){
            $verificacion = password_verify($pass, $pass_original[0]['password']);
        }

        if($verificacion){
            //Si entra aquí es porque "todo" fue bien
            $id=buscando_id($correo);            
            $usuario = obtener_empleado_registrado($id[0]['id_empleado']);            

            $_SESSION['usuario_logueado']=$usuario;

            header("location: ../../vistas/home.php");
            

        }else{
            $_SESSION['errores']['general']="Su correo o contraseña no son correctos";    
        }
    }else{
        $_SESSION['errores']=$errores;    
    }

    if(!empty($_SESSION['errores'])){
        if(count($_SESSION['errores'])>0){        
            header("location: ../../vistas/login.php");
        }
    }        
}