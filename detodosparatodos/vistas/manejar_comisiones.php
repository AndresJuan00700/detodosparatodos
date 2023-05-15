<?php 
	//require_once '../contenidoHtml/cabecera_Administrador.php'; //Esto es lo que ve el administrador
	//require_once '../contenidoHtml/cabecera_Vendedor.php'; //Esto es lo que ve el vendedor

    require_once '../backend/controladores/destruirSesion.php';
	 
if(!empty($_SESSION['usuario_logueado'])){	
    if($_SESSION['usuario_logueado']['cabecera']=="Administrador"){
    $_SESSION['titulo']="Manejar comisiones";
	require_once '../contenidoHtml/cabecera_'.$_SESSION['usuario_logueado']['cabecera'].'.php';    
    require_once '../backend/servicios/listando.php';  
    require_once '../backend/controladores/alertas.php';      
?>

<div class="container p-4">
    <div class="row" >
        <div class="col-md-4"></div>
        <div class="col-md-4" id="comisiones">
            <?php if(!empty($_SESSION['completado'])):   ?>
                
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?=$_SESSION['completado'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            
            <?php 
                borrar_errores("completado");
                endif;                
            ?>
            <?php if(!empty($_SESSION['errores'])):   ?>
                    <?php foreach($_SESSION['errores'] as $error):?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?=$error?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    <?php 
                        borrar_errores();
                        endforeach; 
                    ?>                
            
            <?php 
                borrar_errores("completado");
                endif;                
            ?>
            <h2>Comisiones</h2>
            <div class="card card-body">
                <form action="../backend/servicios/servicios.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="importe" class="form-control" placeholder="Importe..." autofocus>
                    </div>
                    <div class="form-group">
                        <input type="text" name="comision" class="form-control" placeholder="Comision en %..." autofocus>
                    </div>                   
                    <input type="hidden" name="controlador" value="comision">
                    <input type="hidden" name="operacion" value="0">
                    <input type="submit" class="btn btn-success btn-block" value="Guardar">
                </form>
            </div>
        </div>  
        <div class="col-md-4"></div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

<?php 
	require_once '../contenidoHtml/pie_pagina.php';
    }else{
        header("location: ./home.php");
    }

}else{
	destruir();
}
?>