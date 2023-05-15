<?php 	
    require_once '../backend/controladores/destruirSesion.php';

if(!empty($_SESSION['usuario_logueado'])){	
    if($_SESSION['usuario_logueado']['cabecera']=="Administrador"){
        $_SESSION['titulo']="Porcentaje anual";
		require_once '../contenidoHtml/cabecera_Administrador.php';
    require_once '../backend/controladores/alertas.php';    
    require_once '../backend/servicios/listando.php';  

?>
<h2>Porcentaje anual para calcular el precio de venta</h2>
<div class="container">
    <div class="row">
        <div class="col">            
            <?php echo !empty($_SESSION['errores']) ? mostrarError($_SESSION['errores'], "year", "error", "#c90808") : ""?>
            <?php echo !empty($_SESSION['errores']) ? mostrarError($_SESSION['errores'], "conseguido", "exito", "#3a8b3a") : ""?>
            <?php echo !empty($_SESSION['errores']) ? mostrarError($_SESSION['errores'], "guardado", "error", "#c90808") : ""?>
            <?php
                //Trayendo la informacion
                $informacion = obteniendo_porcentaje_actual();

                if(!empty($informacion)){                    
                    $valor=$informacion[0]['valor']."%";
                    $year=$informacion[0]['year'];
                    if($year<=(date('Y')-1)){
                        $placeholder="Porcentaje ".date('Y');
                    }else{
                        $placeholder="Porcentaje ".(date('Y')+1);
                    }
                }else{
                    $valor="No hay valores";
                    $year="No hay registros utiles";
                    $placeholder="Puede ingresar un valor";
                }
            ?>
            <form action="../backend/servicios/servicios.php" method="post">
                <label for="valorActual">Porcentaje actual: </label>
                <input type="text" id="valorActual" class="form-control" value="<?=$valor?>" readonly>
                <label for="valorActual">Año al que pertenece el porcentaje: </label>
                <input type="text" name="year" id="yearCargado" class="form-control" value="<?=$year?>" readonly>
                <label for="valorActual">Ingrese el porcentaje: </label>
                <input type="text" name="valorNuevo" id="valorNuevo" class="form-control" placeholder="<?=$placeholder?>" autofocus required>
                <input type="hidden" name="controlador" value="porcentaje_alerta">
                <input type="hidden" name="operacion" value="0">   
                <?php echo !empty($_SESSION['errores']) ? mostrarError($_SESSION['errores'], "longitud", "error", "#c90808") : ""?>
                <?php echo !empty($_SESSION['errores']) ? mostrarError($_SESSION['errores'], "valor", "error", "#c90808") : ""?>
                <br>
                <button type="submit" class="btn btn-outline-primary">Subir</button>
                <?php !empty($_SESSION['errores']) ? borrar_errores() : ''; ?>                
            </form>            
        </div>
    </div>
</div>


<?php 
    require_once '../contenidoHtml/pie_pagina.php';
    }else{
        header("location: ./home.php");
    }
}else{
	destruir();
}
?>