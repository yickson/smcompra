<?php

/**
 * Modelo que almacena todas las compras
 */
class WebpayTransaccion extends ActiveRecord
{
  public function ingresar($pago)
  {
    //Metodo para ingresar la transaccion
    $transaccion = New WebpayTransaccion;
    $transaccion->usuario_id = $_COOKIE["clienteSM"];
    $transaccion->buyOrder = $pago->detailOutput->buyOrder;
    $transaccion->cardNumber = $pago->cardDetail->cardNumber;
    $transaccion->cuotas = $pago->detailOutput->sharesNumber;
    $transaccion->tipoPago = $pago->detailOutput->paymentTypeCode;
    $transaccion->codigoRespuesta = $pago->detailOutput->responseCode;
    $transaccion->monto = $pago->detailOutput->amount;
    $transaccion->fecha = date("Y-m-d H:i:s");
    $transaccion->VCI = $pago->VCI;

    if($transaccion->save()){
      return $transaccion->id;
    }
    else{
      return false;
    }
  }

  public function anulado()
  {
    //Metodo para ingresar la transaccion
    $transaccion = New WebpayTransaccion;
    $transaccion->usuario_id = $_COOKIE["clienteSM"];
    $transaccion->buyOrder = 'SM0001112223';
    $transaccion->cardNumber = '1111';
    $transaccion->cuotas = '';
    $transaccion->tipoPago = '';
    $transaccion->codigoRespuesta = '-9';
    $transaccion->monto = $_COOKIE["montoSM"];
    $transaccion->fecha = date("Y-m-d H:i:s");
    $transaccion->VCI = 'ANUL';

    if($transaccion->save()){
      return $transaccion->id;
    }
    else{
      return false;
    }
  }

  public function licencias()
  {
    $datos = (New WebpayTransaccion)->find_all_by_sql("SELECT wt.* FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id AND u.tipo = 1) WHERE wt.codigoRespuesta = 0 ");
    return $datos;
  }

  public function productos()
  {
    $datos = (New WebpayTransaccion)->find_all_by_sql("SELECT wt.* FROM webpay_transaccion wt INNER JOIN usuarios u ON (u.id = wt.usuario_id AND u.tipo = 2) WHERE wt.codigoRespuesta = 0 ");
    return $datos;
  }

  public function compra_mes()
  {
    $datos = (New WebpayTransaccion)->find_all_by_sql("SELECT MONTH(wt.fecha) as mes, COUNT(*) as compras FROM webpay_transaccion wt WHERE wt.codigoRespuesta = 0 GROUP BY MONTH(wt.fecha)");
    return $datos;
  }

  public function compra_dias()
  {
    $datos = (New WebpayTransaccion)->find_all_by_sql("SELECT DAY(wt.fecha) as dia, COUNT(*) as compras FROM webpay_transaccion wt WHERE wt.codigoRespuesta = 0 GROUP BY DAY(wt.fecha)");
    return $datos;
  }

  public function compra_semana()
  {
    $datos = (New WebpayTransaccion)->find_all_by_sql("SELECT DAYOFWEEK(wt.fecha) as dia, COUNT(*) as compras FROM webpay_transaccion wt WHERE wt.codigoRespuesta = 0 GROUP BY DAYOFWEEK(wt.fecha)");
    return $datos;
  }

  //Metodo para las tortas de porcentaje en licencia y Textos

  public function licPorc()
  {
    $datos = array();
    $globalLicencias = (New Usuarios)->find_by_sql("SELECT COUNT(*) as globalLic FROM alumnos a INNER JOIN establecimientos e ON (e.id = a.establecimiento_id) INNER JOIN establecimiento_proyecto ep ON (ep.rbd = e.rbd AND ep.curso_id = a.curso)");
    $licVenta = (New Licences)->find_by_sql("SELECT COUNT(*) as licVendidas FROM licences l WHERE l.estado = 1 ");
    $diferencia = $globalLicencias->globalLic - $licVenta->licVendidas;
    $datos[] = round(($diferencia/$globalLicencias->globalLic)*100, 2);
    $datos[] = round(($licVenta->licVendidas/$globalLicencias->globalLic)*100, 2);
    return $datos;
  }

  public function texPorc()
  {
    $datos = array();
    $globalTextos = (New Usuarios)->find_by_sql("SELECT COUNT(*) as textos FROM profesor_alumnos pa");
    $texVenta = (New ProfesorAlumnos)->find_by_sql("SELECT COUNT(*) as texVendidos FROM profesor_alumnos pa WHERE pa.estado = 1 ");
    $diferencia = $globalTextos->textos - $texVenta->texVendidos;
    $datos[] = round(($diferencia/$globalTextos->textos)*100, 2);
    $datos[] = round(($texVenta->texVendidos/$globalTextos->textos), 2);
    return $datos;
  }
}


?>
