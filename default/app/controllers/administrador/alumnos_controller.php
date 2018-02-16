<?php

/**
 * Controller para gestionar los alumnos
 */
class AlumnosController extends AppController
{

  function before_filter()
  {
    View::template('admin');
    $valido = New Administrador;
    if(!$valido->logged()){
      Redirect::to("administrador/index/entrar");
    }
    else{
      $this->admin = Session::get('nombre', 'administrador');
      if(!$this->acl->is_allowed($this->userRol, $this->controller_name, $this->action_name)){
  			//Flash::error("Acceso negado");
  			//return false;
        Redirect::to('administrador');
		  }
    }
  }

  public function index()
  {

  }

  public function crear()
  {
    $this->colegios = (New Establecimientos)->find();
    $this->cursos = (New Cursos)->find();
  }

  public function editar($id)
  {
    $this->alumno = (New Alumnos)->find($id);
    $this->colegios = (New Establecimientos)->find();
    $this->cursos = (New Cursos)->find();
  }

  public function eliminar()
  {

  }

  //Metodos AJAX

  public function listar_usuarios()
  {
    $datos = (New Alumnos)->getAlumnos();
    $this->data = $datos;
    View::select(null, 'json');
  }

  public function editar_usuario()
  {
    $id = Input::post('id');
    $nombre = Input::post('nombre');
    $correo = Input::post('correo');
    $rut = Input::post('rut');
    $curso = Input::post('curso');
    $colegio = Input::post('colegio');
    $datos = (New Alumnos)->find($id);
    $datos->nombre = $nombre;
    $datos->email = $correo;
    $datos->rut = $rut;
    $datos->curso = $curso;
    $datos->establecimiento_id = $colegio;
    if($datos->save()){
      $this->data = 1;
    }else{
      $this->data = 2;
    }

    View::select(null, 'json');
  }

  public function crear_usuario()
  {
    $nombre = Input::post('nombre');
    $correo = Input::post('correo');
    $rut = Input::post('rut');
    $curso = Input::post('curso');
    $colegio = Input::post('colegio');
    $datos = (New Alumnos);
    $datos->nombre = $nombre;
    $datos->email = $correo;
    $datos->rut = $rut;
    $datos->curso = $curso;
    $datos->establecimiento_id = $colegio;
    if($datos->save()){
      $this->data = 1;
    }else{
      $this->data = 2;
    }
    View::select(null, 'json');
  }
}


?>
