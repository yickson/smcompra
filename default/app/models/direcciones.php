<?php

/**
 * Modelo para direcciones de usuarios
 */
class Direcciones extends ActiveRecord
{
  /**
   * @return object devuelve una instancia de direcciones de usuario logeado
   */  
  public function getDireccion(){
      $direccion = (new Direcciones)->find_by_id_usuario(Session::get("usuario"));
      return $direccion; 
  }
  
  /**
   * @param Direcciones $direccion
   * @return boolean actualiza direccion de un usuario
   */
  public function actualizarDireccion($direccion, $datos_direccion)
  {
        if($datos_direccion[0]["value"] != "" || $datos_direccion[1]["value"] != "" || $datos_direccion[2]["value"] != "" || $datos_direccion[5]["value"] != "" ){
	$direccion->calle   =  $datos_direccion[0]["value"];
	$direccion->tipo  = $datos_direccion[1]["value"];
	$direccion->numero = $datos_direccion[2]["value"];
	$direccion->adicional = $datos_direccion[5]["value"];
	$direccion->save();
	    return true;
	}else{
	    return false;
	}
	
  }
}


?>
