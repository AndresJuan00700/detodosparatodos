<?php
function modal_empleado($perfiles){    
?>
<div class="w3-container">   
  <div id="id01" class="w3-modal" style="padding-top:16px">
      <!--Contenido del modal-->
    <div class="w3-modal-content w3-card-4 w3-animate-zoom">
        <!--Cabecera del modal-->
        <header class="w3-container w3-teal" style="background-color:#0e7a53!important"> 
            <span onclick="document.getElementById('id01').style.display='none'" 
            class="w3-button w3-display-topright">&times;</span>
            <h2 class="modal-title" id="exampleModalLabel">Nuevo empleado</h2>
        </header>

        <!--Formulario-->
        <form action="../backend/servicios/servicios.php" method="post" id="formEmpleadoNuevo">
        <div class="w3-container">
            <!--Cada input esta en un grupo-->
            <div class="form-group">
                <label for="id" class="col-form-label">Identificacion: </label>
                <input type="text" name="id" id="id" class="form-control" required="required" autofocus="autofocus">
            </div>
            <div class="form-group">
                <label for="nombres" class="col-form-label">Nombres: </label>
                <input type="text" name="nombres" id="nombres" class="form-control" required="required">
            </div>
            <div class="form-group">
                <label for="apellidos" class="col-form-label">Apellidos: </label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" required="required">
            </div>            
            <div class="form-group">
                <label for="telefono" class="col-form-label">Telefono: </label>
                <input type="tel" name="telefono" id="telefono" class="form-control" pattern="[0-9]+" minlenght="8">
            </div>
            <div class="form-group">
                <label for="email" class="col-form-label">Email: </label>
                <input type="email" name="email" id="email" class="form-control" required="required">
            </div>
            <div class="form-group">
                <label for="pass" class="col-form-label">Contraseña: </label>
                <input type="password" name="pass" id="pass" class="form-control" required="required" minlenght="6">
            </div>
            <div class="form-group">
                <!--Se muestran los perfiles admitidos desde la BD-->
                <?php foreach($perfiles as $perfil):?>
                    <div class="radio_empleado">
                        <input type="radio" id="<?=$perfil['nombre']?>" name="perfil" value="<?=$perfil['id']?>">
                        <label for="<?=$perfil['nombre']?>"><?=$perfil['nombre']?></label>   
                    </div>
                <?php endforeach;?>                                    
            </div>
        </div>
        <br>
        <footer class="w3-container w3-white" style="align-items:right;">
            <input type="hidden" name="controlador" value="empleado">
            <input type="hidden" name="operacion" value="0">            
            <button type="submit" id="btn-guardar" class="btn btn-dark">Guardar</button>
        </footer>
        </form>
        <br>
    </div>
  </div>
</div>

<div class="w3-container">
  <div id="id02" class="w3-modal" style="padding-top:16px">
      <!--Contenido del modal-->
    <div class="w3-modal-content w3-card-4 w3-animate-zoom">
        <!--Cabecera del modal-->
        <header class="w3-container w3-teal" style="background-color:#0d6efd!important"> 
            <span onclick="document.getElementById('id02').style.display='none'" 
            class="w3-button w3-display-topright">&times;</span>
            <h2 class="modal-title" id="exampleModalLabel">Actualizar informacion de empleado</h2>
        </header>

        <!--Formulario-->
        <form action="../backend/servicios/servicios.php" method="post" id="formEmpleados">
        <div class="w3-container">
            <!--Cada input esta en un grupo-->
            <div class="form-group">
                <label for="id" class="col-form-label">Identificacion: </label>
                <input type="text" name="id" id="id_existente" class="form-control" required="required" readonly>
            </div>
            <div class="form-group">
                <label for="nombres" class="col-form-label">Nombres: </label>
                <input type="text" name="nombres" id="nombre_existente" class="form-control" pattern="[a-zA-Z ]+" autofocus="autofocus">
            </div>
            <div class="form-group">
                <label for="apellidos" class="col-form-label">Apellidos: </label>
                <input type="text" name="apellidos" id="apellidos_existente" class="form-control" pattern="[a-zA-Z ]+">
            </div>
            <div class="form-group">
                <label for="telefono" class="col-form-label">Telefono: </label>
                <input type="tel" name="telefono" id="telefono_existente" class="form-control" pattern="[0-9]+" minlenght="8">
            </div>
            <div class="form-group">
                <label for="email" class="col-form-label">Email: </label>
                <input type="email" name="email" id="email_existente" class="form-control">
            </div>
            <div class="form-group">
                <label for="pass" class="col-form-label">Contraseña: </label>
                <input type="password" name="pass" id="pass" class="form-control" placeholder="Ingresala para verificar tu identidad" required="required" minlenght="6">
            </div>
            <div class="form-group">
                <!--Se muestran los perfiles admitidos desde la BD-->
                <?php foreach($perfiles as $perfil):?>
                    <div class="radio_empleado">
                        <input type="radio" id="<?=$perfil['nombre']?>" name="perfil" value="<?=$perfil['id']?>">
                        <label for="<?=$perfil['nombre']?>"><?=$perfil['nombre']?></label>   
                    </div>
                <?php endforeach;?>                                    
            </div>
        </div>
        <br>
        <footer class="w3-container w3-white" style="align-items:right;">    
            <input type="hidden" name="controlador" value="empleado">
            <input type="hidden" name="validar" value="verificar">
            <input type="hidden" name="operacion" value="0">
            <button type="submit" id="btn-guardar" class="btn btn-dark">Guardar</button>
        </footer>
        </form>
        <br>
    </div>
  </div>
</div>

<?php
}
?>
<?php
function modal_cliente(){    
?>
<div class="w3-container">
  <div id="id01" class="w3-modal" style="padding-top:16px; display:'block'!important">
      <!--Contenido del modal-->
    <div class="w3-modal-content w3-card-4 w3-animate-zoom">
        <!--Cabecera del modal-->
        <header class="w3-container w3-teal" style="background-color:#0d6efd!important"> 
            <a href="../vistas/home.php"><span class="w3-button w3-display-topright">&times;</span></a>
            <h2 class="modal-title" id="exampleModalLabel">Nuevo cliente</h2>
        </header>

        <!--Formulario-->
        <form action="../backend/servicios/servicios.php" method="post" id="formClientes">
        <div class="w3-container">
            <!--Cada input esta en un grupo-->
            <div class="form-group">
                <label for="id" class="col-form-label">Identificacion: </label>
                <input type="text" name="id" id="id" class="form-control" required="required">
            </div>
            <div class="form-group">
                <label for="nombres" class="col-form-label">Nombres: </label>
                <input type="text" name="nombres" id="nombres" class="form-control" required="required" pattern="[a-zA-Z ]+">
            </div>
            <div class="form-group">
                <label for="apellidos" class="col-form-label">Apellidos: </label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" required="required" pattern="[a-zA-Z ]+">
            </div>                 
            <input type="hidden" name="trabajador" id="trabajador" value="<?=$_SESSION['usuario_logueado'][0]['id'];?>">
            <!--Este div es para los telefonos que el usuario quiera agregar-->
            <div id="inputs_agregados"></div>
            <!---->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                    </div>
                    <input type="tel" class="form-control" placeholder="Numero Whatsapp" id="whatsapp" name="whatsapp">
                </div>  
                <div class="input-group">  
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                    </div>                  
                    <input type="text" name="instagram" id="instagram" placeholder="Usuario de instagram" class="form-control">                    
                </div> 
                <div class="input-group"> 
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-telegram"></i></span>
                    </div>                   
                    <input type="tel" name="telegram" id="telegram" placeholder="Numero de telegram" class="form-control">                    
                </div> 
                <div class="input-group"> 
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                    </div>                   
                    <input type="text" name="twitter" id="twitter" placeholder="Usuario de twitter" class="form-control">
                </div>                             
            </div>
        </div>
        <br>
        <footer class="w3-container w3-white" style="align-items:right;">   
            <input type="hidden" name="controlador" value="cliente">
            <input type="hidden" name="operacion" value="0">           
            <button type="button" onclick="agregar_input()" id="btn-guardar" class="btn btn-dark">Agregar numero</button>            
            <button type="submit" id="btn-guardar" class="btn btn-dark">Guardar</button>
        </footer>
        </form>
        <br>
    </div>
  </div>
</div>

<?php
}
?>

