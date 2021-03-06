<?php

require_once APP_PATH ."extensions/helpers/datatable_acciones.php";

/**
 * Modelo para gestionar los usuarios
 */
class Usuarios extends ActiveRecord
{
    const APODERADO = 1;
    const PROFESOR  = 2;

  public function getRol($id)
  {
    //Obtener el nombre rol del usuario
    $dato = (New Usuarios)->find($id);
    return $dato->tipo; //Esta variable cambiar por el nombre del campo que tenga el rol
  }

  /**
   * @return object devuelve una instancia de direcciones de usuario logeado
   */
  public function getDireccion(){
      if(Session::get("tipo") == $this::PROFESOR){
	$direccion = (new Direcciones)->find_by_id_usuario(Session::get("iduser"));
	return $direccion;
      }else{
	return null;
      }
  }

  /**
   * @return object devuelve una instancia de direcciones de usuario
   */
  public function getDireccionAdmin($usuario){
	$direccion = (new Direcciones)->find_by_id_usuario($usuario);
	$direccion->region = (new Regiones)->getNombre($direccion->id_region);
	$direccion->comuna = (new Comunas)->getNombre($direccion->id_comuna);
	return $direccion;
  }

  /**
   * @param Direcciones $direccion
   * @return boolean actualiza direccion de un usuario
   */
  public function enviarPago($telefono, $region, $comuna, $tipo, $calle, $numero, $adicional)
  {
        //crear Cookie
	setcookie("carritoSM", Session::get("carrito"),time()+86400*30);
	setcookie("hijosSM",   json_encode(Session::get("hijos")),time()+86400*30);

        //Setenado datos carrito
	$carrito = New Carrito();
	$total = $carrito->getTotalByTipoUsuario();
	$total = $carrito->valorDespacho($total);
	Session::set('total', $total);
	setcookie("totalSM", Session::get("total"),time()+86400*30);
	$direccion = (new Direcciones)->find_by_id_usuario($_COOKIE["clienteSM"]);
	if($direccion){
	    // ya existe direccion
	}else{
	    $direccion = new Direcciones();
	}
	//Actualizamos datos de direccion en caso de ser profesor
	if(Session::get("tipo") == $this::PROFESOR){
	    if($region != "" && $comuna !="" && $numero !="" && $telefono !="" && $calle !="")
	    {
		$direccion->calle      = $calle;
		$direccion->tipo       = $tipo;
		$direccion->numero     = $numero;
		$direccion->id_comuna  = $comuna;
		$direccion->id_region  = $region;
		$direccion->adicional  = $adicional;
		$direccion->id_usuario = Session::get("iduser");
		$direccion->save();

		$usuario = (new Usuarios)->find($_COOKIE["clienteSM"]);
		$usuario->telefono = $telefono;
		$usuario->save();
	    }else{
		$total = null;
	    }
	}
	$nombre_archivo = "log_compras.txt";
	if(file_exists($nombre_archivo))
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "* ID_USUARIO: ".Session::get("iduser")."\n";
		$mensaje .= "* CARRITO: ".Session::get("carrito")."\n";
		$mensaje .= "* HIJOS: ".json_encode(Session::get("hijos"));
	    }
	}

	else
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "* ID_USUARIO: ".Session::get("iduser")."\n";
		$mensaje .= "* CARRITO: ".Session::get("carrito")."\n";
		$mensaje .= "* HIJOS: ".json_encode(Session::get("hijos"));
	    }
	}

	if($archivo = fopen($nombre_archivo, "a"))
	{
	    if(fwrite($archivo, date("Y-m-d H:i:s"). " ". $mensaje. "\n"))
	    {

	    }
	    else
	    {

	    }

	    fclose($archivo);
	}
	return $total;
  }

  /**
   * Consulta para traer todos los usarios con sus hijos
   * @return object
   */
  public function getTodos_con_hijos()
  {
      $todos_con_hijos = $this->find_all_by_sql("SELECT u.id, u.nombre, u.tipo, u.rut, COUNT(a.id) as hijos
						 FROM usuarios as u
						 LEFT JOIN alumnos a ON (a.apoderado_id = u.id)
						 GROUP BY u.id");
      $usuarios = array();
      foreach($todos_con_hijos as $key => $usuario):
	$usuarios[$key]["id"]  = $usuario->id;
  $usuarios[$key]["rut"] = $usuario->rut;
	$usuarios[$key]["nombre"]  = $usuario->nombre;
	$usuarios[$key]["tipo"]    = ($usuario->tipo == $this::APODERADO)?"<span>Apoderado</span>":"<span>Profesor</span>";
	$usuarios[$key]["hijos"]   = DatatableAcciones::getBtnUsuarios($usuario);
  $usuarios[$key]["acciones"]   = DatatableAcciones::getBtnUser($usuario->id);
      endforeach;
      return $usuarios;
  }

  /**
   * Devuelve los hijos de un usuario
   * @return object | $hijos
   */
  public function getHijos()
  {
    $usuario = Input::post("usuario");
    $hijos = $this->find_all_by_sql("SELECT a.id as id_alumno, a.nombre as nombre_alumno, a.rut as rut_alumno, u.id as id_usuario, u.nombre as nombre_usuario, u.rut as rut_usuario, u.tipo
				     FROM alumnos as a
				     INNER JOIN usuarios u ON (a.apoderado_id = u.id)
				     WHERE apoderado_id = $usuario ");
    return $hijos;
  }

  public function getTipoMail($ordenCompra)
  {
    $id = (New WebpayTransaccion)->find_by_buyOrder($ordenCompra)->usuario_id;
    $usuario = (New Usuarios)->find($id);
    if($usuario->tipo == 1){
      $this->detalles = (New WebpayTransaccion)->find_all_by_sql("SELECT l.codigo, pr.proyecto, pr.nombre, pr.valor, pr.nivel FROM webpay_transaccion wt INNER JOIN pedidos p ON (p.transaccion_id = wt.id) INNER JOIN pedidos_productos pp ON (pp.pedido_id = p.id) INNER JOIN productos pr ON (pr.id = pp.producto_id) INNER JOIN usuarios u ON (u.id = wt.usuario_id) INNER JOIN licences l ON (l.producto_id = pr.id AND l.usuario_id = u.id) WHERE wt.buyOrder = '$ordenCompra'");
      //Email::enviar_a($usuario->email, $this->detalles);
      return 1;
    }
    else{
      $this->detalles = (New WebpayTransaccion)->find_all_by_sql("SELECT pr.proyecto, pr.nombre, ROUND(pr.valor * 0.5) as valor, pr.nivel FROM webpay_transaccion wt INNER JOIN pedidos p ON (p.transaccion_id = wt.id) INNER JOIN pedidos_productos pp ON (pp.pedido_id = p.id) INNER JOIN productos pr ON (pr.id = pp.producto_id) INNER JOIN usuarios u ON (u.id = wt.usuario_id) WHERE wt.buyOrder = '$ordenCompra'");
      $this->direccion = (New Direcciones)->getDireccionAdmin($usuario->id);
      //Email::enviar($usuario->email, $this->detalles, $this->direccion);
      return 1;
    }
  }


  public function validar($rut)
  {
    //El usuario envia el RUT para ser validado
    $rut = $this->limpia_rut($rut);
    $rut = substr($rut, 0, -1);
    $usuario = (New Usuarios)->find_by_rut($rut);
    if($usuario != false){
      Session::set('usuario', $usuario->id);
      return true;
    }
    else{
      return false;
    }
  }

  //Metodo para dejar solo números
  public function limpia_rut($rut)
  {
    $result = preg_replace('([^0-9,k])', '', $rut);
    return $result;
  }

  //Metodo para eliminar digito verificador
  public function digito_rut($rut)
  {
    if(strlen($rut) > 8){
      $rut = substr($rut, 0, -1);
      return $rut;
    }
    else{
      return $rut;
    }
  }
  /*
  * @param string $rut
  * Este metodo es 2 en 1
  */
  public function verificador($rut)
  {
    $rut = $this->limpia_rut($rut); //Deja solo numeros
    $rut = $this->digito_rut($rut); //Elimina digito verificador
    return $rut;
  }

  public function editar_usuario($id, $nombre, $rut, $correo, $tel)
  {
    $usuario = (New Usuarios)->find($id);
    $usuario->nombre = $nombre;
    $usuario->rut = $rut;
    $usuario->email = $correo;
    $usuario->telefono = $tel;

    if($usuario->update()){
      return 1;
    }else{
      return 2; //Error no almaceno
    }
  }
}



?>
