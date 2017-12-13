<?php

/**
 * Modelo para gestionar alumnos
 */
class Alumnos extends ActiveRecord
{
  public function verificar($datos)
  {
    //Ingreso todo por rut
    foreach ($datos as $key => $valor) {
      $alumno = (New Alumnos)->find_by_rut($this->verificador($valor['rut']));
      if($alumno->email == ''){
        $alumno->email = $valor['correo'];
        $alumno->save();
        return true;
      }
      else{
        return true;
      }
    }
  }

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

  public function verificador($rut)
  {
    $rut = $this->limpia_rut($rut); //Deja solo numeros
    $rut = $this->digito_rut($rut); //Elimina digito verificador
    return $rut;
  }
}



?>
