$(document).ready(function(){
    var carrito_compra = new Carrito();
    var usuario = $("#usuario").data("info");
    var tipo    = $("#tipo").data("info");
    //Completar carrito si existe sesion
    var carrito = $("#carrito").data("info").replace(/['"]+/g, '').split(',');
    console.log(carrito);
    if(carrito != ""){
        $.each(carrito, function(i,id){
            //$('div.producto').trigger("click");
            carrito_compra.agregar(id,0);
        });
    }
    carrito_compra.cargarProductos(usuario, tipo);
    
    $("#alumno_productos").on("click",".producto", function(e){
         var id = $(this).data("rel");
         var agregado_estado = $(this).data("agregado");
         carrito_compra.agregar(id, agregado_estado);

    });
    
    $(".alumno").on("click",function(e){
         var id = $(this).data("rel");
         carrito_compra.alumnos(id);
    });
    
    $("#continuar_comprar").on("click",function(e){
        var productos = carrito_compra.almacenCarrito();
        $("#productos_arr").val(productos);
        $("#tipo_usuario").val(tipo);
        $("#productos_submit").trigger("click");
    });
});
