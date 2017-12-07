<?php

/**
 * Modelo para gestionar los usuarios
 */
class Usuarios extends ActiveRecord
{

  public function getRol($id)
  {
    //Obtener el nombre rol del usuario
    $dato = (New Usuarios)->find($id);
    return $dato->tipo; //Esta variable cambiar por el nombre del campo que tenga el rol
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
    $rut = substr($rut, 0, -1);
    return $rut;
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
