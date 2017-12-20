<?php

/**
 * Controlador para los usuarios
 */
class UsuarioController extends AppController
{
  //Constantes
  const STEP_1 = array("etapa" => 1);
  const STEP_2 = array("etapa" => 2);
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
    $this->tipo = Session::get('descripcion');
    $this->step = $this::STEP_1;
    $id = Session::get('iduser');
    if(!empty($id)){
      $this->usuario = (New Usuarios)->find($id);
    }
    if($this->tipo == '' or !isset($this->tipo))
    {
      Redirect::to('index/index');
    }
  }

  public function alumno()
  {
    //Identidad del alumno
      Session::delete("hijos");
//    if(!empty(Session::get("hijos"))){
//	print_r("se elimino sesion hijos");die();
//	Session::delete("hijos");
//    }
    $this->step = $this::STEP_2;
    View::template(null);
  }

  public function tipo()
  {
    $tipo = Input::post('tipo');
    if($tipo == 'apoderado'){
      Session::set('descripcion', $tipo);
      Session::set('tipo', 1);
    }
    else{
      Session::set('descripcion', $tipo);
      Session::set('tipo', 2);
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
    $user = Session::get('iduser');
    $usuario = (New Alumnos)->find_by_rut($rut);
    if(!empty($usuario)){
      if($user == $usuario->apoderado_id){
        $this->data = $usuario;
      }
      if($usuario->apoderado_id == 0){
        $datos = (New Alumnos)->find($usuario->id); //Inyeccion de datos
        $datos->apoderado_id = $user; //Asigno el nuevo ID del apoderado/profesor
        $datos->save();
        $this->data = $usuario;
      }
      if($user != $usuario->apoderado_id){
        $this->data = 2;
      }
    }
    else{
      $this->data = false;
    }

    View::select(null, 'json');
  }

  public function verificar_usuario()
  {
    $rut = Input::post('rut');
    $usuario = (New Usuarios)->find_by_rut($rut);
    $tipo = Session::get('tipo');
    if($tipo == $usuario->tipo){
      $this->data = 1;
    }
    else{
      switch ($tipo) {
        case 1:
          $this->data = 3;
          break;

        case 2:
          $this->data = 2;
          break;
      }
    }
    View::select(null, 'json');
  }

  public function encontrar_usuario()
  {
    View::select(null, 'json');
  }

  public function principal()
  {
    Session::delete('iduser'); //Limpia la variable del id del usuario
    View::template(null);
  }
}



?>
