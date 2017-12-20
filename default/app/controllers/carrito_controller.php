<?php
require_once APP_PATH ."extensions/helpers/datatable_acciones.php";
//Load::lib('libwebpay/webpay');

/**
 * Controlador para gestionar el carrito de compra
 */
class CarritoController extends AppController
{
  //Constantes
  const STEP_3 = array("etapa" => 3);
  const STEP_4 = array("etapa" => 4);
  const STEP_5 = array("etapa" => 5);

  //publicas
  public $errorpay;
  public $mensaje;

  function before_filter()
  {
    View::template('carrito');
  }
  public function index()
  {
    //Esta vista no debería cargar nada
	if(Input::post('alumno')){
	    $alumnos = Input::post('alumno');
	    $l = new Alumnos;
	    $datos = (New Alumnos)->verificar($alumnos);
	    foreach ($alumnos as $key => $valor) {
	      $alumno[] = (New Alumnos)->find_by_rut($l->verificador($valor['rut'])); //Metodo para verificar un vacío
	    }
	    Session::set('alumno', $alumno );
	}


    $this->step    = $this::STEP_3;
    $this->usuario = Session::get('iduser');
    $this->tipo    = Session::get('tipo');
    $this->alumno  = Session::get('alumno');
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
      Session::set("hijos", $_POST["hijos"]);
      $alumnos_productos  = $productos->getProductosByUsuario();
      $this->data = $alumnos_productos;
      View::select(null,"json");
  }

  public function comprar(){
    Session::set("carrito", $_POST["productos_arr"]);
    $this->step = $this::STEP_4;
    $this->arr  =  Session::get("carrito");
    $this->tipo = $tipo;
  }

  public function dataTableListarCarrito(){
    $carrito = New Carrito();
    $productos = $carrito->getListaProductos();
    $this->data  = $productos;
    View::select( null , 'json_carrito' );
  }

  public function datatableValidarPago(){
    $datos_direccion = Input::post("datos_direccion");
    $usuario = (New Usuarios)->getDireccion();
    if($usuario != null){
	if($usuario->actualizarDireccion($usuario, $datos_direccion)){
	    $carrito = New Carrito();
	    $total = $carrito->getTotalByTipoUsuario();
	    $total = $carrito->valorDespacho($total);
	    Session::set('total', $total);
	    $data["tipo"]  = Session::get('tipo');
	    $data["total"] = $total;
	    $this->data   = $data;
	}else{
	    $this->data   = null;
	}
    }else{
	    $carrito = New Carrito();
	    $total = $carrito->getTotalByTipoUsuario();
	    $total = $carrito->valorDespacho($total);
	    Session::set('total', $total);
	    $data["tipo"]  = Session::get('tipo');
	    $data["total"] = $total;
	    $this->data   = $data;
    }
    View::select( null , null );
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
    try {
      $this->result = $webpay->retornoWebpay($this->token);
      if($this->result->detailOutput->responseCode != 0) {
        $transaccion = (New WebpayTransaccion)->ingresar($this->result);
        Redirect::to('carrito/error');
      }else{
        $transaccion = (New WebpayTransaccion)->ingresar($this->result); //Webpay
        $pedido = (New Pedidos)->ingresar($transaccion); // Pedidos Master
        $productos = (New PedidosProductos)->almacenar($pedido); //Productos de ese pedido
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
    $this->step = $this::STEP_5;
    $id = Session::get('iduser');
    $this->tipo = Session::get('tipo');
    if($this->token == '' or $this->token == null){
      $this->mensaje = true;
    }
    else{
      $comprapay = (New WebpayTransaccion)->find_by_sql("SELECT * FROM webpay_transaccion WHERE usuario_id = ".$id." ORDER BY id DESC LIMIT 1");
      $this->comprapay = $comprapay; //id de comprapay
      $pedido = (New Pedidos)->find_by_sql("SELECT id FROM pedidos WHERE transaccion_id = $comprapay->id");
      $productos = (New PedidosProductos)->find_all_by_sql("SELECT producto_id FROM pedidos_productos WHERE pedido_id = ".$pedido->id."");
      foreach ($productos as $key => $valor) {
        $this->detalles[] = (New Productos)->find_all_by_sql("SELECT p.proyecto as proyecto, p.nombre as nombre, p.valor as valor, c.nombre as curso, l.codigo as codigo
                                                              FROM productos p, cursos c, licences l
                                                              WHERE p.nivel_id = c.id AND p.id = '".$valor->producto_id."' AND l.usuario_id = ".$id." AND l.producto_id = '".$valor->producto_id."'");
      }
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

  public function setCarrito(){
      $carro = implode(",", $_POST["carro"]);
      Session::delete("carrito");
      Session::set("carrito", $carro);
      $this->data = Session::get("carrito");
      View::select(null, 'json_carrito');
  }
}

?>
