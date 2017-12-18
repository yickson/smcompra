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
    $transaccion->usuario_id = Session::get('iduser');
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
}


?>
