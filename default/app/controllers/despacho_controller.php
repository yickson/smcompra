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

  public function getCompras()
  {
    $rut = Input::post('rut');
    $usuario = (New Usuarios)->find_by_rut($rut);
    if(!empty($usuario)){
      $id = $usuario->id;
      if(empty($id)){
        $this->data = 'No existe el usuario';
      }else{
        $codigos= (New Usuarios)->find_all_by_sql("SELECT wt.buyOrder FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id) WHERE wt.codigoRespuesta = 0 AND u.id = $id");
        Session::set('id', $usuario->id);
        $this->data = $codigos;
      }
    }else{
      $this->data = 'No hay usuario';
    }
    View::select(null, 'json');
  }
  
  public function seguimiento(){
      
  }
}


?>
