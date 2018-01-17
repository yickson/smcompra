var Carrito = function(params){


  var $this = {
        carrito     : [],
        item        : [],
        hijos       : $("#hijos").data("info"),
        sucursales  : "",
        no_agregado : 0,
        agregado    : 1
    };

    /**
     * Constructor
     * @param {string} params
     * @returns {array}
     */
    this.construct = function(params){
        $.extend($this , params);
    };
    
    /**
     * Metodo para cargar lista de productos disponibles para hijos de usuarios.
     * @param integer usuario
     * @param integer tipo
     * @returns html 
     */
    this.cargarProductos = function(usuario, tipo){
        var id_usuario = usuario;
        var productos_ini   = "";
	var productos_item  = "";
	var productos_fin   = "";
	var productos_full  = "";
        var imagen_asignada = '';
        var rel_asignada    = 0;
        var licencia_ocupada = "";
	var productos = [];
	$.ajax({
	    type  : "POST",
	    cache : false,
	    url   : window.location.href+"/getProductos",
	    data  : {"id_usuario": id_usuario,
                     "tipo": tipo,
                     "hijos" : $this.hijos},
            beforeSend:function(){
                $('.loading').show();
            },
	    success: function(data){
                try{
		//Carga productos
                $('.loading').remove();
		var cantidad_productos = 0;
                var product_x_alumno   = 0;
		var display = 0;
		var mostrar = "block";
                var caso = 0;
		$.each($this.hijos, function( key, val ) {
		    productos_ini   = "";
		    productos_item  = "";
		    productos_fin   = "";
                    
		    if(display === 0){
			mostrar = "block";
			dataAlumno(val.id);

		    }else{
			mostrar = "none";
		    }
		    productos_ini = "<div id='alumno"+val.id+"' class='cont-productos col-md-12' style=' display:"+mostrar+"'>"+
			            "<div class='row'>";
		    $.each(data, function(indice, valor){
                        imagen_asignada = '';
                        rel_asignada    = 0;
                        licencia_ocupada = 0;
			if(val.id === valor.id_alumno){
                            if($this.carrito.indexOf(parseInt(valor.id_producto)) > -1){
                                imagen_asignada = '<img id="img'+val.id+'-'+valor.id_producto+'" src="'+window.location.href+'/../img/productos/agregado.png" width="70%" style=" margin-left:-70%""/>';
                                rel_asignada    = 1;
                            }
                            console.log(caso);
                            if(valor.estado == 1){
                                licencia_ocupada = "";
                                var clickeable = "";
                                var estilo = "style='border: 0px solid; opacity: 0.6'";
                                caso = casos(valor.nivel, valor.rbd, valor.id_producto);
                                
                                switch(caso){
                                    case "ohiggins":
                                        imagen_asignada = '<img id="img'+val.id+'-'+valor.id_producto+'" src="'+window.location.href+'/../img/productos/comprado_2.png" style="margin-left: -86%;width: 86%;position: absolute; margin-top: 7%;"/>';
                                    break;
                                    case "normal":
                                        imagen_asignada = '<img id="img'+val.id+'-'+valor.id_producto+'" src="'+window.location.href+'/../img/productos/comprado.png" width="70%" style=" margin-left:-70%"/>';
                                    break;
                                }
                                rel_asignada    = 1;
                            }else{
                                licencia_ocupada = "producto";
                                var clickeable = "style='cursor: pointer'";
                                var estilo = "style='border: 0px solid'";
                            }
                            
                            //Se determina tratamiento tiene el producto para ser visualizado en carrito
                            caso = casos(valor.nivel, valor.rbd, valor.id_producto);
                            
                            switch(caso){
                                case "ohiggins":
                                    productos_item += casoOhiggins(val, valor, licencia_ocupada, rel_asignada, estilo, clickeable, imagen_asignada);
                                break;
                                case "normal":
                                    productos_item += construirProducto(val, valor, licencia_ocupada, rel_asignada, estilo, clickeable, imagen_asignada);
                                break;
                            }
			    
                            cantidad_productos++;
                            product_x_alumno++;
			}
		    });
                    if(product_x_alumno < 1){
                        productos_item += "<div class='col-md-12'>No existen productos asociados a este alumno</div>";
                    }
		    productos_fin = "</div></div>";
		    productos_full = productos_ini + productos_item + productos_fin;
		    productos.push(productos_full);
		    display++;
		});

		var  productos_html = "";
		$.each(productos, function(i,val){
		    productos_html += val;
		});
		$("#alumno_productos").html(productos_html);
                $("#cantidad_productos").text(cantidad_productos);
                
                var carrito = $("#carrito").data("info").replace(/['"]+/g, "");
                if(carrito != ""){

                        $.each(JSON.parse(carrito), function(i,val){
                            var element = "#"+val[0]+"-"+val[1];
                            var caso = $(element).data("caso");
                            agregarAux(val[1], val[0], 0, element, caso);
                        });
                }
                }catch(err){
                    console.log(err);
                };
	    },
                
	    error: function(xhr, textStatus, errorThrown){
                console.log(xhr.responseText);
	    }
	});
    };
    
    /**
     * Metodo para guardar id de productos en un array de productos almacenado en sesion
     * @param {integer} producto
     * @param {integer} alumno
     * @param {integer} agregado_estado
     * @param {object}  element
     * @param {string}  caso
     * @returns {html}
     */
    this.agregar = function(producto, alumno, agregado_estado, element, caso){
        $this.item = [alumno, producto];
        if(agregado_estado == $this.no_agregado){
            $this.carrito.push($this.item);
            switch(caso){
                case "ohiggins":
                    $("#alumno_productos div#agregado"+alumno+'-'+producto).append('<img id="img'+alumno+'-'+producto+'" src="'+window.location.href+'/../img/productos/agregado_2.png" style="margin-left: -86%;width: 86%;position: absolute; margin-top: 7%;"/>');
                break;
                
                case "normal":
                    $("#alumno_productos div#agregado"+alumno+'-'+producto).append('<img id="img'+alumno+'-'+producto+'" src="'+window.location.href+'/../img/productos/agregado.png" width="70%" style="margin-left:-70%"/>');
                break;
            }
            $(element).data("agregado","1");
        }else if(agregado_estado == $this.agregado){
            var carrito_modificado = eliminarItemDeCarrito( $this.carrito, $this.item );
            $this.carrito = carrito_modificado;
            $("#img"+alumno+'-'+producto).remove();
            $(element).data("agregado","0");
        }
        $("#carrito_txt").text($this.carrito.length);
    };
    
    /**
     * Metodo para obtener datos del alumno
     * @param {integer} id
     * @returns {html}
     */
    this.alumnos = function(id){
	    dataAlumno(id);
	    $(".cont-productos").css("display", "none");
	    $("#alumno"+id).css("display", "block");
    }
    
    /**
     * Metodo que devuelve el contenido del carrito
     * @returns {array}
     */
    this.almacenCarrito = function(){
        return $this.carrito;
    }
    
    /**
     * Metodo que devuelve el carrito precargado con items de session
     * @param {string} params
     * @returns {Array}
     */
    this.preCargarCarritoSession = function(params){
        var productos = JSON.parse(params);
        $this.carrito = productos;
        return $this.carrito;
    }
    
    /**
     * Metodo para ejecutar la eliminacion de un item en el carrito
     * @param {array} arr
     * @param {integer} item
     * @returns {array $this.carrito}
     */
    this.eliminarItem = function(arr, item){
        var carrito = eliminarItemDeCarrito(arr, item.toString());
        return $this.carrito = carrito;
    }
    
    /**
     * Metodo para verificar si el carrito esta vacio o no.
     * @returns {html}
     */
    this.carritoVacio = function(){
        if($this.carrito.length != 0){
            var productos = $this.carrito;
            $("#productos_arr").val(JSON.stringify(productos));
            $("#productos_submit").trigger("click");
        }else{
            swal(
                'Carrito Vacío',
                'Si desea continuar debe añadir productos al carrito de compras.',
                'warning',
            );
        }
    }
    
    this.marcarTodos = function(id_usuario, tipo){
        $.ajax({
	    type  : "POST",
	    cache : false,
	    url   : window.location.href+"/getProductos",
	    data  : {"id_usuario": id_usuario,
                     "tipo": tipo,
                     "hijos": $this.hijos},
	    success: function(data){
                $.each(data, function(i,val){
                    if(val.estado == $this.no_agregado){
                        $this.item = [parseInt(val.id_alumno), parseInt(val.id_producto)];
                        var compareCarrito = $this.carrito;
                        var compareItem    = $this.item;
                        var compareCarritoString = compareCarrito.toString();
                        var compareItemString = compareItem.toString();
                        if(compareCarritoString.indexOf(compareItemString) > -1){
                            //existe indice no se vuelve a agregar
                        }else{
                            $this.carrito.push($this.item);
                            $("#agregado"+val.id_alumno+"-"+val.id_producto).append('<img id="img'+val.id_alumno+'-'+val.id_producto+'" src="'+window.location.href+'/../img/productos/agregado.png" width="70%" style="margin-left:-70%"/>');
                            $("#"+val.id_alumno+"-"+val.id_producto).data("agregado","1");
                            $("#carrito_txt").text($this.carrito.length);
                        }
                    }
                })
            },
            error: function(xhr){
                console.log("err");
            }
        });
    }
    
    /**
     * Devuelve estado de licencia pagado o no pagado.
     * @param {string} codigo_producto
     * @returns {json|boolean} 
     */
    this.simulacionRest = function(codigo_producto){
        var codigo = codigo_producto;
        console.log(codigo);
        var usuario = "test2"; 
        var pass = "123";
        $.ajax({
            type  : "get",
            cache : false,
            dataType: "json",
            url   : "https://serviciosm.cl/smcompra/api/licencia/"+codigo+"/"+usuario+"/"+pass,
            succes : function (result){
                console.log(result);
            },
            error : function (){
                console.log("error");
            } 
        });
    }
    
    //Funciones
    
    /**
     * Funcion que elimina un item dentro del arreglo
     * @param {array} arr
     * @param {integer} item
     * @returns {array}
     */
    function eliminarItemDeCarrito( arr, item ) {
        return arr.filter( function( e ) {
            return e.toString() !== item.toString();
        } );
    };
    
    /**
     * Funcion para obtener alumnos asociados al usuario logeado
     * @param {integer} id
     * @returns {html}
     */
    function dataAlumno(id){
	$.ajax({
	      type : "POST",
	      cache: false,
	      url  : window.location.href+"/getAlumno",
	      dataType: 'json',
	      data : {"id" : id},
	      success: function(data){
		  $(".nombre_alumno").text(data.nombre);
		  $("#colegio_alumno").text(data.establecimiento_nombre);
		  $("#curso_alumno").text(data.curso_nombre);

	      },
	      error: function(data){
		  console.log("err");
	      }
	  });
       };
    
    /**
     * Funcion que devuelve html con descuento segun tipo de usuario Profesor/Alumno
     * @param {integer} tipo
     * @param {integer} valor
     * @returns {html}
     */
    function descuentoTipo(tipo, valor){
        var descuento = "";
        switch(tipo){
            case "Licencia":
                            descuento = "<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Precio </strong>"+"<span class='card-text'>$"+valor+"</span>"+
                                        "<span class='card-text' style='color: red; float:right'> <strong>"+tipo+"</strong> </span></p>";

            break;

            case "Texto":
                            descuento = "<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Precio</strong></p>"+
                                      "<span class='card-text' style='text-decoration: line-through'><i>Antes</i> $"+valor+"</span><br>"+
                                      "<span class='card-text'><i>Ahora <strong>50%</strong> de descuento! </i>$"+(valor * 0.5)+"</span>"+
                                      "<span class='card-text' style='color: red; float:right'> <strong>"+tipo+"</strong> </span>";;
            break;
        }
        return descuento;
    }
    
    /**
     * Metodo para guardar id de productos en un array de productos almacenado en sesion
     * @param {integer} id
     * @param {integer} agregado_estado
     * @returns {html}
     */
    function agregarAux(producto, alumno, agregado_estado, element,caso ){
        $this.item = [alumno, producto];
        if(agregado_estado == $this.no_agregado){
            $this.carrito.push($this.item);
            switch(caso){
                case "ohiggins":
                    $("#alumno_productos div#agregado"+alumno+'-'+producto).append('<img id="img'+alumno+'-'+producto+'" src="'+window.location.href+'/../img/productos/agregado_2.png" style="margin-left: -86%;width: 86%;position: absolute; margin-top: 7%;"/>');
                break;
                
                case "normal":
                    $("#alumno_productos div#agregado"+alumno+'-'+producto).append('<img id="img'+alumno+'-'+producto+'" src="'+window.location.href+'/../img/productos/agregado.png" width="70%" style="margin-left:-70%"/>');
                break;
            }
            $(element).data("agregado","1");
        }else if(agregado_estado == $this.agregado){
            var carrito_modificado = eliminarItemDeCarrito( $this.carrito, $this.item );
            $this.carrito = carrito_modificado;
            $("#img"+alumno+'-'+producto).remove();
            $(element).data("agregado","0");
        }
        $("#carrito_txt").text($this.carrito.length);
        
        return true ;
    };
    
    /**
     * Función para casos exepcionales de Establecimientos,
     * @param {integer} curso
     * @param {integer} rbd
     * @returns {boolean} | caso
     */
    function casos(curso, rbd, producto){
        var caso = "normal";
        
        //Caso Ohiggins Pack, añadir pack de 3 licencias
        if(curso == 8 && rbd == 2200 && producto == 360)
        {
            caso = "ohiggins";
        }
        
        return caso;
    }
    
    function construirProducto(val, valor, licencia_ocupada, rel_asignada, estilo, clickeable, imagen_asignada)
    {
        var producto_html = "<div id='"+val.id+"-"+valor.id_producto+"'class='col-md-4 "+licencia_ocupada+" cont-items-prod' data-caso='normal' data-al='"+val.id+"' data-prod='"+valor.id_producto+"' data-agregado='"+rel_asignada+"' "+estilo+">"+
                                "<div id='clickeable' class='col-md-12 img-hover' "+clickeable+">"+
                                    "<div class='row'>"+
                                        "<div id='agregado"+val.id+'-'+valor.id_producto+"' class='col-md-6' style='padding:10px; margin: 0 auto;'>"+
                                            "<img src='"+window.location.href+"/../img/productos/"+valor.img+"' alt='producto' style='width:70%; ' />"+
                                            imagen_asignada+
                                        "</div>"+
                                    "</div>"+
                                    "<div class='row' style='border-top:1px solid #E6EAEA; background-color: #FFF;'>"+
                                        "<div class='col-md-12' style='padding:10px'>"+
                                            "<p class='card-text' style='font-size:18px; margin: 0.2em 0'><strong>"+valor.asignatura+"</strong></p>"+
                                            "<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Proyecto: </strong><span class='card-text'>"+valor.proyecto+"</span></p>"+
                                            "<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Curso: </strong><span class='card-text'>"+valor.nivel+"</span></p>"+
                                            descuentoTipo(valor.tipo, valor.valor)+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                            "</div>";
        return producto_html;
    }
    
    function casoOhiggins(val, valor, licencia_ocupada, rel_asignada, estilo, clickeable, imagen_asignada)
    {
        var producto_html = "<div id='"+val.id+"-"+valor.id_producto+"'class='col-md-4 "+licencia_ocupada+" cont-items-prod' data-caso='ohiggins' data-al='"+val.id+"' data-prod='"+valor.id_producto+"' data-agregado='"+rel_asignada+"' "+estilo+">"+
                                "<div id='clickeable' class='col-md-12 img-hover' "+clickeable+">"+
                                    "<div class='row'>"+
                                        "<div id='agregado"+val.id+'-'+valor.id_producto+"' class='col-md-12' style='padding:10px; margin: 0 auto;'>"+
                                            "<img src='"+window.location.href+"/../img/productos/Se_Protagonista/pack_ohiggins.png' alt='producto' style='width:89%' />"+
                                            imagen_asignada+
                                        "</div>"+
                                    "</div>"+
                                    "<div class='row' style='border-top:1px solid #E6EAEA; background-color: #FFF;'>"+
                                        "<div class='col-md-12' style='padding:10px'>"+
                                            "<p class='card-text' style='font-size:18px; margin: 0.2em 0'><strong>"+valor.asignatura+"</strong></p>"+
                                            "<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Proyecto: </strong><span class='card-text'>"+valor.proyecto+"</span></p>"+
                                            "<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Curso: </strong><span class='card-text'>"+valor.nivel+"</span></p>"+
                                            descuentoTipo(valor.tipo, valor.valor)+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                            "</div>";
        return producto_html;
    }
};
