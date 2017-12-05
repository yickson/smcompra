<?php

/**
 * Modelo del administrador
 */
class Administrador extends ActiveRecord
{
  /**
  * Iniciar sesion
  *
  */
  public function login()
  {
    // Obtiene el adaptador
    $auth = Auth2::factory('model');
    // Modelo que utilizará para consultar
    $auth->setModel('administrador');
    $auth->setLogin('correo');
    $auth->setPass('clave');
    $auth->setAlgos('md5');
    $auth->setSessionNamespace('administrador');
    $auth->setFields(array('id', 'nombre'));
    if($auth->identify()) return true;
    Flash::error($auth->getError());
    return false;
  }
  /**
  * Terminar sesion
  *
  */
  public function logout()
  {
    Auth2::factory('model')->logout();
  }
  /**
  * Verifica si el usuario esta autenticado
  *
  * @return boolean
  */
  public static function logged()
  {
  return Auth2::factory('model')->isValid();
  }
}


?>
