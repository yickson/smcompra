<?php
require_once APP_PATH ."extensions/helpers/datatable_acciones.php";
//Load::lib('libwebpay/webpay');

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
    Session::set('total', $total);
    $data["tipo"]  = Session::get('tipo');
    $data["total"] = $total;
    $this->data   = $data;
    View::select( null , 'json_carrito' );
  }

  public function pasarela()
  {
    Load::lib('webpago');
    $webpay = New Webpago;
    $this->result = $webpay->inicioWebpay();
    View::template(null);
  }

  public function retorno()
  {
    Load::lib('webpago');
    $webpay = New Webpago;
    $this->token = $_POST['token_ws'];
    //$this->result = $webpay->retornoWebpay($this->token);
    //var_dump($this->result->detailOutput->sharesNumber, $this->result->detailOutput->responseCode, $this->result->detailOutput->buyOrder); die();
    //$this->result->cardDetail->cardNumber;
    //$this->result->detailOutput->paymentTypeCode;
    //$this->result->detailOutput->amount;
    //$this->result->detailOutput->sharesNumber;
    //$this->result->detailOutput->buyOrder;
    try {
      $this->result = $webpay->retornoWebpay($this->token);
      if($this->result->detailOutput->responseCode != 0) {
        $trans = (New WebpayTransaccion)->ingresar($this->result);
        Redirect::to('carrito/error');
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
  }

  public function error()
  {
    //Vista para los errores de WebPay
  }

  public function consultarDireccion()
  {
      $direccion = (New Direcciones)->getDireccion();
      $comuna_nombre = (New Comunas)->getNombre($direccion->id_comuna);
      $region_nombre = (New Regiones)->getNombre($direccion->id_region);
      $direccion->nombre_comuna = $comuna_nombre;
      $direccion->nombre_region = $region_nombre;
      $this->data = $direccion;
      View::select(null,"json");
  }
}

?>
