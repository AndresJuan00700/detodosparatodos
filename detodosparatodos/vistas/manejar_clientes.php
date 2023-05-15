<?php 
    require_once '../backend/controladores/destruirSesion.php';
	if(!empty($_SESSION['usuario_logueado'])){	
        if($_SESSION['usuario_logueado']['cabecera']=="Vendedor"){
        $_SESSION['titulo']="Manejar cliente";
        require_once '../contenidoHtml/cabecera_'.$_SESSION['usuario_logueado']['cabecera'].'.php';
?>

<?php
    require '../contenidoHtml/modal.php';
    modal_cliente();
?>

<script>
    document.getElementById('id01').style.display='block';
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/sweetalert.js"></script>

<?php       
        require_once '../backend/controladores/alertas.php';                  
?>                                    
<?php               
        if(!empty($_SESSION['completado'])&&(!empty($_SESSION['whatsapp'])||!empty($_SESSION['instagram'])||!empty($_SESSION['telegram'])||!empty($_SESSION['twitter'])||!empty($_SESSION['numeros']))):            
            $mensaje="<p>".$_SESSION['completado']."</p>";
            if(!empty($_SESSION['whatsapp'])){
                $mensaje.="<p>".$_SESSION['whatsapp']."</p>";
            }
            if(!empty($_SESSION['instagram'])){
                $mensaje.="<p>".$_SESSION['instagram']."</p>";
            }
            if(!empty($_SESSION['telegram'])){
                $mensaje.="<p>".$_SESSION['telegram']."</p>";
            }
            if(!empty($_SESSION['twitter'])){
                $mensaje.="<p>".$_SESSION['twitter']."</p>";
            }
            if(!empty($_SESSION['numeros'])){
                $mensaje.=$_SESSION['numeros'];
            }
?>                        
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registros',
                    html: '<?=$mensaje?>',
                    footer:'<i>Si terminó, cierre el formulario</i>'
                });
            </script>
<?php                    
            borrar_errores("completado"); 
            borrar_errores("whatsapp");            
            borrar_errores("instagram");            
            borrar_errores("telegram");            
            borrar_errores("twitter");  
            borrar_errores("numeros");          
        endif;        
?>
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
?>

<script src="../js/add_to_actualize.js"></script>
<?php
	require_once '../contenidoHtml/pie_pagina.php';
    }else{
        header("location: ./home.php");
    }
}else{
	destruir();
}
?>