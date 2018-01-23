$(document).ready(function() {
  
   var $this = {
		    "apoderado"    : '1',
		    "profesor"     : '2',
                    "loadmapaOn"   :  $("#loadmap").removeClass("hidden"),
                    "loadmapaOff"  :  $("#loadmap").addClass("hidden")
		};
	
    var table = $('#tabla_usuarios').DataTable( {
      "ajax": {
	       "bServerSide": true,
               "url": "listar_con_hijos",
               "dataType": "json",
               "cache": false,
             },
	dom: 'Bfrtip',
	"buttons": [
	   {"extend": 'excel', "text":'<i class="fa fa-download" aria-hidden="true"></i>&nbsp; &nbsp; Exportar Excel',"className": 'btn btn-success pull-center'}
	],
	"columns": [
	    { "data": "id" },
	    { "data": "rut" },
	    { "data": "nombre" },
	    { "data": "tipo" },
	    { "data": "hijos" }
	]
    });
    
    $("#tabla_usuarios").on("click", ".hijos", function(){
	var usuario = $(this).data("id");
	var tipo = null;
	$.ajax({
	    type: "POST",
	    cache: false,
	    url: "consultarHijos",
	    data: {"usuario" : usuario},
	    success: function(data){
		var cantidad_alumnos = data.length;
		$('.datos_hijos').find('tr').remove().end();
		$('#hijos_usuario').modal('show');
		//Info modal Cabecera
		(data[0].tipo == $this.apoderado)?tipo = "Apoderado": tipo = "Profesor";
		$(".titulo").text("Datos "+tipo+" / alumno(s)");
                $(".tipo").text(tipo);
		$(".nombre_usuario").text(data[0].nombre_usuario);
		$(".rut_usuario").text(data[0].rut_usuario);
		$(".cantidad_alumnos").text( cantidad_alumnos)
		
		$.each(data, function(i, val)
		{
		    //Info modal Alumnos
		    var info  = "<tr>"+
				 "<td style='border-bottom:1px solid #ddd;'>"+ val.nombre_alumno + "</td>"+
				 "<td style='border-bottom:1px solid #ddd;'>"+ val.rut_alumno + "</td>"+
				"</tr>";
		    $(".datos_hijos").append(info);
		});
	    },
	    error: function(){

	    }
	});
    }); 
    
    $("#tabla_usuarios").on("click", ".direccion_usuario", function(){
	var usuario = $(this).data("id");
        $.ajax({
           type  : "POST",
           cache : false,
           url   : "consultarDireccion",
           data  : {"usuario" : usuario},
           beforeSend:function(){
                 $("#loadmap").removeClass("hidden")
            },
           success : function(result){
                $('#direccion_usuario').modal({"show": true});
                var mapa = new Mapa();
                mapa.initMap(result.calle, result.region);
                mapa.setCalle(result.calle);
                mapa.setRegion(result.region);
                mapa.setComuna(result.comuna);
                mapa.setNumero(result.numero);
                mapa.setTipo(result.tipo);
                mapa.setAdicional(result.adicional);
                
                $("#datos_usuario").on("blur", "#calle" , function(){
                    var calle = $(this).val();
                    console.log(calle);
                    $("#loadmap").removeClass("hidden");
                    mapa.recargarMapa(calle, result.region)
                });
                
           },
           error : function(){
               console.log("error en mostrar direccion");
           }
        });
    }); 
    
     
});