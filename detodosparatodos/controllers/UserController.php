<?php

class UserController{
    public function index(){
        echo '<h1>Controlador '.__CLASS__.' -> '.__METHOD__.'</h1>';
    }

    public function register(){
        require_once './views/Users/register.php';
    }

    public function save(){
        if($_SERVER['REQUEST_METHOD']=="POST"){            
            require_once './models/User.php';

            if(!isset($_SESSION)){
                session_start();
            }

            $id = !empty($_POST['id']) ? trim($_POST['id']) : null;
            $nombres = !empty($_POST['nombres']) ? trim($_POST['nombres']) : null;
            $apellidos = !empty($_POST['apellidos']) ? trim($_POST['apellidos']) : null;
            $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
            $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;            
            $image = !empty($_FILES['image']['tmp_name']) ? trim($_FILES['image']['tmp_name']) : null;  
            $numero = !empty($_POST['numero']) ? trim($_POST['numero']) : null;                      
            $rol = "user";

            $errores = array();

            //Validaciones para los datos que no pueden quedar NULL
            //La imagen y el telefono pueden quedar NULL

            if(is_null($id)){
                $errores['id']="El id no puede quedar vacio";                
            }

            if(is_null($nombres)){
                $errores['nombres'] = "El nombre no puede quedar vacio";
            }

            if(is_null($apellidos)){
                $errores['apellidos'] = "El apellido no puede quedar vacio";
            }

            if(is_null($email)){
                $errores['email'] = "El email no puede quedar vacio";
            }

            if(is_null($pass)){
                $errores['password'] = "La contraseña no puede quedar vacia";
            }

            //Validaciones para el tipo de dato
            if(!is_numeric($id)){
                $errores['idType'] = "El id debe ser numerico";
            }

            $resultNombre = $this->ctype__alpha($nombres, " ", "El nombre contiene solo letras");

            if(!is_null($resultNombre)){
                $errores['nameType'] = $resultNombre;
            }

            $resultApellido = $this->ctype__alpha($apellidos, " ", "El apellido contiene solo letras");

            if(!is_null($resultApellido)){
                $errores['lastNameType'] = $resultApellido;
            }

            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $errores['emailType'] = "El formato del email no es correcto";
            }
            
            if(count($errores)==0){                             
                $user = new User();
                
                //Los tipos de datos ya son validos, pero falta verificar si el email ya existe
                $verificacion = $this->verify_user($user->getDb(), $email);

                if($verificacion){
                    $pass_verify = password_hash($pass, PASSWORD_BCRYPT, ['cost'=>4]);

                    $user->setId($id);
                    $user->setNombre($nombres);
                    $user->setApellidos($apellidos);
                    $user->setEmail($email);
                    $user->setPassword($pass_verify);
                    $user->setRol($rol);
                    $user->setImage($image);
                    $user->setNumero($numero);                                  

                    $save = $user->save();

                    if($save){
                        $_SESSION['register']="Complete";
                    }else{
                        $_SESSION['register']="Failed";
                    }
                    
                }else{
                    $_SESSION['register']="Failed";
                }
            }else{
                $_SESSION['register']="Failed";
            }
            
        }else{
            $_SESSION['register']="Failed";
        }

        header("Location: ".base_url."user/register");
        
    }

    private function verify_user($db, $email){
        $sql = "SELECT COUNT(1) Cantidad FROM usuarios WHERE email=:correo";

        $sentencia = $db->prepare($sql);
        $sentencia->bindParam(':correo', $email, PDO::PARAM_STR);
        $sentencia->execute();
        $result = $sentencia->fetchAll(PDO::FETCH_ASSOC);   

        $datos = $result[0];

        if($datos['Cantidad']==0){
            return true;
        }else{
            return false;
        }
                
    }

    private function ctype__alpha($array, $separador, $mensaje){
        $result = null;
        
        $arrayVar = explode($separador, $array);

        foreach($arrayVar as $variable){ 

            if(!ctype_alpha($variable)){
                $result=$mensaje;
                break;
            }

        }

        return $result;
    }
}