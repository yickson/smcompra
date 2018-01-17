$(document).ready(function() {
   var $this = {
		    "apoderado"  : '1',
		    "profesor"   : '2'
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
		$(".nombre_usuario").text(data[0].nombre_usuario);
		$(".rut_usuario").text(data[0].rut_usuario);
		$(".cantidad_alumnos").text(cantidad_alumnos)
		
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
});