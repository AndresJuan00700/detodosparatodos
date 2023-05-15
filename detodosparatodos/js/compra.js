var tbody = document.getElementById("contenido");
tbody.innerHTML="";

var manejandoFila = new Array();

function agregar(){
    var familia = document.getElementById("familias").value;           

    if(familia!="Seleccione una familia"){
        var codigo = document.getElementById("codigoProducto").value;
        var nombre = document.getElementById("nombreProducto").value;
        var cantidad = document.getElementById("cantProducto").value;
        var precioCompra = document.getElementById("precioProducto").value;
        var precioVenta = document.getElementById("precio_ventaProducto").value;        
        var descripcion = document.getElementById("descripcionProducto").value;
        
        if(codigo!="" && nombre!="" && cantidad!="" && precioCompra!="" && precioVenta!="" && descripcion!="" && familia!=""){            
            manejandoFila.push(manejandoFila.length);       
            
            //Se crea la fila
            const fila = document.createElement("tr");
            fila.id="fila"+manejandoFila.length+"";

            //Se crea cada columna
            const col_codigo = document.createElement("td");
            col_codigo.className="product-code";
            col_codigo.textContent=codigo;            

            const col_nombre = document.createElement("td");
            col_nombre.className="product-name";
            col_nombre.textContent=nombre;

            const col_precioC = document.createElement("td");
            col_precioC.className="product-priceC";
            col_precioC.textContent=precioCompra;

            const col_cant = document.createElement("td");
            col_cant.className="product-stock";   
            col_cant.textContent=cantidad;            

            //Se va a crear un solo input con todos las variables en un array
            var variables =
            {'codigo':codigo, 'familia':familia, 'nombre':nombre, 'cantidad':cantidad, 'precioCompra':precioCompra, 'precioVenta':precioVenta, 'precioVenta':precioVenta, 'descripcion':descripcion};                                
            
            //En el siguiente td se contendrá los input hidden del formulario
            const col_apartado = document.createElement("td");
            col_apartado.className="product-remove";

            for(var indice in variables){
                const input = document.createElement("input");
                input.type="hidden";
                input.name="registro"+manejandoFila.length+"[]";
                input.value=variables[indice];

                col_apartado.appendChild(input);
            }                        

            const fileImage = document.createElement("div");
            fileImage.className="form-group";

            const inputFile = document.createElement("input");
            inputFile.type="file";
            inputFile.accept="image/*";
            inputFile.className="form-control";
            inputFile.id="imagen"+manejandoFila.length;
            inputFile.name="imagen"+manejandoFila.length;            

            fileImage.appendChild(inputFile);

            /*
                <div class="imagen_formulario">
                    <img id="img1-foto">							
                </div> 
            */

            const boton = document.createElement("button");
            boton.className="btn btn-primary btn-sm";
            boton.type="button";
            boton.onclick= function (){                                                
                eliminando_fila = document.getElementById("fila"+manejandoFila.length);
                if(!eliminando_fila){
                    alert("Esta fila no existe");
                }else{
                    padre = eliminando_fila.parentNode;
                    padre.removeChild(eliminando_fila);
                }
                manejandoFila.pop();                
            };

            const icono = document.createElement("i");
            icono.className="fas fa-eraser";

            boton.appendChild(icono);
            col_apartado.appendChild(boton);

            fila.appendChild(col_codigo);            
            fila.appendChild(col_nombre);
            fila.appendChild(col_precioC);
            fila.appendChild(col_cant);
            fila.appendChild(fileImage);
            fila.appendChild(col_apartado);

            tbody.appendChild(fila);            

            document.getElementById("familias").value="Seleccione una familia";
            document.getElementById("codigoProducto").value="";
            document.getElementById("nombreProducto").value="";
            document.getElementById("cantProducto").value="";
            document.getElementById("precioProducto").value="";
            document.getElementById("precio_ventaProducto").value="";        
            document.getElementById("descripcionProducto").value="";
            //document.getElementById("img-foto").src="";
            

        }else{
            alert("No deje elementos vacio");
        }

    }else{
        alert("Seleccione una familia");
    }
}
/*
let vista_preliminar = (event) =>{    
    let leer_img  = new FileReader();
    let id_img = document.getElementById("img1-foto");

    leer_img.onload = ()=>{
        if(leer_img.readyState==2){
            id_img.src = leer_img.result
        }
    }

    leer_img.readAsDataURL(event.target.files[0])
    
}

<div class="imagen_formulario">
	<img id="img1-foto">							
</div>


*/