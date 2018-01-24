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
      $direccion->telefono = (new Usuarios)->find(Session::get("iduser"))->telefono;
      return $direccion; 
  }

  /**
   * @return object devuelve una instancia de direcciones de usuario logeado con nombre de Comuna y Region
   */
  public function getFullDireccion(){
      $direccion = (new Direcciones)->find_by_id_usuario($_COOKIE["clienteSM"]);
      $direccion->nombre_region = (new Regiones)->find($direccion->id_region)->nombre;
      $direccion->nombre_comuna = (new Comunas)->find($direccion->id_comuna)->nombre;
      return $direccion;
  }

  public function getDireccionCorreo()
  {
    $id = $_COOKIE["clienteSM"];
    $direccion = (New Direcciones)->find_by_sql("SELECT r.nombre as region, c.nombre as comuna, d.calle, d.numero, d.adicional, IF(d.tipo = 1, 'casa', 'depto') as tipoVivienda FROM direcciones d INNER JOIN regiones r ON (r.id = d.id_region) INNER JOIN comunas c ON (c.id = d.id_comuna) WHERE d.id_usuario = $id");
    return $direccion;
  }


}


?>
