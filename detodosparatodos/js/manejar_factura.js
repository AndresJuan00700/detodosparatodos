document.getElementById("contenedor_factura").innerHTML="";
document.getElementById("input_adicionales").innerHTML="";
lista=0;

var cuerpo = document.getElementById("contenedor_factura");

var listando = new Array();

var totalCompra = new Array();
var flag=false;

function agregar(){        
    info=document.getElementById("codigo").value;
    cantidad=document.getElementById("cant").value;
    console.log(info);
    console.log(cantidad);
    console.log(totalCompra);
    console.log(flag);
    if(codigo!=="" && codigo!="Seleccione un producto" && cantidad!=="" && cantidad>0){
        var apartado=document.getElementById("btn-quitar");        

        listando.push(listando.length);
        
        var informacion = info.split(",");  

        //var index = listando.indexOf(listando[listando.length-1]);            

        codigo=informacion[0];
        precio=informacion[1];
        total=precio*cantidad;  
        totalCompra.push(total);
        
        producto=document.getElementById(codigo).innerHTML;                

        //Se crea la fila
        const fila = document.createElement("tr");
        fila.id=""+listando.length+"";

        //Se crean las columnas
        const col_codigo = document.createElement("td");
        col_codigo.textContent=codigo;

        const col_producto = document.createElement("td");
        col_producto.textContent=producto;

        const col_cantidad = document.createElement("td");
        col_cantidad.textContent=cantidad;

        const col_total = document.createElement("td");
        col_total.textContent=total;

        const boton = document.createElement("button");
       if(flag==false){                       
            //<button type="button" onclick="" id="quitar" class="btn-flotante"><i class="far fa-trash-alt"></i> - Quitar ultimo</button> 
            const icono = document.createElement("i");
            icono.className="far fa-trash-alt";            
                        
            boton.type="button";
            boton.id="quitar";
            boton.className="btn btn-flotante"; 
            boton.textContent="Quitar ultimo - ";
            boton.appendChild(icono);
            boton.onclick = function (){                            
                eliminando_fila = document.getElementById(listando.length);
                if(!eliminando_fila){
                    alert("Esta fila no existe");
                }else{
                    padre = eliminando_fila.parentNode;
                    padre.removeChild(eliminando_fila);
                }
                
                listando.pop(); 
                totalCompra.pop()
                
                if(listando.length==0){         
                    var btn = document.getElementById("quitar"); 
                    flag=false;
                    padre = btn.parentNode;
                    padre.removeChild(btn);
                    document.getElementById("totalCompra").value="";

                }else{
                    document.getElementById("totalCompra").value=sumar();
                }

                    
            }        
                      
       }
       
        //Se agregan las columas a la fila
        fila.appendChild(col_codigo);
        fila.appendChild(col_producto);
        fila.appendChild(col_cantidad);
        fila.appendChild(col_total);
        if(flag==false){
            console.log("Entro al boton");
            apartado.appendChild(boton);
            flag=true;
        }

        //Datos para la bd        
        valores = [codigo, cantidad];
        add_to_form(valores);

        cuerpo.appendChild(fila);        

        document.getElementById("codigo").value="Seleccione el producto";
        document.getElementById("cant").value="";
        document.getElementById("totalCompra").value=sumar();
    }else{
        alert("Ingrese correctamente los datos");
    }

}

function add_to_form(valores){
    var inputs="<input type='hidden' name='articulo"+listando.length+"' value='"+valores+"'>";

    document.getElementById("input_adicionales").innerHTML+=inputs;
}

function sumar(){
    var suma = 0;

    for (const key in totalCompra) {
        suma+=totalCompra[key];
    }

    return suma;

    
}