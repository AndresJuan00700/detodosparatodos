<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>De todos para todos</title>	
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="../css/normalize.css">
	<link rel="stylesheet" href="../css/estilos.css">
	<link rel="stylesheet" href="../css/registroVentas.css">
	<link rel="stylesheet" href="../css/estilosCompraNueva.css">	
	<link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
	<script src="../js/jquery-3.2.1.js"></script>
	<script src="../js/script.js"></script>
	 <!-- debajo esta el codigo que me permite poner iconos de font awesome (recuerda buscar otra api para eso) -->
	 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>	 
<body>
	<!--Menu de navegacion-->
	<nav id="menu">
		<!--Poner aqui un logo para la tienda-->
		<div id="logo">
			<a href="./home.php"><img src="../images/logo.PNG" alt="De todos para todos" > </a>
			<h2><?=$_SESSION['titulo']?></h2>
			<i class="fa fa-user-o" id="inicio">
				<?=$_SESSION['usuario_logueado'][0]['nombres']?> <?=$_SESSION['usuario_logueado'][0]['apellidos']?>
				<button type="button" class="btn btn-outline-secondary" onclick="location.href='../backend/controladores/cerrarSesion.php'">Cerrar sesion</button>
			</i>						
		</div>			
		<!-- Enlaces -->
		<div class="justify-content-center" id="enlaces">			
				<ul>					
					<a href="./ventas.php">Detalle de ventas</a>
					<a href="./proveedores.php">Proveedores</a>					
					<a href="./compras.php">Comprar</a>
					<a href="./manejar_empleados.php">Empleados</a>					
					<a href="./porcentaje.php">Porcentaje</a>	
					<a href="./manejar_comisiones.php">Comisiones</a>				
				</ul>			
		</div>		
		<hr>	  
	</nav>		