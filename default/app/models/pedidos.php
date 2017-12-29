<?php
require_once APP_PATH ."extensions/helpers/datatable_acciones.php";

/**
 * Modelo para gestionar los pedidos
 */
class Pedidos extends ActiveRecord
{
  public function ingresar($idtransaccion)
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
  
  public function getPedidos() 
  {
	$pedidos = $this->find_all_by_sql("SELECT p.id, 
					  (SELECT nombre FROM usuarios WHERE id = p.usuario_id) as nombre, 
					  (SELECT rut FROM usuarios WHERE id = p.usuario_id) as rut,
					   wp.buyOrder , wp.monto, wp.fecha
					   FROM pedidos as p
					   INNER JOIN webpay_transaccion wp ON (p.transaccion_id = wp.id and codigoRespuesta = 0)");
	//formatear no mas
	$pedidos_format = array();
	foreach($pedidos as $k => $pedido):
	    $pedidos_format[$k]["id"] = $pedido->id;
	    $pedidos_format[$k]["nombre"] = $pedido->nombre;
	    $pedidos_format[$k]["rut"] = $pedido->rut;
	    $pedidos_format[$k]["buyOrder"] = $pedido->buyOrder;
	    $pedidos_format[$k]["monto"] = $pedido->monto;
	    $pedidos_format[$k]["fecha"] = $pedido->fecha;
	    $pedidos_format[$k]["boton"] = datatableAcciones::getBtnPedidos();
	endforeach;
	return $pedidos_format;
  }
}


?>
