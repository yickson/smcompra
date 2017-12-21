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
    }
}
