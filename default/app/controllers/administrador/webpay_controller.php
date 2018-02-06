<?php

/**
 * Controller
 */
class WebpayController extends AppController
{

  function before_filter()
  {
    View::template('admin');
    $valido = New Administrador;
    /**if(!$valido->logged()){
      Redirect::to("administrador/index/entrar");
    }**/
  }

  public function index()
  {

  }

  public function licencias()
  {

  }

  public function productos()
  {

  }

  //Métodos para AJAX

  public function listar_operaciones()
  {
    $webpay = (New WebpayTransaccion)->find();
    $this->data = $webpay;
    View::select(null, 'json');
  }

  public function compras_licencias()
  {
    $webpay = (New WebpayTransaccion)->licencias();
    $this->data = $webpay;
    View::select(null, 'json');
  }

  public function compras_productos()
  {
    $webpay = (New WebpayTransaccion)->find();
    $this->data = $webpay;
    View::select(null, 'json');
  }
}


?>
