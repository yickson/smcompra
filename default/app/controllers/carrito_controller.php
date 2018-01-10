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
      $l = new Alumnos;
	    $alumnos = $l->filtrar(Input::post('alumno'));
	    $datos = $l->verificar($alumnos);
	    foreach ($alumnos as $key => $valor) {
	      $alumno[] = $l->find_by_rut($l->verificador($valor['rut'])); //Metodo para verificar un vacío
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
      $array_hijo = array();
      $alumnos = new Alumnos();
      foreach($_POST["hijos"] as $key => $hijo):
	  $array_hijo[$key]["id"] = $alumnos->find($hijo["id"])->id;
	  $est = $alumnos->find($hijo["id"])->establecimiento_id;
	  $array_hijo[$key]["rbd"] = (new Establecimientos)->find($est)->rbd;
	  $array_hijo[$key]["nombre"] = $alumnos->find($hijo["id"])->nombre;
	  $array_hijo[$key]["curso"] = $alumnos->find($hijo["id"])->curso;
      endforeach;
      $alumnos_productos  = $productos->getProductosByUsuario($array_hijo);
      $this->data = $alumnos_productos;
      View::select(null,"json");
  }

  public function comprar(){

    Session::set("carrito", $_POST["productos_arr"]);
    $this->step = $this::STEP_4;
    $this->arr  =  Session::get("carrito");
    //$this->tipo = $tipo;
  }

  public function dataTableListarCarrito(){
    $carrito = New Carrito();
    $productos = $carrito->getListaProductos();
    $this->data  = $productos;
    View::select( null , 'json_carrito' );
  }

  public function datatableValidarPago(){
    $datos_direccion = Input::post("datos_direccion");
    $total  = (New Usuarios)->enviarPago($datos_direccion);
    $data["tipo"]  = Session::get('tipo');
    $data["total"] = $total;
    $this->data   = $data;
    View::select( null , "json" );
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
      $usuario = (New Usuarios)->find($id);
      $comprapay = (New WebpayTransaccion)->find_by_sql("SELECT * FROM webpay_transaccion WHERE usuario_id = ".$id." ORDER BY id DESC LIMIT 1");
      $this->comprapay = $comprapay; //id de comprapay
      $pedido = (New Pedidos)->find_by_sql("SELECT id FROM pedidos WHERE transaccion_id = $comprapay->id");
      $productos = (New PedidosProductos)->find_all_by_sql("SELECT producto_id FROM pedidos_productos WHERE pedido_id = ".$pedido->id."");

      if($this->tipo == 2){
        $this->detalles = (New PedidosProductos)->find_all_by_sql("SELECT pp.id, p.descripcion, p.proyecto, p.nombre, ROUND(p.valor * 0.5) as valor FROM productos p, pedidos_productos pp WHERE p.id = pp.producto_id AND pp.usuario_id = $id AND pp.pedido_id = $pedido->id");
        $this->direccion = (New Direcciones)->getFullDireccion();
        $array_textos = Session::get("carrito");
	      $texto = (new ProfesorAlumnos)->DesactivarTexto($array_textos);
      }else{
        $this->detalles = (New PedidosProductos)->find_all_by_sql("SELECT pp.id, p.proyecto, p.nombre, p.valor, l.codigo, p.nivel FROM pedidos_productos pp INNER JOIN productos p ON p.id = pp.producto_id INNER JOIN licences l ON l.producto_id = pp.producto_id AND l.usuario_id = $id WHERE pp.usuario_id = 1280 AND pp.pedido_id = $pedido->id");
        //Email::enviar_a($usuario->email, $this->detalles); //Email para el apoderado
      }
      $this->data_alumnos = (new Alumnos)->buscar_colegio();
    }
  }

  public function error()
  {
    //Vista para los errores de WebPay
  }

  public function consultarDireccion()
  {
      $direccion = (New Direcciones)->getDireccion();
      if($direccion){
	$comuna_nombre = (New Comunas)->getNombre($direccion->id_comuna);
	$region_nombre = (New Regiones)->getNombre($direccion->id_region);
	$direccion->nombre_comuna = $comuna_nombre;
	$direccion->nombre_region = $region_nombre;
      }

      $this->data = $direccion;
      View::select(null,"json");
  }

  public function setCarrito(){
      $carro = stripslashes(json_encode($_POST["carro"]));
      Session::delete("carrito");
      Session::set("carrito", $carro);
      $this->data = Session::get("carrito");
      View::select(null, 'json_carrito');
  }

  public function simulacionRest(){
	$codigo = Input::post("codigo");
	$licencias = (new Licences)->find_by_codigo($codigo);
	$estado = ($licencias->estado == 1)?true:false;
	$this->data = $estado;
	View::select(null, 'json_carrito');
  }

  /**
     * Actualiza licencias de productos de alumnos
     * @return array| Multidimensional
     */
    public function setLicencias()
    {
	$licencias = Input::post("data");
	$alumnos = Session::get("alumno");
	$id_usuario = Session::get("iduser");
	$licencias_array = array();
	$licencias_repetidas = array();
	foreach($alumnos as $al):

	    //licencias
	    foreach($licencias["message"] as $lic):
		$licencia = (new Licences)->find_by_sql("SELECT id, codigo, producto_id, estado, usuario_id
							  FROM licences
							  WHERE alumno_id    = $al->id
							  AND producto_id  = ".$lic['store_id'][1]."");
		if($lic['store_id'][1] == $licencia->producto_id && $lic["store_id"][0] == $al->id)
		{
		    if(in_array($lic['licencia'], $licencias_array))
		    {
			array_push($licencias_repetidas, $lic['licencia']);

		    }else
		    {
			$licencia->codigo     = $lic['licencia'];
			$licencia->tipo       = 'conecta';
			$licencia->usuario_id = $id_usuario;
			$licencia->estado   = 1;
			$licencia->save();
			array_push($licencias_array, $lic['licencia']);
		    }
		}
	    endforeach;
	endforeach;
	$this->data = "exito, ".count($licencias_repetidas)."licencias repetidas son";
	View::select(null, "json");
    }

    /**
     * Obtiene un array multidimensional de cada alumno identificado por el rut, y un sub array con sus licencias
     * @return array| Multidimensional
     */
    public function getRutAlumnos()
    {
	$alumno_licencias = (New Licences)->getLicenciasPorAlumno();
	$this->data = $alumno_licencias;
	View::select(null, "json");
    }

    public function cargarComunas(){
	$region = Input::post("region");
	$comunas = (new Comunas)->find_all_by_id_region($region);
	$this->data = $comunas;
	View::select(null, "json");
    }

public function setLicenciaEs(){
  	$id = Input::post("alumno");
  	$carrito = json_decode(Session::get("carrito"));
  	foreach($carrito as $carro):
  	    if($carro[0] == $id){
  		$licencias = (new Licences)->find_by_sql("SELECT * FROM licences WHERE alumno_id = $carro[0] AND producto_id = $carro[1]");
  		if($licencias->id != null){
          $d = (New Alumnos);
          $alumno = $d->find($id);
          $rbd = $d->getColegio($alumno->establecimiento_id);
          if($rbd == 2200 AND $alumno->curso == 8 AND $carro[1] == 360){ //Si el colegio es 2200 y curso es 8 setea 3 licencias en una
            for ($i=0; $i < 3; $i++) {
              $licencias->tipo  = "espania";
      		    $licencias->usuario_id = Session::get("iduser");
      		    $licencias->estado = true;
              $licencias->save();
            }
          }
          else{
            $licencias->tipo  = "espania";
    		    $licencias->usuario_id = Session::get("iduser");
    		    $licencias->estado = true;
    		    if($licencias->save()){
    			$this->data = "OK";
    		    }else{
    			$this->data = "errrr";
    		    }
          }

  		}
  	    }
  	endforeach;
  	die();

  	View::select(null, "json");
  }
}

?>
