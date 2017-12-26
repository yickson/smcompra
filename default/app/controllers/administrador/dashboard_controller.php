<?php

//require_once APP_PATH ."extensions/helpers/btn_acciones.php";
/**
 * Controlador para crear usuarios de tipo administrador
 */
class DashboardController extends AppController
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
    //Vista del index
  }
}


?>
