<?php 
	//require_once '../contenidoHtml/cabecera_Administrador.php'; //Esto es lo que ve el administrador	           

    require_once '../backend/controladores/destruirSesion.php';
	
if(!empty($_SESSION['usuario_logueado'])){	
    if($_SESSION['usuario_logueado']['cabecera']=="Administrador"){
        $_SESSION['titulo']="Manejar empleados";
        require_once '../contenidoHtml/cabecera_Administrador.php';
        require_once '../backend/servicios/listando.php';             
?>

<div class="container">
    <div class="row"> 
        <div class="col-lg-12">            
            <button onclick="document.getElementById('id01').style.display='block'" class="btn btn-success">Nuevo</button>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="tablaEmpleados" class="table table-striped table-bordered table-condensed" style="width:100%; border: 1px solid black;">
                    <thead class="text-center">
                        <tr>
                            <th>Id</th>
                            <th>Perfil</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Telefono</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>                        
                        <?php 
                            $empleados = obtener_empleados();
                            //for($i=1; $i<=1; $i++):
                            foreach($empleados as $empleado):
                        ?>                            
                            <tr>                                
                                <input type="hidden" name="correo" id="correo<?=$empleado['id']?>" value="<?=$empleado['correo']?>">
                                <td id="id<?=$empleado['id']?>"><?=$empleado['id']?></td>
                                <td><?=$empleado['perfil']?></td>
                                <td id="nombre<?=$empleado['id']?>"><?=$empleado['nombres']?></td>
                                <td id="ape<?=$empleado['id']?>"><?=$empleado['apellidos']?></td>
                                <td id="tel<?=$empleado['id']?>"><?=$empleado['telefono']?></td>
                                <td>
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="button" onclick="agregar('id<?=$empleado['id']?>','nombre<?=$empleado['id']?>','ape<?=$empleado['id']?>','tel<?=$empleado['id']?>', 'correo<?=$empleado['id']?>')" class="btn btn-primary btnEditar">Actualizar</button>
                                            <?php                                                  
                                                $cantidad = cantidadCompra_O_Venta($empleado['id'], $empleado['perfil']);                                                
                                                if(empty($cantidad)):
                                            ?>
                                            <form action="../backend/servicios/servicios.php" method="post">
                                                <input type="hidden" name="id" value="<?=$empleado['id']?>">
                                                <input type="hidden" name="controlador" value="empleado">
                                                <input type="hidden" name="operacion" value="1">
                                                <button type="submit" class="btn btn-danger btnBorrar">Eliminar</button>
                                            </form>                                            
                                            <?php                                                
                                                endif; 
                                            ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>                            
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 
<!--Modal para el CRUD-->
<!--Modal de prueba-->

<?php
    require '../contenidoHtml/modal.php';    
    modal_empleado(obtener_perfiles());
?>
<!--Mostrando mensajes de error o exito-->
<?php if(!empty($_SESSION)):        
        require_once '../backend/controladores/alertas.php';
?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/sweetalert.js"></script>
<?php
        if(!empty($_SESSION['completado'])):
?>            
            <script>
                Toast.fire({
                    icon: 'success',
                    title: '<?=$_SESSION['completado']?>'
                });
            </script>
<?php        
            borrar_errores("completado");
        endif;
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