<?php

/**
 * Modelo para direcciones de usuarios
 */
class Direcciones extends ActiveRecord
{
    
  /**
   * @return object devuelve una instancia de direcciones de usuario logeado Solo ID's
   */  
  public function getDireccion(){
      $direccion = (new Direcciones)->find_by_id_usuario(Session::get("iduser"));
      return $direccion; 
  }
  
  /**
   * @return object devuelve una instancia de direcciones de usuario logeado con nombre de Comuna y Region
   */  
  public function getFullDireccion(){
      $direccion = (new Direcciones)->find_by_id_usuario(Session::get("iduser"));
      $direccion->nombre_region = (new Regiones)->find($direccion->id_region)->nombre;
      $direccion->nombre_comuna = (new Comunas)->find($direccion->id_comuna)->nombre;
      return $direccion; 
  }
  
  
}


?>
