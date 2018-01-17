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
    if(strlen($rut) > 8){
      $rut = substr($rut, 0, -1);
      return $rut;
    }
    else{
      return $rut;
    }
  }

  public function verificador($rut)
  {
    $rut = $this->limpia_rut($rut); //Deja solo numeros
    $rut = $this->digito_rut($rut); //Elimina digito verificador
    return $rut;
  }

  public function filtrar($arreglo)
  {
    foreach ($arreglo as $key => $value) {
      if($key >= 1){
        if($value['rut'] == ''){
          unset($arreglo[$key]);
        }
      }
    }
    return $arreglo;
  }

  public function buscar_colegio()
  {
    //Buscar colegio del alumno
    $alumnos = Session::get('hijos');
    foreach ($alumnos as $key => $valor) {
        $datos = (New Alumnos)->find($valor['id']);
        $est = (New Establecimientos)->find($datos->establecimiento_id);
        $colegio[] = array('id'    => $datos->id,
			   'rut'   => $datos->rut,
                           'rbd'   => $est->rbd,
                           'pais'  => $est->pais,
                           'curso' => $datos->curso);
    }

    return $colegio;
  }

  public function getColegio($id_est)
  {
    $colegio = (New Establecimientos)->find($id_est);

    return $colegio->rbd;
  }

  public function caso_especial()
  {
    $lic = array(379, 380, 381);
    $hijos = Session::get('hijos');
    $hijos_ohiggin = array();
    $resultado = array();
    $resp = '';
    foreach ($hijos as $key => $value) {
      $alumno = (New Alumnos)->find($value['id']);
      $rbd = (New Establecimientos)->find($alumno->establecimiento_id);
      if($alumno->curso == 8 AND $rbd->rbd == 2200){
        $hijos_ohiggin[] = $alumno->id;
      }
    }
    foreach ($hijos_ohiggin as $value) {
      foreach ($lic as $valor) {
        $licencia = (New Licences)->find_by_sql("SELECT * FROM licences WHERE alumno_id = $value AND producto_id = $valor");
        $resp .= $licencia->codigo.'<br>';
      }
      $resultado[] = $resp;
      $resp = '';
    }
    return $resultado;
  }
}



?>
