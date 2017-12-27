$(document).ready(function(){
    var licencias = $("#licencias").data("licencias");
    var rut = $("#licencias").data("rut");
    console.log(licencias);
    console.log(rut);
    $.ajax({
        type : "post",
        cache: false,
        url  : "https://www.smconecta.cl/API/usedCodes/create",
        crossDomain: true,
        beforeSend: function (xhr) {
         /* Authorization header */
         xhr.setRequestHeader("X-Public", "c779ebff261ac661bb83a0fa1c22dbbf81143d7c1e1864bc7a71b9096cd87664");
         xhr.setRequestHeader("X-Hash", "e5d8d0893cbc68ef7a53ac8f96c86476677253c446ba77979cb6f3dbaf744410");
        },
        data: {"licencias":licencias,
               "rut" : rut},
        success: function(result){
            console.log("CORRECTO");
            console.log(result);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
                     console.log(errorThrown);
        },
    });
    
});

