<?php

/**
 * Controller para gestionar los productos comprados
 */
class ProductosController extends AppController
{

  function before_filter()
  {
    View::template('admin');
  }

  public function index()
  {
    //Vista de productos activos en plataforma
  }

  public function listar_productos()
  {
    $productos = (New Productos)->getProductosActivos();
    View::select(NULL, 'json');
  }
}



?>
