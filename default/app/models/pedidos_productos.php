<?php

/**
 * Modelo para gestionar los productos asociados a pedidos
 */
class PedidosProductos extends ActiveRecord
{
  public function almacenar($idpedido)
  {

    $carro = json_decode(Session::get('carrito')); //La sesion es un string

    foreach ($carro as $key => $valor) {
      $producto = (New Productos)->find($valor[1]); //Encontrar producto

      $productos = New PedidosProductos;
      $productos->producto_id = $valor[1];
      $productos->cantidad = $producto->valor;
      $productos->usuario_id = Session::get('iduser');
      $productos->pedido_id = $idpedido;
      $productos->fecha = date("Y-m-d H:i:s");
      $productos->save();
    }
  }
}


?>
