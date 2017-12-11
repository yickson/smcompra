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
    View::template(null);
  }

  public function tipo()
  {
    $tipo = Session::set('tipo', Input::post('tipo'));
    if($tipo == 'apoderado'){
      $tipo = 1;
    }
    else{
      $tipo = 2;
    }
    $this->data = $tipo;
    View::select(null, 'json');
  }

  public function buscar()
  {
    $rut = Input::post('rut');
    $usuario = (New Usuarios)->find_by_rut($rut);
    Session::set('iduser', $usuario->id);
    $this->data = $usuario;
    View::select(null, 'json');
  }

  public function buscar_alumno()
  {
    $rut = Input::post('rut');
    $usuario = (New Alumnos)->find_by_rut($rut);
    $this->data = $usuario;
    View::select(null, 'json');
  }

  public function encontrar_usuario()
  {
    View::select(null, 'json');
  }

  public function principal()
  {
    View::template(null);
  }
}



?>
