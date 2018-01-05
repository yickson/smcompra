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
      $direccion = (new Direcciones)->find_by_id_usuario(Session::get("iduser"));
      return $direccion; 
  }
  
  
}


?>
