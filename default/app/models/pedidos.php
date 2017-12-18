<?php

/**
 * Modelo para gestionar los pedidos
 */
class Pedidos extends ActiveRecord
{
  public function ingresar($pedido, $idtransaccion)
  {
    //Datos del pedido
    $datos = New Pedidos;
    $datos->usuario_id = Session::get('iduser');
    $datos->transaccion_id = $idtransaccion;
    $datos->total = Session::get('total');
    $datos->fecha = date("Y-m-d H:i:s");

    if($datos->save()){
      return $datos->id;
    }
    else{
      return false;
    }
  }
}


?>
