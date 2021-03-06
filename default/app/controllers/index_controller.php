<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
class IndexController extends AppController
{
    function before_filter()
    {
      View::template('main');
    }

    public function index()
    {
      Session::set("carrito", "");
      Session::delete('iduser'); //Limpia la variable del id del usuario
      Session::delete('rutc');
      Session::delete('rt');
      Session::delete('tipo'); //Limpia la variable del id del usuario
      Session::delete('monto');
      Session::delete('descripcion');
    }
    
    public function setNavegador(){
	Session::set("navegador", Input::post("data"));
	$this->data = "exito";
	View::select(null, "json");
    }
}
