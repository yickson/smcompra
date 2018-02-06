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
}


?>
