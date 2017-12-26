$(document).ready(function(){
    var licencias = $("#licencias").data("licencias");
    var rut = $("#licencias").data("rut");
    console.log(licencias);
    console.log(rut);
    $.ajax({
       type : "post",
       cache: false,
       url  : "https://smconecta.cl/api/usedCode",
       data: {"licencias":licencias,
              "rut" : rut},
       success: function(result){
           
       },
       error: function(){
           
       }
    });
    
});

