<?php

/**
 * Controller para profesor
 */
class ProfesorController extends AppController
{
  function before_filter()
  {
    View::template('main');
    $this->tipo = 'profesor';
  }

  public function index()
  {
    //Esta vista no debería tener contenido
    View::select('../index/index'); //Modificar esto luego
  }

  public function datos()
  {
    View::select('../usuario/datos');
  }

  public function alumno()
  {
    View::select('../usuario/index');
  }
}


?>
