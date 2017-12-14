<?php

/**
 * Modelo para gestionar las licencias de los productos
 */
class Regiones extends ActiveRecord
{
  public function getNombre($id){
      $region = (new Regiones)->find($id);
      $nombre = $region->region_nombre;
      return $nombre; 
  }
}


?>
