var Carrito = function(option){

  var $this = {
        carrito    : [],
        sucursales : "",
        canvas     : "",
        legenda    : ""
    };

    /*
     * Constructor
     */
    this.construct = function(options){
        $.extend($this , options);
    };

    this.cargarProductos = function(usuario){
        var id_usuario = usuario;
        var productos_ini  = "";
	var productos_item = "";
	var productos_fin  = "";
	var productos_full = "";
	var productos = [];
	$.ajax({
	    type  : "POST",
	    cache : false,
	    url   : "carrito/getProductos",
	    data  : {"id_usuario": id_usuario},
	    success: function(data){
		//Carga productos
		var hijos = $("#hijos").data("info");
		var cantidad_productos = 0;
		var display = 0;
		var mostrar = "block";
		$.each( hijos, function( key, val ) {
		    productos_ini  = "";
		    productos_item = "";
		    productos_fin  = "";
		    if(display === 0){
			mostrar = "block";
			dataAlumno(val.id);
                        
		    }else{
			mostrar = "none";
		    }
		    productos_ini = "<div id='alumno"+val.id+"' class='cont-productos col-md-12' style='background-color: #FFF; display:"+mostrar+"'>"+
			            "<div class='row'>";
		
		    $.each(data, function(indice, valor){
			if(val.id === valor.id_alumno){
			    productos_item +=   "<div id='"+valor.id_producto+"' class='col-md-4 producto' data-rel='"+valor.id_producto+"' data-agregado='0' style='border: 0px solid'>"+
						    "<div class='col-md-12 img-hover' style='cursor:pointer;'>"+
							"<div class='row'>"+
							    "<div id='agregado"+valor.id_producto+"' class='col-md-6' style='padding:10px'>"+
								"<img src='/smcompra/img/productos/"+valor.img+"' alt='producto' style='width:70%; position: absolute' />"+	
							    "</div>"+
							    "<div class='col-md-6' style='padding:10px'>"+
								"<h5 class='mt-0'>Asignatura:</h5>"+
								"<p class='card-text'>"+valor.asignatura+"</p>"+
								"<h5>Proyecto:</h5>"+
								"<p class='card-text'>"+valor.proyecto+"</p>"+
								"<p><span><b>Curso: </b></span><span class='card-text'>"+valor.nivel+"</span></p>"+
								"<h5>Tipo Material:</h5>"+
								"<span class='card-text' style='color: red;'> <strong>"+valor.tipo+"</strong> </span><br>"+
								"<span class='card-text' style='text-decoration: line-through'>$"+valor.valor+"</span><br>"+
								"<span class='card-text'>$"+(valor.valor * 0.5)+"<strong> (-50%)</strong></span>"+
							    "</div>"+
							"</div>"+
						    "</div>"+
						"</div>";
                            cantidad_productos++;
			}
		    });
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
	    },
	    error: function(data){
                console.log("error");
	    }
	});
    };
    
    this.agregar = function(id, agregado_estado){
        if(agregado_estado == 0){
            $this.carrito.push(id);
            $("#agregado"+id).append('<img id="img'+id+'" src="/smcompra/img/productos/agregado.png" width="70%" style="position:absolute"/>');
            $("#"+id).data("agregado","1");
        }else if(agregado_estado == 1){
            $this.carrito.splice($.inArray(id, $this.carrito),1);
            $("#img"+id).remove();
            $("#"+id).data("agregado","0");
        }
        $("#carrito_txt").text($this.carrito.length);
    };
    
    this.alumnos = function(id){
	    dataAlumno(id);
	    $(".cont-productos").css("display", "none");
	    $("#alumno"+id).css("display", "block");
    }
    
    function dataAlumno(id){
	$.ajax({
	      type : "POST",
	      cache: false,
	      url  : "carrito/getAlumno",
	      dataType: 'json',
	      data : {"id" : id},
	      success: function(data){
		  $("#nombre_alumno").text(data.nombre);
		  $("#colegio_alumno").text(data.establecimiento_nombre);
		  $("#curso_alumno").text(data.curso);
		  
	      },
	      error: function(data){
		  console.log("err");
	      }
	  }); 
       };
};