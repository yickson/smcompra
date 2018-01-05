<?php

/**
 * Modelo para gestionar las licencias de los productos
 */
class Comunas extends ActiveRecord
{
  public function getNombre($id){
      $comuna = (new Comunas)->find($id);
      $nombre = $comuna->nombre;
      return $nombre; 
  }
}


?>
