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
<<<<<<< HEAD
    $l = New Alumnos;
=======
    //print_r($alumnos);die();
    $l = new Alumnos;
>>>>>>> 3ec89cf7e1bdc1590085c893cad4ebb3dcb14128
    $datos = (New Alumnos)->verificar($alumnos);
    foreach ($alumnos as $key => $valor) {
      $alumno[] = (New Alumnos)->find_by_rut($l->verificador($valor['rut'])); //Metodo para verificar un vacío
    }

    $this->usuario = Session::get('iduser');
    $this->tipo = Session::get('tipo');
    $this->alumno = $alumno;
<<<<<<< HEAD
=======
    //View::select(null, null);
>>>>>>> 3ec89cf7e1bdc1590085c893cad4ebb3dcb14128
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
      $id_usuario = $_POST["id_usuario"];
      $tipo = $_POST["tipo"];
      $alumnos = "";

      switch($tipo):
	  case 1: //Apoderado
	        $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
							  (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, li.alumno_id as id_alumno
							   FROM productos as p
							   INNER JOIN licences li ON (li.producto_id = p.id and li.usuario_id = $id_usuario)
							   ORDER BY li.alumno_id ASC");
	  break;
	  case 2: //Profesor
	        $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
							   (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, pa.alumno_id as id_alumno
							    FROM productos as p
							    INNER JOIN profesor_alumnos pa ON (pa.producto_id = p.id and pa.usuario_id = $id_usuario)
							    ORDER BY pa.alumno_id ASC");
	  break;
      endswitch;

      $this->data = $alumnos;
      View::select(null,"json");
  }

  public function comprar(){
    $productos_arr = $_POST["productos_arr"];
    $this->arr = $productos_arr;
    $this->tipo = $tipo;
  }
<<<<<<< HEAD

  public function dataTableComprar(){
=======
  
  public function dataTableListarCarrito(){
>>>>>>> 3ec89cf7e1bdc1590085c893cad4ebb3dcb14128
    $productos_sql = new Productos;
    $productos = array();
    $result = null;
    $productos_format = array();
    $total_format = null;
    $productos_arr = explode(",", $_POST["arr"]);
    $i=0;
    foreach($productos_arr as $producto):
	$result = $productos_sql->find($producto);
	$productos_format[$i]["imagen"] = datatableAcciones::getImagen($result->imagen);
	$productos_format[$i]["descripcion"] = $result->descripcion;
	$productos_format[$i]["cantidad"] = 1;
	//if($tipo_usuario == 1){
	switch(Session::get('tipo')):
	    case 1:
		    $productos_format[$i]["total"] = $result->valor;
	    break;
	    case 2:
		    $productos_format[$i]["total"] = $result->valor * 0.5;
	    break;
	endswitch;
	$productos_format[$i]["boton"] = datatableAcciones::getBtnCarrito($result->id);
<<<<<<< HEAD
	$total_format += $result->valor;

=======
	$total_format += $productos_format[$i]["total"];
>>>>>>> 3ec89cf7e1bdc1590085c893cad4ebb3dcb14128
	$i++;
    endforeach;

    $subtotal = round($total_format / 1.19);
    $iva = round($subtotal * 0.19);
    $iva = number_format($iva, 0 , ' , ' ,  '.');
    $total = number_format($total_format, 0 , ' , ' ,  '.');
    $pagar = "";
    $productos["data"] = datatableAcciones::getTotal($i, $productos_format, $subtotal, $iva, $total);
    $productos["total"] = $total_format;
    $this->data  = $productos;
    View::select( null , 'json_carrito' );
  }
  
  public function datatableValidarPago(){
    $productos_sql = new Productos;
    $result = null;
    $total_format = null;
    $productos_arr = explode(",", $_POST["arr"]);
    foreach($productos_arr as $producto):
	$result = $productos_sql->find($producto);
	switch(Session::get('tipo')):
	    case 1:
		   $total_format += $result->valor;
	    break;
	    case 2:
		   $total_format += $result->valor * 0.5;
	    break;
	endswitch;
    endforeach;
    $total = $total_format;
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

    $amount    = 10990; //Input::post('total');
    $buyOrder  = 1234567; //Generarorden();
    $sessionId = uniqid().rand(0,99999); //Random
    $urlReturn = 'http://localhost/smcompras/carrito/retorno';
    $urlFinal  = 'http://localhost/smcompras/carrito/final';

    $this->result = $webpay->getNormalTransaction()->initTransaction($amount, $buyOrder, $sessionId , $urlReturn, $urlFinal);
    //var_dump($this->result);
    //View::select(null, null);
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
      }
    }
    catch(Exception $ex) {
      die('Error inesperado en transkbank: ' . $ex->getMessage());
    }
    //View::select(null, null);
  }

  public function final()
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
}

?>
