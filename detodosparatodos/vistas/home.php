<?php
	require_once '../backend/controladores/destruirSesion.php';
	
	if(!empty($_SESSION['usuario_logueado'])){
		require_once '../backend/servicios/listando.php';		
		$perfil_usuario = $_SESSION['usuario_logueado'][0]['perfil_id'];
		$lista_perfiles = obtener_perfiles();
				
		//Para saber qué cabecera mostrar
		foreach($lista_perfiles as $perfil => $idperfil){	
			if($perfil_usuario==$idperfil['id']){		
				$nombreCabecera=$idperfil['nombre'];
				$_SESSION['usuario_logueado']['cabecera']=$nombreCabecera;
			}
		}
		$_SESSION['titulo']="Catalogo de productos";
		//Se crean los enlaces
		require_once '../contenidoHtml/cabecera_'.$nombreCabecera.'.php';				
		require_once '../contenidoHtml/contenido_productos.php';		
		contenido(0, "../");		
	//Lo siguiente es para notificarle al administrador que hay productos que se acabaron
	if($_SESSION['usuario_logueado']['cabecera']=="Administrador" && !empty($_SESSION['notificacion']))	:
?>
<script src="../js/push.min.js"></script>
<script>
	Push.create("Hola! Buen dia", {
    body: "Hay productos que se acabaron! Una cantidad de <?=$_SESSION['notificacion']['total']?>. Por favor, compre mas",
    icon: '../images/imagen.png',	
	timeout: 7000,	
    onClick: function () {  		
        this.close();
    }
});
</script>
<?php	
	endif;	
		require_once '../contenidoHtml/pie_pagina.php';
		
	}else{
		//Esta funcion viene del archivo cerrarSesion.php
		destruir();
	}			
?>