$(document).ready(function(){
    var carrito_compra = new Carrito();
    var usuario = $("#usuario").data("info");
    var tipo    = $("#tipo").data("info");
    //Completar carrito si existe sesion
    var carrito = $("#carrito").data("info").replace(/['"]+/g, '').split(',');
    if(carrito != ""){
        $.each(carrito, function(i,id){
            carrito_compra.agregar(id,0);
        });
    }
    carrito_compra.cargarProductos(usuario, tipo);
    
    $("#alumno_productos").on("click",".producto", function(e){
         var id = $(this).data("rel");
         var agregado_estado = $(this).data("agregado");
         carrito_compra.agregar(id, agregado_estado);

    });
    
    $(".alumno").on("click", function(e){
         var id = $(this).data("rel");
         carrito_compra.alumnos(id);
    });
    
    $("#continuar_comprar").on("click", function(e){
        carrito_compra.carritoVacio()
    });
    
    $('#lienzo').on('click',".todos", function(e){
        console.log("pase");
        carrito_compra.marcarTodos(usuario, tipo)
    });
});
