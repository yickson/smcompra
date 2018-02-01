<?php

/**
 * Modelo para gestionar los representantes
 */
class Representantes extends ActiveRecord
{
  
    public function getFullData(){
	$representante = (new RepresentanteZona)->find_by_representante(Session::get('id', 'administrador'));
	$sql = $this->find_all_by_sql("SELECT p.id, 
					  (SELECT nombre FROM usuarios WHERE id = p.usuario_id) as nombre, 
					  (SELECT rut FROM usuarios WHERE id = p.usuario_id) as rut,
					   wp.buyOrder , wp.monto, wp.fecha
					   FROM pedidos as p
					   INNER JOIN webpay_transaccion wp ON (p.transaccion_id = wp.id and codigoRespuesta = 0)");
	return $sql;
    }
}


?>
