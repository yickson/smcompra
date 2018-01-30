$(document).ready(function(){
    var table = $('#tabla').DataTable( {
      "ajax": {
               "url": "representante/data_representante",
               "dataType": "json",
               "cache": false,
             },
      dom: 'Bfrtip',
      "buttons": [
         {"extend": 'excel', "text":'<i class="fa fa-download" aria-hidden="true"></i>&nbsp; &nbsp; Exportar Excel',"className": 'btn btn-success pull-center'}
      ],
      "columns": [
          { "data": "rbd" },
	  { "data": "colegio" },
	  { "data": "profesor" },
	  { "data": "alumno" },
	  { "data": "boton" }
      ]
    });
    
    $("#tabla").on("click", ".hijos", function(){
        var profesor = $(this).data("id");
        $.ajax({
            type  : "post",
            cache : false,
            url   : "representante/hijosProductos",
            data  : {"profesor" : profesor},
            success : function(result){
                $('.datos_hijos').find('tr').remove().end();
                $.each(result, function(i,val){
                    var info  = "<tr>"+
                                 "<td style='border-bottom:1px solid #ddd;'>"+ val.alumno_rut + "</td>"+
				 "<td style='border-bottom:1px solid #ddd;'>"+ val.alumno_nombre + "</td>"+
				 "<td style='border-bottom:1px solid #ddd;'>"+ val.producto + "</td>"+
                                 "<td style='border-bottom:1px solid #ddd;'>"+ val.estado + "</td>"+
				"</tr>";
		    $(".datos_hijos").append(info);
                   console.log(val.alumno_id); 
                });
            },
            error: function(){
                console.log("error en lista de hijos productos");
            }
        })
        $('#hijos_usuario').modal('show');
    });
});
