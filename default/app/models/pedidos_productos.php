<?php

/**
 * Modelo para gestionar los productos asociados a pedidos
 */
class PedidosProductos extends ActiveRecord
{
  public function almacenar($idpedido)
  {
    $carro = explode(",", Session::get('carrito')); //La sesion es un string
    $licencia = (New Licences)->DesactivarLicencia($carro);
    $texto = (New ProfesorAlumnos)->DesactivarTexto($carro);
    
    foreach ($carro as $key => $valor) {
      $producto = (New Productos)->find($valor); //Encontrar producto

      $productos = New PedidosProductos;
      $productos->producto_id = $valor;
      $productos->cantidad = $producto->valor;
      $productos->usuario_id = Session::get('iduser');
      $productos->pedido_id = $idpedido;
      $productos->fecha = date("Y-m-d H:i:s");
      $productos->save();
    }
  }
}


?>
