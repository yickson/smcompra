<?php

/**
 * Estadisticas Controller
 */
class EstadisticasController extends AppController
{

  function before_filter()
  {
    View::template('admin');
  }

  public function index()
  {
    //Vista inicial con todas las graficas
  }

  public function compras()
  {
    //Vista para ver todas las compras y sumatorias
  }

  //Método para las estadisticas de la plataforma

  public function getUsuarios()
  {
    $datos = (New Usuarios)->find_all_by_sql("SELECT IF(tipo = 1, 'apoderado', 'profesor') as tipo, COUNT(nombre) as cantidad FROM usuarios GROUP BY tipo");
    $this->data = $datos;
    View::select(null, 'json');
  }

  public function getComprasByUsuario()
  {
    $datos = (New Usuarios)->find_all_by_sql("SELECT IF(u.tipo = 1, 'apoderado', 'profesor') as tipo, COUNT(u.tipo) as cantidad FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id) WHERE wt.codigoRespuesta = 0 GROUP BY u.tipo ");
    $this->data = $datos;
    View::select(null, 'json');
  }

  public function compra_mes()
  {
    $webpay = (New WebpayTransaccion)->compra_mes();
    $this->data = $webpay;
    View::select(null, 'json');
  }

  public function compra_dias()
  {
    $webpay = (New WebpayTransaccion)->compra_dias();
    $this->data = $webpay;
    View::select(null, 'json');
  }

  public function compra_semana()
  {
    $webpay = (New WebpayTransaccion)->compra_semana();
    $this->data = $webpay;
    View::select(null, 'json');
  }
}


?>
