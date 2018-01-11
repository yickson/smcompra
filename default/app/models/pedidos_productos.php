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

  public function encontrar_pedidos()
  {
    $carro = json_decode(Session::get('carrito'));
    $i = 0;
    foreach ($carro as $key => $valor) {
      $productos[$i] = (New PedidosProductos)->find_by_sql("SELECT l.codigo, p.id, p.proyecto, p.nombre, p.valor, p.nivel FROM licences l, productos p WHERE l.producto_id = p.id AND l.producto_id = $valor[1] AND l.alumno_id = $valor[0]");
      $i++;
    }

    return $productos;
  }
}


?>
