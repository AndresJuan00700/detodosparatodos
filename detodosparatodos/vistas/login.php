<?php
    session_start();    
    if(!empty($_SESSION['usuario_logueado'])){
        session_destroy();
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/estilos.css">   
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon"> 
</head>
<body>
    <!--Queda a preferencia usar o no usar el main, es para estructura semántica-->
    <main>
        <div class="contenedor__todo">

            <!--Formulario de Login y registro-->
            <div class="contenedor__login-register">
                <!--Login-->
                <form action="../backend/servicios/validandoLogin.php" class="formulario__login" method="POST">
                    <h2>Iniciar Sesión</h2>
                    <input type="email" name="email" placeholder="Correo Electronico" autofocus required>
                    <input type="password"name="pass" placeholder="Contraseña" required>
                    <!--La etiqueta a se podrá quitar cuando se
                    trabaje en el backend, ya que con php se
                    podrá redireccionar hacia donde deba-->
                    <button type="submit">Entrar</button>
                </form>                
            </div>
        </div>
    </main>    

<!--Maquetando alertas-->
<?php if(!empty($_SESSION)):  
    
    require_once '../backend/controladores/alertas.php';
?>    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/sweetalert.js"></script>
<?php
    if(!empty($_SESSION['errores'])):
            $texto="";
            foreach($_SESSION['errores'] as $error){
                $texto.="<p>".$error.".</p>";
            }
?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Errores',
                    html:'<?=$texto?>',
                    footer:'<strong>Por favor, ingrese los datos correctamente</strong>'
                });
            </script>
<?php           
        borrar_errores();
        endif; 
    endif;
?>

</body>
</html>