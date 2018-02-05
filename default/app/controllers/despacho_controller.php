<?php

/**
 * Controller para la gestión de despacho para los profesores
 */
class DespachoController extends AppController
{

  function before_filter()
  {
    View::template('main');
  }

  public function index()
  {

  }

  //Metodos para llamados AJAX

  public function getBuyOrder()
  {
    $dato = Input::post('');
    $usuario = (New Usuarios)->find_all_by_sql("SELECT wt.buyOrder FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id) WHERE wt.codigoRespuesta = 0 AND u.id = $id");
    $this->data = '';
    View::select(null, 'json');
  }
  
  public function seguimiento(){
      
  }
}


?>
