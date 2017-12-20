var Carrito = function(params){


  var $this = {
        carrito    : [],
        hijos      : $("#hijos").data("info"),
        sucursales : "",
        canvas     : "",
        legenda    : ""
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
	    url   : "carrito/getProductos",
	    data  : {"id_usuario": id_usuario,
                     "tipo": tipo,
                     "hijos" : $this.hijos},
	    success: function(data){
                try{
		//Carga productos
		var cantidad_productos = 0;
                var product_x_alumno   = 0;
		var display = 0;
		var mostrar = "block";
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
                                imagen_asignada = '<img id="img'+valor.id_producto+'" src="/smcompra/img/productos/agregado.png" width="70%" style=" margin-left:-70%""/>';
                                rel_asignada    = 1;
                            }
                            if(valor.estado == 1){
                                licencia_ocupada = "";
                                var clickeable = "";
                                var estilo = "style='border: 0px solid; opacity: 0.6'";
                                imagen_asignada = '<img id="img'+valor.id_producto+'" src="/smcompra/img/productos/comprado.png" width="70%" style=" margin-left:-70%"/>';
                                rel_asignada    = 1;
                            }else{
                                licencia_ocupada = "producto";
                                var clickeable = "style='cursor: pointer'";
                                var estilo = "style='border: 0px solid'";
                            }
			    productos_item +=   "<div id='"+valor.id_producto+"' class='col-md-4 "+licencia_ocupada+" cont-items-prod' data-rel='"+valor.id_producto+"' data-agregado='"+rel_asignada+"' "+estilo+">"+
						    "<div id='clickeable' class='col-md-12 img-hover' "+clickeable+">"+
							"<div class='row'>"+
							    "<div id='agregado"+valor.id_producto+"' class='col-md-6' style='padding:10px; margin: 0 auto;'>"+
								"<img src='/smcompra/img/productos/"+valor.img+"' alt='producto' style='width:70%; ' />"+
                                                                imagen_asignada+
							    "</div>"+
                                                        "</div>"+
                                                        "<div class='row' style='border-top:1px solid #BBBBBB; background-color: #FFF;'>"+
							    "<div class='col-md-12' style='padding:10px'>"+
//								"<h5 class='mt-0'>Asignatura:</h5>"+
								"<p class='card-text' style='font-size:18px; margin: 0.2em 0'><strong>"+valor.asignatura+"</strong></p>"+
								"<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Proyecto: </strong><span class='card-text'>"+valor.proyecto+"</span></p>"+
								"<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Curso: </strong><span class='card-text'>"+valor.nivel+"</span></p>"+
								"<span class='card-text' style='color: red; float:right'> <strong>"+valor.tipo+"</strong> </span>"+
                                                                "<p class='card-text' style='font-size:16px; margin: 0.2em 0'><strong>Precio: </strong><span class='card-text'>"+descuentoTipo(valor.tipo, valor.valor)+"</span></p>"+
							    "</div>"+
							"</div>"+
						    "</div>"+
						"</div>";
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
     * @param {integer} id
     * @param {integer} agregado_estado
     * @returns {html}
     */
    this.agregar = function(id, agregado_estado){
        if(agregado_estado == 0){
            $this.carrito.push(parseInt(id));
            $("#agregado"+id).append('<img id="img'+id+'" src="/smcompra/img/productos/agregado.png" width="70%" style="margin-left:-70%"/>');
            $("#"+id).data("agregado","1");
        }else if(agregado_estado == 1){
            var carrito_modificado = eliminarItemDeCarrito( $this.carrito, id );
            $this.carrito = carrito_modificado;
            $("#img"+id).remove();
            $("#"+id).data("agregado","0");
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
        var productos = params.productos.replace(/['"]+/g, "").split(",");
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
            $("#productos_arr").val(productos);
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
	    url   : "carrito/getProductos",
	    data  : {"id_usuario": id_usuario,
                     "tipo": tipo,
                     "hijos": $this.hijos},
	    success: function(data){
                $.each(data, function(i,val){
                    console.log(i+" - "+val.id_producto);
                    if(val.estado == false){
                        if($this.carrito.indexOf(parseInt(val.id_producto)) > -1){
                            //existe indice no se vuelve a agregar
                        }else{
                            $this.carrito.push(parseInt(val.id_producto));
                            $("#agregado"+val.id_producto).append('<img id="img'+val.id_producto+'" src="/smcompra/img/productos/agregado.png" width="70%" style="margin-left:-70%"/>');
                            $("#"+val.id_producto).data("agregado","1");
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
     * Funcion que elimina un item dentro del arreglo
     * @param {array} arr
     * @param {integer} item
     * @returns {array}
     */
    function eliminarItemDeCarrito( arr, item ) {
        return arr.filter( function( e ) {
            return e !== item;
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
	      url  : "carrito/getAlumno",
	      dataType: 'json',
	      data : {"id" : id},
	      success: function(data){
		  $(".nombre_alumno").text(data.nombre);
		  $("#colegio_alumno").text(data.establecimiento_nombre);
		  $("#curso_alumno").text(data.curso);

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
                            descuento = "<span class='card-text'>$"+valor+"</span><br>";

            break;

            case "Texto":
                            descuento = "<span class='card-text' style='text-decoration: line-through'>$"+valor+"</span><br>"+
                                      "<span class='card-text'>$"+(valor * 0.5)+"<strong> (-50%)</strong></span>";
            break;
        }
        return descuento;
    }
};
