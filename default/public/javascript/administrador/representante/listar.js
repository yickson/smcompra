$(document).ready(function(){
    
    var $this = {"establecimientos" : "", 
                 "profesor"         : "",
                 "estado"           : ""};
    
    /**
     * Constructor
     * @param {string} params
     * @returns {array}
     */
    this.construct = function(params){
        $.extend($this , params);
    };
    
    $('select').selectize({ maxItems: 3 });
    
    $('input').iCheck({
        checkboxClass: 'icheckbox_flat-red',
        radioClass: 'iradio_flat-red'
   });
    var table = $('#tabla').DataTable( {
      "ajax": {
               "type" : "post",
               "url": "representante/data_representante",
               "cache": false,
               "data" : function ( d ) {
                            return {"buscar" : $this};
                        }
             },
      dom: 'Bfrtip',
      "buttons": [],
      "processing": true,
      "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Procesando...</span> '},
      "columns": [
          { "data": "rbd" },
	  { "data": "colegio" },
	  { "data": "profesor" },
          { "data": "profesor_rut" },
	  { "data": "alumno" },
	  { "data": "boton" }
      ]
    });
    
    $("#buscar").on("click", function(){
        $this.establecimientos = $("#establecimientos").val();
        $this.profesor = $("#profesor").val();
        $this.estado = $('input:radio[name=estado]:checked').val();
        setTimeout( function () {
            table.ajax.reload();
        }, 200 );
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
        });
        $('#hijos_usuario').modal('show');
    });
});
