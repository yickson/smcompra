<?php

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
   * @param Direcciones $direccion
   * @return boolean actualiza direccion de un usuario
   */
  public function enviarPago($datos_direccion)
  {
        //Setenado datos carrito
	$carrito = New Carrito();
	$total = $carrito->getTotalByTipoUsuario();
	$total = $carrito->valorDespacho($total);
	Session::set('total', $total);
	$direccion = (new Direcciones)->find_by_id_usuario(Session::get("iduser"));
	if($direccion){
	    // ya existe direccion
	}else{
	    $direccion = new Direcciones();
	}
	//Actualizamos datos de direccion en caso de ser profesor
	if(Session::get("tipo") == $this::PROFESOR){
	    if($datos_direccion[0]["value"] != "" && $datos_direccion[1]["value"] != "" && $datos_direccion[2]["value"] != "" && $datos_direccion[5]["value"] != "" )
	    {
		$direccion->calle   =  $datos_direccion[0]["value"];
		$direccion->tipo  = $datos_direccion[1]["value"];
		$direccion->numero = $datos_direccion[2]["value"];
		$direccion->id_comuna = $datos_direccion[3]["value"];;
		$direccion->id_region = $datos_direccion[4]["value"];
		$direccion->adicional = $datos_direccion[5]["value"];
		$direccion->id_usuario = Session::get("iduser");
		$direccion->save();
	    }else{
		$total = null;
	    }
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
	$usuarios[$key]["id"] = $usuario->id;
        $usuarios[$key]["rut"] = $usuario->rut;
	$usuarios[$key]["nombre"]  = $usuario->nombre;
	$usuarios[$key]["tipo"]    = ($usuario->tipo == $this::APODERADO)?"<span>Apoderado</span>":"<span>Profesor</span>";
	$usuarios[$key]["hijos"]   = "<button data-id='".$usuario->id."' class='btn btn-info hijos'> <span class='badge'>$usuario->hijos</span></button>";
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
}



?>
