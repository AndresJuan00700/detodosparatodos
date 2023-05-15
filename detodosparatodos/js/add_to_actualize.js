function agregar(id, nombres, apellidos, telefonos, correos){    
    //Obtenemos la informacion del cliente a actualizar
    var id_empleado = document.getElementById(id).innerHTML;    
    var nombre = document.getElementById(nombres).innerHTML;
    var apellido = document.getElementById(apellidos).innerHTML;
    var telefono = document.getElementById(telefonos).innerHTML;
    var correo = document.getElementById(correos).value;
    
    //Le pasamos esa informacion a un nuevo formulario
    document.getElementById('id02').style.display='block';
    document.getElementById('id_existente').value=id_empleado;
    document.getElementById('nombre_existente').value=nombre;
    document.getElementById('apellidos_existente').value=apellido;
    document.getElementById('telefono_existente').value=telefono;
    document.getElementById('email_existente').value=correo;

}
document.getElementById("inputs_agregados").innerHTML=""; 
var iterador=0;

function agregar_input(){    
    iterador++;
    var input = "<div class='form-group'>"+                    
                    "<label for='numero"+iterador+"' class='col-form-label'>Numero "+iterador+": </label>"+
                    "<input type='tel' name='numero"+iterador+"' id='numero"+iterador+"' class='form-control'pattern=[0-9]+>"+                        
                "</div>";
    document.getElementById("inputs_agregados").innerHTML+=input;    
}
