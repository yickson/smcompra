<?php

/**
 * Librería que retorna el resultado
 */
Load::lib('libwebpay/webpay');
class Webpago
{
  public $urlR = 'https://serviciosm.cl/smcompra/carrito/retorno'; //URL de llamada de Retorno
  public $urlF = 'https://serviciosm.cl/smcompra/carrito/fin'; //URL de vista final segun caso

  public function inicioWebpay()
  {
    //Load::lib('libwebpay/configuration');
    $certificate = Load::lib('libwebpay/cert-normal');
    $configuration = new configuration();
    $configuration->setEnvironment($certificate->environment);
    $configuration->setCommerceCode($certificate->commerce_code);
    $configuration->setPrivateKey($certificate->private_key);
    $configuration->setPublicCert($certificate->public_cert);
    $configuration->setWebpayCert($certificate->webpay_cert);
    $webpay = new Webpay($configuration);
    $amount    = Session::get('total');//10990; //Input::post('total');
    $buyOrder  = Carrito::generarOrden(10); //Generarorden();
    $sessionId = uniqid().rand(0,99999); //Random
    $urlReturn = $this->urlR;
    $urlFinal  = $this->urlF;

    return $webpay->getNormalTransaction()->initTransaction($amount, $buyOrder, $sessionId , $urlReturn, $urlFinal);
  }

  public function retornoWebpay($token)
  {

    //Load::lib('libwebpay/configuration');
    $certificate = Load::lib('libwebpay/cert-normal');
    //Retorno
    $configuration = new configuration();
    $configuration->setEnvironment($certificate->environment);
    $configuration->setCommerceCode($certificate->commerce_code);
    $configuration->setPrivateKey($certificate->private_key);
    $configuration->setPublicCert($certificate->public_cert);
    $configuration->setWebpayCert($certificate->webpay_cert);
    $webpay = new Webpay($configuration);
    return $webpay->getNormalTransaction()->getTransactionResult($token);;
  }
}


?>
