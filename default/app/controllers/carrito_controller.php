<?php
require_once APP_PATH ."extensions/helpers/datatable_acciones.php";
Load::lib('libwebpay/webpay');

/**
 * Controlador para gestionar el carrito de compra
 */
class CarritoController extends AppController
{
  public $errorpay;
  public $mensaje;
  function before_filter()
  {
    View::template('carrito');
  }
  public function index()
  {
    //Esta vista no debería cargar nada
    $alumnos = Input::post('alumno');
    $l = new Alumnos;
    $datos = (New Alumnos)->verificar($alumnos);
    foreach ($alumnos as $key => $valor) {
      $alumno[] = (New Alumnos)->find_by_rut($l->verificador($valor['rut'])); //Metodo para verificar un vacío
    }

    $this->usuario = Session::get('iduser');
    $this->tipo = Session::get('tipo');
    $this->alumno = $alumno;
  }

  /**
   * Retorna información del alumno
   * @param  $id post(int)
   * @return json
   **/
  public function getAlumno(){
      $id = $_POST["id"];
      $alumno = (new Alumnos)->find($id);
      $nombre_establecimiento = (new Establecimientos)->getNombreById($alumno->establecimiento_id);
      $alumno->establecimiento_nombre = $nombre_establecimiento;
      $this->data = $alumno;
      View::select(null,"json");
  }

  /**
   * Retorna Pproductos de los hijos del profesor
   * @param  $id post(int)
   * @return json
   **/
  public function getProductos(){
      $productos = New Productos();
      $alumnos_productos  = $productos->getProductosByUsuario();
      $this->data = $alumnos_productos;
      View::select(null,"json");
  }

  public function comprar(){
    $productos_arr = $_POST["productos_arr"];
    $this->arr = $productos_arr;
    $this->tipo = $tipo;
  }

  public function dataTableListarCarrito(){
    $carrito = New Carrito();
    $productos = $carrito->getListaProductos();
    $this->data  = $productos;
    View::select( null , 'json_carrito' );
  }

  public function datatableValidarPago(){
    $carrito = New Carrito();
    $total = $carrito->getTotalByTipoUsuario();
    $total = $carrito->valorDespacho($total);
    $this->total  = $total;
    View::select( null , 'json_carrito' );
  }

  public function pasarela()
  {

    $certificate = Load::lib('libwebpay/cert-normal');
    //Carro de compra
    $configuration = new configuration();
    $configuration->setEnvironment($certificate->environment);
    $configuration->setCommerceCode($certificate->commerce_code);
    $configuration->setPrivateKey($certificate->private_key);
    $configuration->setPublicCert($certificate->public_cert);
    $configuration->setWebpayCert($certificate->webpay_cert);
    //var_dump($configuration);
    $webpay = new Webpay($configuration);

    $amount    = Session::get('total');//10990; //Input::post('total');
    $buyOrder  = Carrito::generarOrden(10); //Generarorden();
    $sessionId = uniqid().rand(0,99999); //Random
    $urlReturn = 'http://localhost/smcompras/carrito/retorno';
    $urlFinal  = 'http://localhost/smcompras/carrito/fin';

    $this->result = $webpay->getNormalTransaction()->initTransaction($amount, $buyOrder, $sessionId , $urlReturn, $urlFinal);
    ///var_dump($this->result, $buyOrder);
    View::template(null);
  }

  public function retorno()
  {
    //Load::lib('libwebpay/webpay');
    $certificate = Load::lib('libwebpay/cert-normal');
    //Retorno
    $configuration = new configuration();
    $configuration->setEnvironment($certificate->environment);
    $configuration->setCommerceCode($certificate->commerce_code);
    $configuration->setPrivateKey($certificate->private_key);
    $configuration->setPublicCert($certificate->public_cert);
    $configuration->setWebpayCert($certificate->webpay_cert);

    $this->token = $_POST['token_ws'];

    $webpay = new Webpay($configuration);
    try {
      $this->result = $webpay->getNormalTransaction()->getTransactionResult($this->token);
      //var_dump($this->result->detailOutput->responseCode);
      if($this->result->detailOutput->responseCode != 0) {
        Redirect::to('carrito/error');
        //var_dump($this->result->detailOutput->responseCode, $this->errorpay);
      }else{
        View::template(null);
      }
    }
    catch(Exception $ex) {
      die('Error inesperado en transkbank: ' . $ex->getMessage());
    }
    //View::select(null, null);
  }

  public function fin()
  {
    //Final
    $this->token = $_POST['token_ws'];

    if($this->token == '' or $this->token == null){
      $this->mensaje = true;
    }
    //var_dump($this->token, $this->mensaje);
    //View::select(null, null);
  }

  public function error()
  {
    //Vista para los errores de WebPay
  }

  public function prueba()
  {
    $total = Input::post('total');
    Session::set('total', $total);
    $this->data = true;
    View::select(null, 'json');
  }
}

?>
