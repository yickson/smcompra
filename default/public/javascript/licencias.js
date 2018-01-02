var Licencias = function(params){
    var $this = {
        "alumnos" : [],
        "licencias" : []
    }
    
    /**
     * Constructor
     * @param {string} params
     * @returns {array}
     */
    this.construct = function(params){
        $.extend($this , params);
    };
    
    //Servicio consumo Rest para cambiar estado de licencias a pagadas.
    $.ajax({
        type : "post",
        cache: false,
        url  : "getRutAlumnos",
        success: function(result){
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
                data: {"licencias": result},
                success: function(result){
                    console.log(result);
                    console.log("exito en el cambio de estado de licencia");
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
            });
        },
        error: function(){
            console.log("error en consumo rest Licencias");;
        }
    });
};

