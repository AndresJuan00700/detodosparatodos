<?php

require_once './autoload.php';
require_once './config/connection.php';
require_once './config/parameters.php';
require_once './views/layouts/header.php';
require_once './views/layouts/sidebar.php';
require_once './vendor/autoload.php';

function show_error(){
    require_once './controllers/ErrorController.php';
    $error = new ErrorController();
    $error->index();
}

$get_controller = !empty($_GET['controller']) ? trim($_GET['controller']) : null;

//Si existe el controlador
if(!is_null($get_controller)){    
    $controller_name = $get_controller.'Controller';
}elseif(!isset($_GET['controller']) && !isset($_GET['action'])){        
    //header("location: error.php?error=page");
    $controller_name = controller_default;
}

//Si existe el la clase de ese controlador
if(class_exists($controller_name)){        

    $controller = new $controller_name();

    $get_action = !empty($_GET['action']) ? trim($_GET['action']) : null;

    //Si existe el metodo
    if(!is_null($get_action) && method_exists($controller_name, $get_action)){
        $action = $get_action;
        $controller->$action();        
    }else if(!isset($_GET['controller']) && !isset($_GET['action'])){
        $action = action_default;
        $controller->$action();
    }else{                
        //require_once 'error.php';
        show_error();
    }
}else{    
    //header("location: error.php?error=class");
    show_error();
}

require_once './views/layouts/footer.php';