<?php

/**
 * Modelo para gestionar los productos asociados a pedidos
 */
class PedidosProductos extends ActiveRecord
{
  public function almacenar($idpedido, $webpay)
  {

    $carro = json_decode($_COOKIE["carritoSM"]); //La sesion es un string
    $tipo = (New Usuarios)->find($_COOKIE["clienteSM"])->tipo;

    foreach ($carro as $key => $valor) {
      $producto = (New Productos)->find($valor[1]); //Encontrar producto
      $productos = New PedidosProductos;
      $texto_model = (New ProfesorAlumnos)->find_by_sql('SELECT * FROM profesor_alumnos WHERE usuario_id ='.$webpay->sessionId.
		                                        ' AND producto_id ='.$valor[1].' AND alumno_id ='.$valor[0]);
      $texto_model->estado = 1;
      $texto_model->save();
      $productos->producto_id = $valor[1];
      $productos->cantidad = $producto->valor;
      $productos->usuario_id = $webpay->sessionId;
      $productos->pedido_id = $idpedido;
      $productos->fecha = date("Y-m-d H:i:s");
      $productos->save();
    }
    if($tipo == 2){
      $producto = (New Productos)->find(418); //Encontrar producto
      $productos = New PedidosProductos;
      $productos->producto_id = 418;
      $productos->cantidad = $producto->valor;
      $productos->usuario_id = $_COOKIE["clienteSM"];
      $productos->pedido_id = $idpedido;
      $productos->fecha = date("Y-m-d H:i:s");
      $productos->save();
    }
  }

  public function  encontrar_pedidos()
  {
    $carro = json_decode($_COOKIE["carritoSM"]);
    $i = 0;
    foreach ($carro as $key => $valor) {
      $productos[$i] = (New PedidosProductos)->find_by_sql("SELECT l.codigo, p.id, p.proyecto, p.nombre, p.valor, p.nivel, u.nombre as usuario FROM licences l INNER JOIN productos p ON (p.id = l.producto_id) INNER JOIN usuarios u ON u.id = l.usuario_id WHERE l.producto_id = $valor[1] AND l.alumno_id = $valor[0] ");
      $i++;
    }

    return $productos;
  }

}


?>
