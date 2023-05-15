<?php 
	//require_once '../contenidoHtml/cabecera_Administrador.php'; //Esto es lo que ve el administrador
	//require_once '../contenidoHtml/cabecera_Vendedor.php'; //Esto es lo que ve el vendedor

require_once '../backend/controladores/destruirSesion.php';
if(!empty($_SESSION['usuario_logueado'])){	
	if($_SESSION['usuario_logueado']['cabecera']=="Vendedor"){
		$_SESSION['titulo']="Registrar venta";
	require_once '../contenidoHtml/cabecera_'.$_SESSION['usuario_logueado']['cabecera'].'.php';
	require_once '../backend/servicios/listando.php';
	$productos = !empty(productos(0)) ? productos(0) : null;		
	
	function generandoCodigo($devolver){
		$DesdeLetra = "a";
		$HastaLetra = "z";
		$DesdeNumero = 0;
		$HastaNumero = 9;

		$letraAleatoria = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
		$numeroAleatorio = rand($DesdeNumero, $HastaNumero);
		if($devolver==1){
			return $letraAleatoria;
		}else if($devolver==2){
			return $numeroAleatorio;
		}
	}

	function creandoCodigo(){
		$codigo=date("Ym");
		for($i=0; $i<2; $i++){
			$codigo.=generandoCodigo(rand(1,2));
		}
		return strtoupper($codigo);
	}
	//Esto se quita de aqui cuando los mensajes queden listos
	//De aqui
	//var_dump($_SESSION);
	/*unset($_SESSION['venta']);
	unset($_SESSION['detalle_venta']);
	unset($_SESSION['errores']);*/
	//Hasta aqui
?>
<!--Caja de cantidades-->
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col-md-auto" id="cantidades">
		<?php
			$total = obtener_total_clientes();	
			$numero_clientes = obtener_numero_clientes($_SESSION['usuario_logueado'][0]['id']);
			if(!empty($numero_clientes)){
				$numero_clientes=$numero_clientes[0]['numero'];				
			}else{
				$numero_clientes=0;
			}
		?>
		<p>Manejas <span class="numero"><?=$numero_clientes?></span> de <span class="numero"><?=$total[0]['total']?></span> clientes, sabiendo que el anonimo es para todos</p>
    </div>	
  </div>
</div>
<br>
<!--======== INICIO DE FORMULARIO ==========-->
<div class="container justify-content-center">
	<div class="row">
		<div class="col col-md-4">
			<div class="well well-sm">
				<fieldset>
				<form class="form-horizontal justify-content-center" method="post">					
						<legend class="text-center header">Registrar Ventas</legend>
												
						<div class="form-group">								
							<div class="col-md-8">								
								<select name="name" id="codigo" class="form-select">
									<option>Seleccione el producto</option>
									<?php if(!empty($productos)): ?>									
								<?php foreach($productos as $indice => $producto):?>
									<?php if($producto['stock']>0):?>
									<option id="<?=$producto['id']?>" value="<?=$producto['id']?>,<?=$producto['precio']?>"><?=$producto['Nombre del producto']?></option>									
									<?php endif; ?>
								<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
						<br>
						<div class="form-group">								
							<div class="col-md-8">
								<input id="cant" name="precioventa" type="text" placeholder="Cantidad de Productos" class="form-control">
							</div>
						</div>
						<br>
						<div class="form-group" id="btnregistro">
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-primary btn-lg" onclick="agregar()">Agregar</button>								
							</div>							
						</div>										
				</form>
				</fieldset>
			</div>
		</div>
		
		<div class="col col-md-8">
			<div class="well well-sm">
				<table class="table table-borderless factura text-center">
				<thead>
					<tr>
						<th>Id</th>
						<th>Nombre</th>
						<th>Cantidad</th>
						<th>Total</th>												
					</tr>					
				</thead>				
				<tbody id="contenedor_factura">						
				</tbody>

				</table>				
				<form action="../backend/servicios/servicios.php" method="post">				
					<div id="input_adicionales"></div>	

					<div class="input-group mb-3">						
						<div class="custom-prepend">
							<?php 
								$clientes = obtener_clientes($_SESSION['usuario_logueado'][0]['id']);								
							?>																	
								<?php
									if(!empty($clientes)):										
								?>
									<select class="form-select" name="cliente" id="cliente" required>
										<option value="">Elija un cliente</option>
										<?php foreach($clientes as $cliente):?>
											<option value="<?=$cliente['id']?>"><?=$cliente['cliente']?></option>
										<?php endforeach;?>
									</select>
								<?php
									else:
								?>
										<h3>No tienes clientes</h3>
								<?php
									endif;
								?>														
						</div> 
						<div class="col-md-4">
							<input type="text" style="text-align:center;" name="codigoVenta" value="<?=creandoCodigo()?>" class="form-control" readonly>
						</div>	
						<div class="col-md-4">							
							<input type="text" style="text-align:center;" name="totalCompra" id="totalCompra" placeholder="Total de venta" class="form-control" readonly>
						</div>			
					</div>							
					<div class="form-group" id="btnregistro">
						<div class="col text-center">
							<button type="submit" class="btn btn-primary btn-lg">Guardar</button>	
							<input type="hidden" name="controlador" value="detalles_venta">
							<input type="hidden" name="operacion" value="0">																				
						</div>													
					</div>					
				</form>
			</div>
		</div>
		<!---->
	</div>	
</div>
<div id="btn-quitar"></div>
<button type="button" onclick="productos()" class="btn-flotante"><i class="fas fa-table"></i> - Ver productos</button> 
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/sweetalert.js"></script>
<script src="../js/manejar_factura.js"></script>
<?php			
	$html="";
	if(empty($productos)){
		$html.="<strong>No hay productos</strong>";
	}else{
		$html.="<table class='table table-bordered'>";
			$html.="<thead>";
				$html.="<tr>";
					$html.="<th>Codigo</th>";									
					$html.="<th>Producto</th>";				
					$html.="<th>Familia</th>";				
					$html.="<th>Precio</th>";				
					$html.="<th>Stock</th>";													
				$html.="<tr>";
			$html.="</thead>";
			$html.="<tbody>";
			foreach($productos as $producto){									
				$html.="<tr>";
					$html.="<td>".$producto['id']."</td>";					
					$html.="<td>".$producto['Nombre del producto']."</td>";
					$html.="<td>".$producto['Nombre de familia']."</td>";
					$html.="<td>".$producto['precio']."</td>";
					$html.="<td>".$producto['stock']."</td>";					
				$html.="<tr>";
			}			
			$html.="</tbody>";
		$html.="</table>";
	}
?>
<script>
	function productos(){
		Swal.fire({
			title:'Productos',
			width:'1000px',
			showConfirmButton: false,
			html:"<?=$html?>",
			showClass:{
				popup: 'animate_animated animate__fadeInDown'
			},
			hideClass:{
				popup: 'animate_animated animate__fadeOutUp'
			},
			footer: 'Aqui estan todos los productos'
		})
	}
</script>
<!-- Mostrar Notificaciones -->


<?php 
	require_once '../backend/controladores/alertas.php';		
    if(!empty($_SESSION['venta'])):
		$texto="";
		if(!empty($_SESSION['detalle_venta'])){
			foreach($_SESSION['detalle_venta'] as $producto){
				$texto.="<p>".$producto."</p>";
			}
		}
		var_dump($texto)
?>            
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '<?=$_SESSION['venta']?>',
					html: '<?=$texto?>'
                });
            </script>
<?php        
            borrar_errores("venta");
			borrar_errores("detalle_venta");

        endif;
        if(!empty($_SESSION['errores'])):
            $texto="";
            foreach($_SESSION['errores'] as $errores => $error){
                
				if(is_array($error)){
					foreach($error as $indice){
						$texto.="<p>".$indice.".</p>";
					}
				}else{
					$texto.="<p>".$error.".</p>";
				}
				
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

	require_once '../contenidoHtml/pie_pagina.php';
	}else{
		header("location: ./home.php");
	}
}else{
	destruir();
}
?>

