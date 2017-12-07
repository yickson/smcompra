<?php

/**
 * Modelo para gestionar los colegios (Establecimientos)
 */
class Establecimientos extends ActiveRecord
{
    
 /**
  * @param $id int
  * @return string Envia el id del colegio para retornar su nombre
  */
  public function getNombreById($id){
    $establecimiento = (New Establecimientos)->find($id);
    return $establecimiento->nombre;
  }
}


?>
