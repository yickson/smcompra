$(document).ready(function(){
    var carrito_compra = new Carrito();
    var usuario = $("#usuario").data("info");
    var tipo    = $("#tipo").data("info");
    //Completar carrito si existe sesion
    
    carrito_compra.cargarProductos(usuario, tipo);
    
    
    $("#alumno_productos").on("click",".producto", function(e){
        
         var prod = $(this).data("prod");
         var alumno = $(this).data("al");
         var agregado_estado = $(this).data("agregado");
         carrito_compra.agregar(prod, alumno, agregado_estado, $(this));

    });
    
    $(".alumno").on("click", function(e){
         var id = $(this).data("rel");
         carrito_compra.alumnos(id);
    });
    
    $("#continuar_comprar").on("click", function(e){
        carrito_compra.carritoVacio()
    });
    
    $('#lienzo').on('click',".todos", function(e){
        carrito_compra.marcarTodos(usuario, tipo)
    });
    
    $('#testRest').on('click', function(e){
        carrito_compra.simulacionRest($(this).data("codigo"))
    });
});
