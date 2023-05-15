<?php 
	//require_once '../contenidoHtml/cabecera_Administrador.php'; //Esto es lo que ve el administrador
	//require_once '../contenidoHtml/cabecera_Vendedor.php'; //Esto es lo que ve el vendedor
	require_once '../backend/controladores/destruirSesion.php';
	
if(!empty($_SESSION['usuario_logueado'])){
	if($_SESSION['usuario_logueado']['cabecera']=="Administrador"){	
		$_SESSION['titulo']="Registrar ventas";		
	require_once '../contenidoHtml/cabecera_Administrador.php';
	require_once '../backend/servicios/listando.php';
	$informacion = !empty(detallesVentas()) ? detallesVentas() : null;
	
	if(!is_null($informacion)){
		setlocale(LC_ALL, 'es_Es');      
?>	

	<table class="tftable" style="border:'1'">
		<tr>
			<th>Fecha de la venta</th>			
			<th>Nombre del producto</th>
			<th>Precio de venta</th>
			<th>Cantidad vendida</th>
			<th>Nombre del vendedor</th>
			<th>Nombre del cliente</th>			
		</tr>
		<?php 
			foreach($informacion as $fila): 
				//Esto es para pasar el numero del mes al nombre en español
				$arrayMes=explode("-", $fila['Fecha de venta']);
				$numeroMes=$arrayMes[1];
				$dateObj = DateTime::createFromFormat('!m', $numeroMes);
                $arrayMes[1] = ucfirst(strftime('%B', $dateObj->getTimestamp()));
				
				$fila['Fecha de venta']=implode("-", $arrayMes);				
		?>
		<tr>
			<td><?=$fila['Fecha de venta']?></td>
			<td><?=$fila['Nombre del producto']?></td>
			<td>$<?=$fila['Precio de venta']?></td>
			<td><?=$fila['Cantidad']?></td>
			<td><?=$fila['Nombre del empleado']?></td>
			<td><?=$fila['Nombres del cliente']?></td>
		</tr>
		<?php endforeach; ?>		
	</table>
	<br>
	<?php 
	require_once '../contenidoHtml/pie_pagina.php';
	}else{
		echo "<h2>No se han vendido productos</h2>";
	}
	}else{
		header("location: ./home.php");
	}
}else{
	destruir();
}
		
?>