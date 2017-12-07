<?php

/**
 * Controlador para los usuarios
 */
class UsuarioController extends AppController
{
  function before_filter()
  {
    View::template('main');
  }
  public function index()
  {
    //Esta vista no debería cargar nada
  }

  public function datos()
  {
    //Identidad del apoderado
    View::template(null);
    $this->tipo = Session::get('tipo');
    if($this->tipo == '' or !isset($this->tipo))
    {
      Redirect::to('index/index');
    }
  }

  public function alumno()
  {
    //Identidad del alumno
  }

  public function tipo()
  {
    $tipo = Session::set('tipo', Input::post('tipo'));
    $this->data = $tipo;
    View::select(null, 'json');
  }
}



?>
