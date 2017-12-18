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
    }
}
