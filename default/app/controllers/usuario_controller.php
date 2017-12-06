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
  }

  public function alumno()
  {
    //Identidad del alumno
  }
}



?>
