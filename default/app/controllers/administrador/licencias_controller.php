<?php

/**
 * Controller para gestionar las licencias
 */
class LicenciasController extends AppController
{

  public function before_filter()
  {
    View::template("admin");
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
