<?php

/**
 * Modelo para gestionar las licencias de los productos
 */
class Regiones extends ActiveRecord
{
  public function getNombre($id){
      $region = $this->find($id);
      $nombre = $region->nombre;
      return $nombre; 
  }
}


?>
