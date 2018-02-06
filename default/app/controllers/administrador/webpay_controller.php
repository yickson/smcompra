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

  public function ingresos()
  {
    //Ventas totales, despacho
    $this->ventatotal = (New WebpayTransaccion)->find_by_sql("SELECT SUM(monto) as venta FROM webpay_transaccion WHERE codigoRespuesta = 0 ");
    $despacho = (New WebpayTransaccion)->find_by_sql("SELECT COUNT(*) as transaccion FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id AND u.tipo = 2)");
    $totaldespacho = ($despacho->transaccion * 3090);
    $this->despacho = $totaldespacho;
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
