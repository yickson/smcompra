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

  public function editar($id)
  {
    $this->usuario = (New Usuarios)->find($id);
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

  public function editar_usuario()
  {
    $id = Input::post('id');
    $nombre = Input::post('nombre');
    $rut = Input::post('rut');
    $correo = Input::post('correo');
    $tel = Input::post('tel');
    $usuarios = (New Usuarios)->editar_usuario($id, $nombre, $rut, $correo, $tel);
    $this->data = $usuarios;
    View::select(null, 'json');

  }

  public function consultarHijos()
  {
      $hijos = (New Usuarios)->getHijos();
      $this->data = $hijos;
      View::select(null, "json");
  }

    public function consultarDireccion()
  {
      $usuario = Input::post("usuario");
      $direccion = (New Usuarios)->getDireccionAdmin($usuario);
      $this->data = $direccion;
      View::select(null, "json");
  }
}


?>
