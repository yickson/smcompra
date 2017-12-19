<?php

/**
 * Modelo para gestionar los productos asociados a pedidos
 */
class PedidosProductos extends ActiveRecord
{
  public function almacenar($carro, $idpedido)
  {
    foreach ($carro as $key => $valor) {
      $producto[] = (New Productos)->find($valor);
    }
    foreach ($producto as $key => $valor) {
      $productos = New PedidosProductos;
      $productos->producto_id = $valor->producto_id;
      $productos->valor = $valor->valor;
      $productos->usuario_id = Session::get('iduser');
      $productos->pedido_id = $idpedido;
      $productos->fecha = date("Y-m-d H:i:s");
      $productos->save();
    }
  }
}


?>
