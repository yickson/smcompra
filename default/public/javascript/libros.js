var Libros = function(params){
    var $this = {
        "public" : "c779ebff261ac661bb83a0fa1c22dbbf81143d7c1e1864bc7a71b9096cd87664",
        "hash"   : "e5d8d0893cbc68ef7a53ac8f96c86476677253c446ba77979cb6f3dbaf744410",
        "libros" : params
    }
    
    /**
     * Constructor
     * @param {string} params
     * @returns {array}
     */
    this.construct = function(params){
        $.extend($this , params);
    };
    
    //Consume servicio rest de generacion de licencias, devuelve licencias por cada producto de alumno en session
    $.ajax({
        type  : "post",
        cache : false,
        url   : "https://www.smconecta.cl/API/codes/create",
        crossDomain : true,
        beforeSend: function (xhr) {
        /* Authorization header */
            xhr.setRequestHeader("X-Public", $this.public);
            xhr.setRequestHeader("X-Hash", $this.hash);
        },
        data:{"libros": $this.libros},
        success: function(result){
            console.log(result);
            console.log("Conexion realizada con exito");
            $.ajax({
                type  : "post",
                cache : false,
                url   : "setLicencias",
                data  : {"data" : result},
                success: function(resultado){
                    console.log("exito en el ingreso dinamico de licencias");
                    console.log(resultado);
                    var rest_licencias = new Licencias();
                },
                error: function(){
                    console.log("error con el ingreso de licencias");
                }
            });
        },
        error: function(){
            console.log("error en consumo rest Libros");
        }
    });
};
