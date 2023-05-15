<?php 	
    require_once '../backend/controladores/destruirSesion.php';
	
if(!empty($_SESSION['usuario_logueado'])){	
    if($_SESSION['usuario_logueado']['cabecera']=="Administrador"){
        $_SESSION['titulo']="Proveedores";
        require_once '../contenidoHtml/cabecera_Administrador.php';
        require_once '../backend/servicios/listando.php';         
        require_once '../backend/controladores/alertas.php';
?>

<!--Body-->
<!--clase contenedor para tener todo centrado-->
<div class="container p-4">

    <div class="row" >
        <!--primera columna de 4 (Parte del formulario)-->
        <div class="col-md-4">

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

            <!--Tarjeta-->
            <h2>Manejar proveedores</h2>
            <div class="card card-body">
                <form action="../backend/servicios/servicios.php" method="POST">
                    <!--Inputs formulario-->
                    <div class="form-group">
                        <input type="text" name="codigo" class="form-control" placeholder="Codigo..." autofocus>
                    </div>
                    <div class="form-group">
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre..." autofocus>
                    </div>
                    <div class="form-group">
                        <input type="text" name="telefono" class="form-control" placeholder="Telefono..." autofocus>
                    </div>
                    <input type="hidden" name="controlador" value="proveedor">
                    <input type="hidden" name="operacion" value="0">
                    <!--boton formulario-->
                    <input type="submit" class="btn btn-success btn-block" name="guardar" value="Guardar">
                </form>
            </div>

        </div>  
        <!--Segunda Columna 8 Para mostrar los datos de proveedores-->
        <div class="col-md-8">
            <h1>Lista de Proveedores</h1>
            <table class="table table-bordered">
                <thead>
                    <!--Cabecera de la tabla--> 
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Telefono</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <!--Consulta para llamar los datos de proveedor-->
                <tbody>
                    <?php                          
                        $resultado_proveedor = proveedores();
                        if(!empty($resultado_proveedor)):                            
                    ?>
                        <?php foreach($resultado_proveedor as $proveedor): ?>                                
                                    <tr>
                                        <td>  <?=$proveedor['codigo']?> </td>
                                        <td>  <?=$proveedor['nombre']?> </td>
                                        <td>  <?=$proveedor['telefono']?> </td>
                                        <td>  
                                        <?php                                                  
                                            $cantidad = cantidadCompra_O_Venta($proveedor['codigo'], "Proveedor");                                                                                            
                                            if(empty($cantidad)):                                            
                                        ?>                     
                                        <form action="../backend/servicios/servicios.php" method="post">                 
                                            <input type="hidden" name="codigo" value="<?=$proveedor['codigo']?>">
                                            <input type="hidden" name="controlador" value="proveedor">
                                            <input type="hidden" name="operacion" value="1">
                                            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                        <?php
                                                else:
                                                    echo '<p style="color:#dd1212;">No se puede</p>';
                                                endif; 
                                            ?>
                                        </td>
                                    </tr>                                
                                
                    <?php 
                            endforeach; 
                        endif;
                    ?>
                    

                </tbody>

            </table>
        </div>
    
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