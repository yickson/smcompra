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
    $despacho = (New WebpayTransaccion)->find_by_sql("SELECT COUNT(*) as transaccion FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id AND u.tipo = 2) WHERE wt.codigoRespuesta = 0");
    $totaldespacho = ($despacho->transaccion * 3090);
    $this->despacho = $totaldespacho;
    //Ingresos por Licencia y textos
    $licencias = (New WebpayTransaccion)->find_by_sql("SELECT SUM(monto) as total FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id AND u.tipo = 1) WHERE wt.codigoRespuesta = 0");
    $this->licencias = $licencias->total;
    $textos = (New WebpayTransaccion)->find_by_sql("SELECT SUM(monto) as total FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id AND u.tipo = 2) WHERE wt.codigoRespuesta = 0");
    $this->textos = $textos->total;

    //Licencias totales
    $globalLicencias = (New Usuarios)->find_by_sql("SELECT COUNT(*) as globalLic FROM alumnos a INNER JOIN establecimientos e ON (e.id = a.establecimiento_id) INNER JOIN establecimiento_proyecto ep ON (ep.rbd = e.rbd AND ep.curso_id = a.curso)");
    $this->licenciaGlobal = $globalLicencias->globalLic;
    $licVenta = (New Licences)->find_by_sql("SELECT COUNT(*) as licVendidas FROM licences l WHERE l.estado = 1 ");
    $this->licVenta = $licVenta->licVendidas;

    //Textos totales
    $globalTextos = (New Usuarios)->find_by_sql("SELECT COUNT(*) as textos FROM profesor_alumnos pa");
    $this->textoGlobal = $globalTextos->textos;
    $texVenta = (New ProfesorAlumnos)->find_by_sql("SELECT COUNT(*) as texVendidos FROM profesor_alumnos pa WHERE pa.estado = 1 ");
    $this->texVenta = $texVenta->texVendidos;

    //Totales por vender
    $globalTotalLic = (New Usuarios)->find_by_sql("SELECT SUM(p.valor) as totalGlobalLic FROM alumnos a INNER JOIN establecimientos e ON (e.id = a.establecimiento_id) INNER JOIN establecimiento_proyecto ep ON (ep.rbd = e.rbd AND ep.curso_id = a.curso) INNER JOIN productos p ON (p.id = ep.producto_id)");
    $this->globalTotalLic = $globalTotalLic->totalGlobalLic;
    $globalTotalTex = (New ProfesorAlumnos)->find_by_sql("SELECT SUM(p.valor) as textos FROM profesor_alumnos pa INNER JOIN productos p ON (p.id = pa.producto_id)");
    $this->globalTotalTex = $globalTotalTex->textos;
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

  public function licPorcentaje()
  {
    $webpay = (New WebpayTransaccion)->licPorc();
    $this->data = $webpay;
    View::select(null, 'json');
  }

  public function texPorcentaje()
  {
    $webpay = (New WebpayTransaccion)->texPorc();
    $this->data = $webpay;
    View::select(null, 'json');
  }
}


?>
