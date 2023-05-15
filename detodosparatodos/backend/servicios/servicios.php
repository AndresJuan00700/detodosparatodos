<?php
    
    if($_SERVER['REQUEST_METHOD']=='POST'){        
        require_once '../controladores/controlador.php';  
        require_once './listando.php';                
        
        $controlador = trim($_POST['controlador']);
        $operacion = trim($_POST['operacion']);

        if(!isset($_SESSION)){
            session_start();
        }                   

        if($controlador=="empleado"){
            require_once '../modelos/empleado.php';
            require_once '../modelos/usuario.php';
            //En caso de que quiera actualizar o para verificar un detalle al ingresar un empleado nuevo            

            //Recupero los datos que se hayan mandado por POST, 
            //y se pone la primera letra en mayuscula

            //Con quote() se escapan los datos por si se ingresan caracteres especiales
            //para evitar errores en la base de datos

            //Datos para la tabla empleados
            $id = !empty($_POST['id']) ? trim($_POST['id']) : null ;
            $perfil=  !empty($_POST['perfil']) ? trim($_POST['perfil']) : null ;
            $nombres = !empty($_POST['nombres']) ? ucwords(trim($_POST['nombres'])) : null ; 
            $apellidos = !empty($_POST['apellidos']) ? ucwords(trim($_POST['apellidos'])) : null ; 
            $telefono = !empty($_POST['telefono']) ? trim($_POST['telefono']) : null ; 
            
            //Datos para la tabla usuarios
            $email = !empty($_POST['email']) ? trim($_POST['email']) : null ; 
            $password = !empty($_POST['pass']) ? trim($_POST['pass']) : null ;                                    

            //Se crea una variable de session para los mensajes resultantes
            $errores = array();            
            
            //Se necesitan validar las variables    
            if(is_null($id)){
                $errores['id']="El id no puede quedar vacio";
            }else if(!is_numeric($id)){
                $errores['id']="El id del empleado debe ser un numero";
            }else{
                /*
                Cuando se pasa por el formulario de actualizacion, se pide la contraseña
                para verificar si es correcta y se puede dar el paso.

                Aqui se trabaja con el input validar
                */
                $verificacion=true;
                
                if(isset($_POST['validar'])){                    
                    $password_traida=obtenerPassword('id_empleado',$id)[0]['password'];
                    $verificando = password_verify($password,$password_traida);
                    if($verificando==false){
                        $verificacion=false;
                        $errores['verificacion']="La contraseña ingresada para poder actualizar es incorrecta";
                    }
                }
            }

            if(is_null($perfil)&&$operacion==0){
                $errores['perfil']="El perfil no puede quedar vacio";
            }

            if((is_numeric($nombres) || preg_match("/[0-9]/", $nombres))&&$operacion==0){                
                $errores['nombre']="El nombre no admite numeros";
            }  

            /*
                ctype_alpha es una funcion que sólo admite letras, no admite numeros,
                ni espacios, ni caracteres especiales, está aqui para que no se rompa
                la sentencia SQL si pasa por ejemplo una comilla doble o una comilla simple,
                con mysqli seria algo similar a la funcion mysqli_real_escape()
            */

            /*
                Como los nombres vendran separados por espacio, entonces se convertira un momento
                en un array para verificar que cada cadena de caracter esté conformada solo por letras
            */

            //Se convierte $nombres en un array
            $array_nombres = explode(" ", $nombres);

            for($i=0; $i<count($array_nombres); $i++){
                
                if(!ctype_alpha($array_nombres[$i])&&$operacion==0){                
                    $errores['nombreerror2']="El nombre solo debe contener letras";
                }
                
            }                         
            
            if((is_numeric($apellidos) || preg_match("/[0-9]/", $apellidos))&&$operacion==0){                
                $errores['apellidos']="El apellido no admite numeros";
            } 

            /**
             * El mismo principio que se aplicó para nombres, se aplicaria
             * para apellidos
             */
            
             //Se convierte $nombres en un array
            $array_apellidos = explode(" ", $apellidos);

            for($i=0; $i<count($array_apellidos); $i++){
                
                if(!ctype_alpha($array_apellidos[$i])&&$operacion==0){                
                    $errores['apellidoserror2']="El apellido solo debe contener letras";
                }
                
            }             

            if(!is_numeric($telefono) && !is_null($telefono) && $operacion==0){                
                $errores['tel']="El telefono es solo numerico";
            } 

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)&&$operacion==0){
                $errores['email']="Ingrese un email correcto";
            }

            if(is_null($password)&&$operacion==0){
                $errores['password']="La contraseña no puede quedar vacia";
            }else if(strlen($password)<6&&$operacion==0){                
                $errores['password']="La longitud minima de la contraseña es de 6";
            }else if(strlen($password)>16&&$operacion==0){                
                $errores['password']="La longitud maxima de la contraseña es de 16";
            }else{
                //Se encripta la contraseña si no hay problemas con la original
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost'=>4]) ; 
            }  
            
            if(is_null($perfil)&&$operacion==0){
                $errores['perfil']="El perfil no puede quedar vacio";
            }

            //Se necesita verificar que no haya otro usuario con el correo ingresado para un nuevo usuario            
            $pase = verificando_identidad($id, $email);
            
            if($pase==false){
                $errores['pase']="El correo ingresado es usado por otro usuario";
            }   
            
            //Si no hay errores
            if(count($errores)==0 && $verificacion==true){                
                
                $empleado = new empleado($id, $perfil, $nombres, $apellidos, $telefono);
                $usuario = new usuario($id, $email, $password);
                
                //Para controlar la tabla empleados
                $controlador_empleado=new controlador("empleados");
                //Para controlar la tabla usuarios
                $controlador_usuario=new controlador("usuarios");            
                                
                if(is_numeric($operacion) && $operacion==0){  
                    //Se manda al empleado              
                    $resultado_empleado = $controlador_empleado->guardar("gestionar_empleado",$empleado);
                    if($resultado_empleado==true){
                        //Se manda al usuario de ese empleado
                        $resultado_usuario = $controlador_usuario->guardar("gestionar_usuario",$usuario);                        
                        if($resultado_empleado==true){

                            //En caso de que se actualize aquel con la sesion activa

                            if($empleado->id==$_SESSION['usuario_logueado'][0]['id']){
                                $_SESSION['usuario_logueado'][0]['nombres']=$empleado->nombres;
                                $_SESSION['usuario_logueado'][0]['apellidos']=$empleado->apellidos;
                            }

                            $_SESSION['completado']="Se ha registrado exitosamente";                             
                        }else{
                            $errores['errores']['general']="No se guardó el usuario del empleado";    
                        }
                    }else{
                        $errores['errores']['general']="No se guardó el empleado";
                    }
                }else if(is_numeric($operacion) && $operacion==1){                    
                    //Primero se borra su usuario
                    $cuenta_borrada=$controlador_usuario->eliminar("gestionar_usuario",$usuario);
                    if($cuenta_borrada){
                        //Si se borró la cuenta, se borra a su usuario
                        $usuario_borrado = $controlador_empleado->eliminar("gestionar_empleado",$empleado);
                        if($usuario_borrado){                            
                            $_SESSION['completado']="Se ha eliminado exitosamente";                             
                        }else{
                            $errores["errores"]["borrado"]="El empleado no se eliminó";   
                        }
                    }else{
                        $errores["errores"]["borrado"]="La cuenta no se eliminó";
                    }
                }
                
            }else{                
                $_SESSION['errores']=$errores;            
            }    
            
            //Al terminar todo, regresa
            /*
                Sé que regresa a manejar_empleado.php porque estoy en
                el condicional de empleado
            */
            header('location: ../../vistas/manejar_empleados.php');
            
        }else if($controlador=="cliente"){
            //Entra aqui si la solicitud viene de manejar_cliente
            
            //var_dump($_POST);

            $id = !empty($_POST['id']) ? trim($_POST['id']) : null ;
            $id_empleado = !empty($_POST['trabajador']) ? trim($_POST['trabajador']) : null ;
            $nombres = !empty($_POST['nombres']) ? ucwords(trim($_POST['nombres'])) : null ;
            $apellidos = !empty($_POST['apellidos']) ? ucwords(trim($_POST['apellidos'])) : null ;
            $numeros = array();
            $cuentas = array();

            //Variable de errores
            $errores = array();            

            //Capturar errores en el id
            if(is_null($id)){
                $errores['id']="El id no puede quedar vacio";
            }else if(!is_numeric($id)){
                $errores['id']="El id del empleado debe ser un numero";
            }

            //Capturar errores en el id_empleado
            if(is_null($id)){
                $errores['id_empleado']="El id no puede quedar vacio";
            }else if(!is_numeric($id)){
                $errores['id_empleado']="El id del empleado debe ser un numero";
            }

            //Capturar errores en el nombre
            if(is_numeric($nombres) || preg_match("/[0-9]/", $nombres)){                
                $errores['nombre']="El nombre no admite numeros";
            } 

            /*
                ctype_alpha es una funcion que sólo admite letras, no admite numeros,
                ni espacios, ni caracteres especiales, está aqui para que no se rompa
                la sentencia SQL si pasa por ejemplo una comilla doble o una comilla simple,
                con mysqli seria algo similar a la funcion mysqli_real_escape()
            */

            /*
                Como los nombres vendran separados por espacio, entonces se convertira un momento
                en un array para verificar que cada cadena de caracter esté conformada solo por letras
            */

            //Se convierte $nombres en un array
            $array_nombres = explode(" ", $nombres);

            for($i=0; $i<count($array_nombres); $i++){
                
                if(!ctype_alpha($array_nombres[$i])&&$operacion==0){                
                    $errores['nombreerror2']="El nombre solo debe contener letras";
                }
                
            } 

            //Capturar errores en el apellido
            if(is_numeric($apellidos) || preg_match("/[0-9]/", $apellidos)){                
                $errores['apellidos']="El apellido no admite numeros";
            } 
            
            //Se aplica el mismo principio que en $nombres
            $array_apellidos = explode(" ", $apellidos);

            for($i=0; $i<count($array_apellidos); $i++){
                
                if(!ctype_alpha($array_apellidos[$i])&&$operacion==0){                
                    $errores['apellidoserror2']="El apellido solo debe contener letras";
                }
                
            } 

            if(count($errores)==0){
                require_once '../modelos/cliente.php';
                //Creo a mi cliente
                $cliente = new cliente($id, $id_empleado, $nombres, $apellidos);
                
                $controlador_cliente=new controlador("clientes");

                if(is_numeric($operacion) && $operacion==0){
                    $resultado_cliente = $controlador_cliente->guardar("gestionar_cliente",$cliente);
                    if($resultado_cliente){
                        $_SESSION['completado']="El cliente se registró exitosamente";
                        if(!empty($_POST['numero1'])){
                            require_once '../modelos/telefono.php';
                            $j=1;
                            //Busco esos numeros ingresados
                            for($i=0; $i<count($_POST); $i++){
                                if(!empty($_POST['numero'.$j.''])){                                                                        
                                    $numeros['numero'.$j.'']=$_POST['numero'.$j.''];
                                    $j++;
                                }
                            }                            

                            //Cada numero guardado en la variable numeros 
                            //debe ser guardado en la base de datos
                            $controlador_telefono=new controlador("telefonos");
                            $_SESSION['numeros']="";
                            $j=1;
                            foreach($numeros as $numero){                                
                                //Mando el numero al objeto
                                $telefono = new telefono($id, $numero);
                                $resultado_telefono = $controlador_telefono->guardar("gestionar_telefono", $telefono);
                                if($resultado_telefono){                                    
                                    $_SESSION['numeros'].= "<p>El numero ".$j." se registró exitosamente</p>";
                                    $j++;                                    
                                }else{
                                    $errores['errores']['general']="No se guardó el numero ".$numero;
                                }                                
                            }                            
                        }

                        //var_dump($_POST);
                        $whatsapp=!empty($_POST['whatsapp']) ? trim($_POST['whatsapp']) : null ;
                        $instagram=!empty($_POST['instagram']) ? trim($_POST['instagram']) : null ;
                        $telegram=!empty($_POST['telegram']) ? trim($_POST['telegram']) : null ;
                        $twitter=!empty($_POST['twitter']) ? trim($_POST['twitter']) : null ;
                        
                        
                        if(!empty($whatsapp)||!empty($instagram)||!empty($telegram)||!empty($twitter)){
                            require_once '../modelos/redes_cliente.php';
                            $controlador_red_cliente=new controlador("redes_usuarios");                                
                        }                            
                        if(!empty($whatsapp)){
                            $codigo = obtener_redes("Whatsapp");
                            if(!empty($codigo)){
                                $codigo = $codigo[0]['codigo']; 
                                $red_cliente = new redes_cliente($codigo, $id, $whatsapp);
                                $resultado_red_cliente = $controlador_red_cliente->guardar("gestionar_redusuario",$red_cliente);

                                if($resultado_red_cliente){
                                    $_SESSION['whatsapp']= "Whatsapp se registró exitosamente";
                                }else{
                                    $errores['errores']['whatsapp']="No se guardó whatsapp";
                                }
                            }                                
                        }
                        if(!empty($instagram)){
                            $codigo = obtener_redes("Instagram");
                            if(!empty($codigo)){
                                $codigo = $codigo[0]['codigo']; 
                                $red_cliente = new redes_cliente($codigo, $id, $instagram);  
                                $resultado_red_cliente = $controlador_red_cliente->guardar("gestionar_redusuario",$red_cliente);                                                                     

                                if($resultado_red_cliente){
                                    $_SESSION['instagram']= "Instagram se registró exitosamente";
                                }else{
                                    $errores['errores']['instagram']="No se guardó instagram";
                                }
                            } 
                        }
                        if(!empty($telegram)){
                            $codigo = obtener_redes("Telegram");
                            if(!empty($codigo)){
                                $codigo = $codigo[0]['codigo']; 
                                $red_cliente = new redes_cliente($codigo, $id, $telegram);  
                                $resultado_red_cliente = $controlador_red_cliente->guardar("gestionar_redusuario",$red_cliente);                                                                     

                                if($resultado_red_cliente){
                                    $_SESSION['telegram']= "Telegram se registró exitosamente";
                                }else{
                                    $errores['errores']['telegram']="No se guardó telegram";
                                }
                            } 
                        }
                        if(!empty($twitter)){
                            $codigo = obtener_redes("Twitter");
                            if(!empty($codigo)){
                                $codigo = $codigo[0]['codigo'];
                                $red_cliente = new redes_cliente($codigo, $id, $twitter);
                                $resultado_red_cliente = $controlador_red_cliente->guardar("gestionar_redusuario",$red_cliente);

                                if($resultado_red_cliente){
                                    $_SESSION['twitter']= "Twitter se registró exitosamente";
                                }else{
                                    $errores['errores']['twitter']="No se guardó twitter";
                                }
                            } 
                        }

                    }else{
                        $errores['errores']['general']="No se guardó el cliente";
                    }
                }
            }
            //Al estar en controlador clientes, sé que regreso a manejar_clientes.php
            header('location:../../vistas/manejar_clientes.php');            

        }else if($controlador=="porcentaje_alerta"){
            $year = !empty($_POST['year']) ? $_POST['year'] : false;
            $valor = !empty($_POST['valorNuevo']) ? $_POST['valorNuevo'] : false;
            
            //echo $year."<br>".$valor."<br>".date("Y")."<br>";

            $errores = array();

            if(is_null($year)||is_null($valor)){
                $errores['vacios']="No puede dejar valores vacios";
            }

            if($year>date("Y") && $year!="No hay registros utiles"){
                $errores['year']="No es permitido realizar este cambio, debe esperar al ".(date("Y")+1);
            }

            if(strlen($valor)<=0 || strlen($valor)>4){
                $errores['longitud']="Ingrese una longitud valida, como mucho dos decimales";
            }            

            if($valor<=0){
                $errores['valor']="Ingrese un valor valido";
            }

            if(!is_numeric($valor)){
                $errores['valor']="El valor debe ser numerico";
            }else if($valor>100){
                $errores['valor']="El valor parece estar fuera del rango [1-100]";
            }
             
            if(count($errores)==0){
                require_once '../modelos/porcentaje_anual.php';
                
                if(is_numeric($operacion) && $operacion=="0"){

                    $controlador_porcentaje = new controlador("porcentaje_anual");                      
                    $fecha = date('Y');
                    //$hora= "SELECT CURRENT_TIME()";
                    $hora = date('Y-m-d H:i:s');

                    $pase = false;
                    
                    $hora_del_ultimo_registro=obtener_ultima_hora();
                    
                    if(!empty($hora_del_ultimo_registro)){
                        $hora_del_ultimo_registro=$hora_del_ultimo_registro[0]['momento_registro'];                        
                        $momentos = explode(" ", $hora_del_ultimo_registro);
                        $año=explode("-", $momentos[0]);

                        if($_POST['year']==$año[0]){
                            /*
                                Se supone que es un valor que se ingresa
                                anualmente, pero puede haberse ingresado
                                un valor incorrecto, entonces habrá
                                un lapso de tiempo para que lo corrija 
                                var_dump($momentos[0]);
                                die();
                            */
                            $pase=false;                            
                            
                            $hora_del_ultimo_registro=obtener_ultima_hora();
                            
                            $inicio = new DateTime($momentos[1]);
                            $fin = new DateTime($hora);                            
                            
                            //En esta variable obtengo la diferencia entre la hora en la bd y la hora actual                            
                            $diferencia = get_object_vars($inicio->diff($fin));                              

                            //De momento, sólo se aceptaran cambios hasta 7 dias despues del ultimo guardado

                            /*
                                Si se cambia el limite, tener en cuenta que un minuto contiene 60 segundos,
                                una hora contiene 60 minutos, un dia contiene 24 horas, 
                                y un mes contiene hasta 31 dias
                            */                            
                            
                            $limite_mes=0;
                            $limite_dia=0;
                            $limite_hora=0;
                            $limite_min=4;
                            $limite_seg=60;                                                          
                            
                            $pase_mes=false;

                            if($limite_mes>0){
                                if($diferencia['m']<$limite_mes){
                                    $pase_mes=true;
                                }
                            }else if($limite_mes==0){
                                if($diferencia['m']==$limite_mes){
                                    $pase_mes=true;
                                }
                            }

                            $pase_dia=false;

                            if($diferencia['d']<=$limite_dia){
                                $pase_dia=true;
                            }

                            $pase_hora=false;

                            if($diferencia['h']<=$limite_hora){
                                $pase_hora=true;
                            }

                            $pase_min=false;

                            if($diferencia['i']<=$limite_min){
                                $pase_min=true;
                            }

                            $pase_seg=false;

                            if($diferencia['i']<=$limite_seg){
                                $pase_seg=true;
                            }

                            if($limite_seg==60){
                                $limite_min++;
                                $limite_seg=0;
                            }

                            if($limite_min==60 || $limite_min==61){
                                $limite_hora++;
                                $limite_min=0;
                            }

                            if($limite_hora==24 || $limite_hora==25){
                                $limite_dia++;
                                $limite_hora=0;
                            }

                            if($limite_dia==30 || $limite_dia==31){
                                $limite_mes++;
                                $limite_dia=0;
                            }
                            
                            $limite_total="";
                            $limite_total.= ($limite_mes!=0) ? $limite_mes." meses " : "" ;
                            $limite_total.= ($limite_dia!=0) ? $limite_dia." dias " : "" ;
                            $limite_total.= ($limite_hora!=0) ? $limite_hora." horas " : "" ;
                            $limite_total.= ($limite_min!=0) ? $limite_min." minutos " : "" ;

                            if($pase_mes==true && $pase_dia==true && $pase_hora==true && $pase_min==true && $pase_seg==true){
                                $pase=true;                                
                            }                           
                            
                        }else{                                                
                            $pase=true;                                               
                        }
                        
                    }else{
                        $pase=true;                                               
                    }                    

                    if($pase){
                        $porcentaje = new porcentaje_anual($valor, $fecha, $hora);
                        $resultado_porcentaje = $controlador_porcentaje->guardar("gestionar_porcentaje_anual", $porcentaje);

                        if(!empty($limite_total)){
                            $errores['conseguido']="Se pudo hacer el cambio dentro del limite de tiempo de $limite_total";
                        }else{
                            $errores['conseguido']="Se pudo hacer el cambio";
                        }
                    }else{
                        if(!empty($limite_total)){
                            
                            $errores['guardado']="<p>No se pudo guardar el registro, ya pasó el limite de actualizacion de $limite_total</p><p>Debe esperar al ".(date('Y')+1)."</p>";
                        }else{
                            $errores['guardado']="No se pudo guardar el registro";
                        }
                    }

                }
            }
            
            $_SESSION['errores']=$errores;

            header("location:../../vistas/porcentaje.php");
        }else if($controlador=="familia"){

            $id = !empty($_POST['id']) ? trim($_POST['id']) : null;
            $nombre = !empty($_POST['nombres']) ? ucwords(trim($_POST['nombres'])) : null;

            $errores = array();

            if(is_null($id)){
                $errores['id_nulo']="El id no puede quedar nulo";
            }

            if(!ctype_alpha($id) && !is_numeric($id)){                
                $errores['id']="El id solo debe contener letras o numeros";
            } 

            if(is_null($nombre)){
                $errores['nombres']="El nombre no puede quedar vacio";
            }

            if(is_numeric($nombre) || preg_match("/[0-9]/", $nombre)){                
                $errores['nombre']="El nombre no admite numeros";
            }  
            
            
            /*
                ctype_alpha es una funcion que sólo admite letras, no admite numeros,
                ni espacios, ni caracteres especiales, está aqui para que no se rompa
                la sentencia SQL si pasa por ejemplo una comilla doble o una comilla simple,
                con mysqli seria algo similar a la funcion mysqli_real_escape()
            */

            /*
                Como los nombres vendran separados por espacio, entonces se convertira un momento
                en un array para verificar que cada cadena de caracter esté conformada solo por letras
            */

            //Se convierte $nombres en un array
            $array_nombres = explode(" ", $nombre);

            for($i=0; $i<count($array_nombres); $i++){
                
                if(!ctype_alpha($array_nombres[$i])&&$operacion==0){                
                   $errores['nombreerror2']="El nombre solo debe contener letras";
                }
                
            } 

            
            if(count($errores)==0){
                require '../modelos/familia.php';

                $familia = new familia($id, $nombre);

                $controlador_familia = new controlador("familias");                

                if(is_numeric($operacion) && $operacion=="0"){
                    $resultado_familia = $controlador_familia->guardar("gestionar_familia", $familia);
                    if($resultado_familia){
                        $_SESSION['completado']="La nueva familia se guardó con exito";
                    }else{
                        $errores['guardado']="Hubo algun error en el guardado";
                    }
                }

            }else{
                $errores['guardado']="No se pueden guardar los datos";
            }

            $_SESSION['errores']=$errores;

            header("location: ../../vistas/compras.php");
            
        }else if($controlador=="proveedor"){
            
            $codigo = !empty($_POST['codigo']) ? trim($_POST['codigo']) : null;
            $nombre = !empty($_POST['nombre']) ? ucwords(trim($_POST['nombre'])) : null;
            $telefono = !empty($_POST['telefono']) ? trim($_POST['telefono']) : null;

            $errores = array();

            if(!is_numeric($codigo)){
                $errores['codigo']="El codigo debe ser numerico";
            }

            if(is_null($codigo)){
                $errores['codigo']="El codigo no puede quedar vacio";
            }

            if(is_null($nombre)&&$operacion=="0"){
                $errores['nombre']="El nombre no puede quedar vacio";
            }

            //Capturar errores en el nombre
            if(is_numeric($nombre) || preg_match("/[0-9]/", $nombre)){                
                $errores['nombre']="El nombre no admite numeros";
            }

            if(!is_numeric($telefono) && !is_null($telefono)){
                $errores['telefono']="El telefono debe ser numerico";
            }

            if(count($errores)==0){
                require_once '../modelos/proveedor.php';

                $proveedor = new proveedor($codigo, $nombre, $telefono);

                $controlador_proveedor = new controlador("provedores");

                if(is_numeric($operacion) && $operacion==0){
                    $resultado_proveedor = $controlador_proveedor->guardar("gestionar_proveedor", $proveedor);
                    if($resultado_proveedor){
                        $_SESSION['completado']="El proveedor se guardó";
                    }else{
                        $errores['query']="Algo salio mal en el query";
                    }
                }else if(is_numeric($operacion)){
                    $resultado_proveedor = $controlador_proveedor->eliminar("gestionar_proveedor", $proveedor);
                    if($resultado_proveedor){
                        $_SESSION['completado']="El proveedor $codigo se eliminó";
                    }else{
                        $errores['query']="Algo salio mal en el query";
                    }
                }else{
                    $errores['operacion']="La operacion no es valida";
                }

            }else{
                $errores['guardado']="No se puede guardar";
            }

            $_SESSION['errores']=$errores;

            header("location: ../../vistas/proveedores.php");
        }else if($controlador=="comision"){
            
            $volumen = !empty($_POST['importe']) ? trim($_POST['importe']) : null;
            $porcentaje = !empty($_POST['comision']) ? trim($_POST['comision']) : null;

            $errores = array();

            if(is_null($volumen)){
                $errores['volumen']="El volumen no debe quedar vacio";
            }

            if(is_null($porcentaje)){
                $errores['porcentaje']="El porcentaje no debe quedar vacio";
            }

            if(!is_numeric($volumen) || !is_numeric($porcentaje)){
                $errores['numerico']="Debe ingresar valores numericos";
            }

            if(count($errores)==0){
                require_once '../modelos/comision.php';
                $fecha = date("Y-m-d");
                $comision = new comision(null, $volumen, $porcentaje, $fecha);
                $controlador_comision = new controlador("comisiones");

                if(is_numeric($operacion) && $operacion=="0"){
                    $resultado_comision = $controlador_comision->guardar("gestionar_comision", $comision);

                    if($resultado_comision){
                        $_SESSION['completado']="Se guardó con exito";
                    }else{
                        $errores['guardado']="No se pudo guardar";
                    }

                }                

            }

            $_SESSION['errores']=$errores;

            header("location: ../../vistas/manejar_comisiones.php");

        }else if($controlador=="detalles_compra"){            

            $codigoCompra = !empty($_POST['codigoCompra']) ? trim($_POST['codigoCompra']) : null;
            $proveedor = !empty($_POST['proveedor']) ? trim($_POST['proveedor']) : null;

            $i=1;
            $terminado=false;

            $errores = array();
            $registro = array();   
            $imagenes = array();

            do{
                if(!empty($_POST["registro$i"])){
                    $registro[$i]=$_POST["registro$i"];
                    $i++;
                }else{
                    $terminado=true;
                }

            }while($terminado==false);
            
            $i=1;
            $terminado=false;
            do{
                if(!empty($_FILES["imagen$i"])){
                    $imagenes[$i]= $_FILES["imagen$i"]['tmp_name']!="" ?  $_FILES["imagen$i"] : null;
                    if(is_null($imagenes[$i])){
                        $errores['imagenVacia']="La imagen del producto $i no puede quedar vacio";
                    }
                    $i++;
                }else{
                    $terminado=true;
                }

            }while($terminado==false);                    

            if(is_null($proveedor)||is_null($codigoCompra)||count($registro)==0||count($imagenes)==0){
                $errores['vacio']="No pueden haber datos vacios";
            }else if($proveedor=="Seleccione un proveedor"){
                $errores['proveedor']="Seleccione un proveedor";
            }                   

            if(count($errores)==0){
                $id_empleado = $_SESSION['usuario_logueado'][0]['id'];
                $fecha = date('Y-m-d H:i:s');                
                $total=0;                
                for($i=1; $i<=count($registro); $i++){
                    
                    $cantidad = !empty($registro[$i][3]) ? trim($registro[$i][3]) : null;
                    $precioCompra = !empty($registro[$i][4]) ? trim($registro[$i][4]) : null;

                    if(!is_numeric($cantidad)){
                        $errores['cantidad']="La cantidad debe ser numerico";
                    }

                    if(!is_numeric($precioCompra)){
                        $errores['compra']="El valor de la compra $i debe ser numerico";
                    }                    

                    if(count($errores)==0){                        
                        $total+=($cantidad*$precioCompra);
                    }
                }                
                //Se guarda la informacion de la compra
                require_once '../modelos/compra.php';

                $compra = new compra($codigoCompra, $id_empleado, $proveedor, $fecha, $total);
                $controlador_compra = new controlador("compras");
                
                //Si entra, puede irse a la tabla compras
                if($operacion==0){
                    $resultado_compra = $controlador_compra->guardar("gestionar_compra", $compra);
                    if($resultado_compra){
                        $_SESSION['completado']="La compra se guardó";
                    }else{
                        $errores['compra']="La compra no se guardó";
                    }
                }                



                if(count($errores)==0){
                    for($i=1; $i<=count($registro); $i++){                        

                        $codigo = !empty($registro[$i][0]) ? strtoupper(trim($registro[$i][0])) : null;
                        $familia = !empty($registro[$i][1]) ? trim($registro[$i][1]) : null;
                        $nombre = !empty($registro[$i][2]) ? ucfirst(trim($registro[$i][2])) : null;
                        $cantidad = !empty($registro[$i][3]) ? trim($registro[$i][3]) : null;
                        $precioCompra = !empty($registro[$i][4]) ? trim($registro[$i][4]) : null;
                        $precioVenta = !empty($registro[$i][5]) ? trim($registro[$i][5]) : null;
                        $descripcion = !empty($registro[$i][6]) ? ucfirst(trim($registro[$i][6])) : null;
                        //Se carga la ruta de la imagen
                        $cargarImagen = !empty($imagenes[$i]) && $imagenes[$i]['tmp_name']!=""  ? $imagenes[$i]['tmp_name'] : null;                                          
                        
                        if(is_null($cargarImagen) || is_null($codigo) || is_null($familia) || is_null($nombre) || is_null($cantidad) || is_null($precioCompra) || is_null($precioVenta) || is_null($descripcion)){
                            $errores['vacios']="No pueden haber datos vacios, no se puede guardar el registro $i";
                        }else{
                            //Se abre la imagen a traves de la ruta     
                            //si cargarImagen no es nulo
                            $imagen = fopen($cargarImagen, 'r');
                        }                                            
    
                        if(!is_numeric($precioVenta)){
                            $errores['venta']="El valor de la venta $i debe ser numerico";
                        }

                        if(strlen($descripcion)>120){
                            $errores['descripcion']="Error en el producto $i, la descripcion no puede tener mas de 120 caracteres";
                        }
                        
                        //Antes del detalle de compra, se necesita guardar la info
                        //de los productos
                        if(count($errores)==0){
                            require_once '../modelos/producto.php';
                            $producto = new producto($codigo, $familia, $imagen, $nombre, $precioCompra, $precioVenta, $cantidad, $descripcion);                           

                            if($operacion==0){
                                /*
                                    No se manda crea un controlador,
                                    porque no nos funciona para guardar 
                                    imagenes, ya que para ellas se necesita
                                    usar el PDO::PARAM_LOB
                                */
                                $resultado_producto = insertandoProducto($producto);
                                
                                if($resultado_producto){
                                    $_SESSION["producto"]="Los productos se guardaron";

                                    //Si se guardó, se va a guardar el detalle de la compra

                                    require_once '../modelos/detalle_compra.php';
                                    $detalle = new detalle_compra($codigoCompra, $codigo, $cantidad);
                                    $controlador_detalle = new controlador("detalles_compras");
                                    
                                    $resultado_detalle = $controlador_detalle->guardar("gestionar_detalles_compras", $detalle);

                                    if(!$resultado_detalle){
                                        $errores['detalle']="Hubo algun error en el guardado";
                                    }
                                }else{
                                    $errores["producto$i"]="El producto '$producto->nombre' no se guardó";
                                }
                            }                                                        

                        }else{
                            $errores["guardado$i"]="El registro '$producto->id' no se pudo guardar";
                        }                    
                            
                    }
                }
            }            
            $_SESSION['errores']=$errores;
            header("location:../../vistas/compras.php");

        }else if($controlador=="detalles_venta"){
            //var_dump($_POST);            
            //echo '<br><br><br>';
           //var_dump($_SESSION);
           //variables  que necesito
            $codigo = !empty($_POST['codigoVenta']) ? trim($_POST['codigoVenta']) : null;                                  
            $idvendedor = $_SESSION ["usuario_logueado"]['0']['id'];
            $idcliente = !empty($_POST["cliente"]) ? trim($_POST['cliente']) : null; 
            $fecha = date("Y-m-d");
            $totalventa=0;
            
            //total();            
            $i=1;
            $terminado=false;
            $articulo = array();
            $errores = array();

            do{
                if(!empty($_POST["articulo$i"])){
                    //$articulos[$i]= $_POST["articulo$i"];
                    $articulo[$i] = explode(',',$_POST["articulo$i"]);
                    $idproducto = $articulo[$i][0];
                    $cantidad = is_numeric($articulo[$i][1]) ? $articulo[$i][1] : null;
                    $consulta = total($idproducto);           
                    $cantidadDisponible = !empty(cantidad_disponible($idproducto)) ? cantidad_disponible($idproducto)[0]['stock'] : null;


                    if(is_null($cantidad) || is_null($cantidadDisponible)){
                        $errores['cantidad'][$i] ='Hubo un problema con la cantidad del producto '.$i;
                        $terminado = true;                                            
                    }else if(!empty($consulta)){
                        $precio = $consulta[0]["Precio_ventas"];
                        $total = $cantidad * $precio;                
                        $totalventa += $total;     
                    }
                    //Para ver si hay disponible la cantidad solicitada
                    if(!empty($cantidadDisponible) && $cantidad>$cantidadDisponible){
                        $errores['disponible'][$i]="No está disponible la cantidad solicitada del producto $i";
                    }

                        $i++;
                }else{
                    $terminado=true;
                }
            }while($terminado==false);         
            
            if(empty($idcliente)){
                $errores['idcliente'] = 'Debe elegir un Cliente';
            }
            
            if(empty($articulo)){
                $errores['articulo'] = 'Debe añadir un Articulo';
            }            
                    
            if(empty($errores)){
                
                require_once('../modelos/venta.php');
                $datos = new venta( $codigo,$idvendedor,$idcliente,$fecha,$totalventa);
                $control = new controlador('ventas');
               
                if($operacion == 0){
                    
                    $guard = $control->guardar('gestionar_ventas',$datos);

                    if($guard){
                        $_SESSION['venta']="La venta se guardó";
                    }else{
                        $errores['venta']="La venta no se guardó";                                              
                    }
                    
                    if(empty($errores)){
                       
                        /////////// DETALLES VENTAS   //////////////
                        require_once('../modelos/detalles_ventas.php');
                        $control_detalles = new controlador('detalles_ventas');
                        $i = 1;

                        do{
                            $idproducto = $articulo[$i][0];
                            $cantidad = is_numeric($articulo[$i][1]) ? $articulo[$i][1] : null;
                            $detalle_venta = new detalles_ventas($codigo,$idproducto,intval($cantidad)); 
                            $guardar = $control_detalles->guardar('gestionar_detalles_ventas',$detalle_venta);

                            if($guardar){
                                $_SESSION['detalle_venta'][$i]="El producto '$idproducto' se guardó en la venta";
                                $actualizacion = actualizandoCantidad($detalle_venta->id_producto, $detalle_venta->cantidad);
                                if(!$actualizacion){
                                    $errores['actualizarCantidad'][$i]="No se pudo actualizar la cantidad del producto $i";
                                }
                            }else{
                                $errores['detalle_venta'][$i]="El producto '$idproducto' no se guardó en la venta";                                              
                            }                                                    
                            $i++;
                        }while($i <= count($articulo));      

                    }else{
                        $errores['detalles_ventas'] = ' No se pueden guardar los productos';
                    }
                }                                                       
            }else{
                $errores['venta']="La venta no se guardó";
            }                                                                                                   
            $_SESSION['errores'] = $errores;
            header('location:../../vistas/registrarVenta.php');              
        }        
        
    }    
?>