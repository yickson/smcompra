<?php

//require_once APP_PATH ."extensions/helpers/btn_acciones.php";
/**
 * Controlador para crear usuarios de tipo administrador
 */
class UsuariosController extends AppController
{
  function before_filter()
  {
    View::template('admin');
    $valido = New Administrador;
    if(!$valido->logged()){
      Redirect::to("administrador/index/entrar");
    }
  }
  
  
  public function index()
  {
     
  }
  
  /**
   * Listar usuarios con hijos
   */
  public function listar_con_hijos()
  {
    $usuarios["data"] = (New Usuarios)->getTodos_con_hijos();
    $this->data = $usuarios;
    View::select(null, "json");
  }
  
  public function consultarHijos()
  {
      $hijos = (New Usuarios)->getHijos();
      $this->data = $hijos;
      View::select(null, "json");
  }
}


?>
