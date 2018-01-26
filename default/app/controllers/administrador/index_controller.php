<?php

/**
 * Controlador inicial para la entrada al Backend
 */
class IndexController extends AppController
{

  function before_filter()
  {
    View::template('main');
    //$auth = (New Administrador)->logged();
    $accion = $this->action_name;
    if(!$auth and $accion != 'entrar'){
      Redirect::to('administrador/index/entrar');
    }
    else{
      $this->nombre = Session::get('nombre', 'administrador');
    }
  }

  public function index()
  {
    //$auth = (New Administrador)->logged();
    if(!$auth){
      Redirect::to('administrador/index/entrar');
    }
  }

  public function entrar()
  {
    View::template('main');
    if(Input::hasPost('correo', 'clave')){
      $auth = (New Administrador)->login();
      if($auth){
        Redirect::to('administrador/dashboard');
      }
    }
  }

  public function cerrar()
  {
    View::template(NULL);
    $auth = (New Administrador)->logout();
    Redirect::to('administrador/index/entrar');
  }
}



?>
