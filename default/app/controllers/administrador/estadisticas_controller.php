<?php

/**
 * Estadisticas Controller
 */
class EstadisticasController extends AppController
{

  function before_filter()
  {
    View::template('admin');
  }

  public function index()
  {
    //Vista inicial con todas las graficas
  }

  public function compras()
  {
    //Vista para ver todas las compras y sumatorias
  }

  //Método para las estadisticas de la plataforma

  public function getUsuarios()
  {
    $datos = (New Usuarios)->find_all_by_sql("SELECT IF(tipo = 1, 'apoderado', 'profesor') as tipo, COUNT(nombre) as cantidad FROM usuarios GROUP BY tipo");
    $this->data = $datos;
    View::select(null, 'json');
  }
}


?>
