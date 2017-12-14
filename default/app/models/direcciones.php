<?php

/**
 * Modelo para gestionar las licencias de los productos
 */
class Direcciones extends ActiveRecord
{
  public function getDireccion(){
      $direccion = (new Direcciones)->find_by_id_usuario(Session::get("usuario"));
      return $direccion; 
  }
}


?>
