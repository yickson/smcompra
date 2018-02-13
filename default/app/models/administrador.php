<?php
require_once APP_PATH ."extensions/helpers/datatable_acciones.php";
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

  public function getAdministrador()
  {
    $administrador = array();
    $i = 0;
    $datos = (New administrador)->find_all_by_sql("SELECT a.id, a.nombre, a.correo, n.descripcion FROM administrador a INNER JOIN niveles n ON (n.id = a.nivel)");
    foreach ($datos as $key => $valor) {
      $administrador[$i]['id'] = $valor->id;
      $administrador[$i]['nombre'] = $valor->nombre;
      $administrador[$i]['correo'] = $valor->correo;
      $administrador[$i]['descripcion'] = $valor->descripcion;
      $administrador[$i]['acciones'] = DatatableAcciones::getBtnAdm($valor->id);
      $i++;
    }
    return $administrador;
  }
}


?>
