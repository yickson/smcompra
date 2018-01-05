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
    if(!$valido->logged()){
      Redirect::to("administrador/index/entrar");
    }
  }

  public function index()
  {

  }

  public function listar_operaciones()
  {
    $webpay = (New WebpayTransaccion)->find();
    $this->data = $webpay;
    View::select(null, 'json');
  }
}


?>
