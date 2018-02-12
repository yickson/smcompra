<?php

/**
 * Controller para gestionar las licencias
 */
class LicenciasController extends AppController
{

  public function before_filter()
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
    //Index de vista de index
  }

  public function listar_licencias()
  {
    $licencia["data"] = (New Licences)->getLicenciasActivas();
    $this->data = $licencia;
    View::select(NULL, 'json');
  }
}



?>