<?php
function modal_familia(){
?>

<div class="w3-container">   
  <div id="id01" class="w3-modal" style="padding-top:16px">
      <!--Contenido del modal-->
    <div class="w3-modal-content w3-card-4 w3-animate-zoom">
        <!--Cabecera del modal-->
        <header class="w3-container w3-teal" style="background-color:#0d6efd!important"> 
            <span onclick="document.getElementById('id01').style.display='none'" 
            class="w3-button w3-display-topright">&times;</span>
            <h2 class="modal-title" id="exampleModalLabel">Nueva familia</h2>
        </header>

        <!--Formulario-->
        <form action="../backend/servicios/servicios.php" method="post" id="formEmpleadoNuevo">
        <div class="w3-container">
            <!--Cada input esta en un grupo-->
            <div class="form-group">
                <label for="id" class="col-form-label">Identificacion: </label>
                <input type="text" name="id" id="id" class="form-control" required="required" autofocus="autofocus">
            </div>
            <div class="form-group">
                <label for="nombres" class="col-form-label">Nombres: </label>
                <input type="text" name="nombres" id="nombres" class="form-control" required="required">
            </div>            
        </div>
        <br>
        <footer class="w3-container w3-white" style="align-items:right;">
            <input type="hidden" name="controlador" value="familia">
            <input type="hidden" name="operacion" value="0">            
            <button type="submit" id="btn-guardar" class="btn btn-dark">Guardar</button>
        </footer>
        </form>
        <br>
    </div>
  </div>
</div>

<?php
}
?>