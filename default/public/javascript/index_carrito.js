$(document).ready(function(){
    var carrito = [];
    var  carrito_compra = new Carrito();
    var usuario = $("#usuario").data("info");
    var tipo    = $("#tipo").data("info");
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
});
