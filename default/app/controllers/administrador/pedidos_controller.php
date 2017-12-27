<?php

//require_once APP_PATH ."extensions/helpers/btn_acciones.php";
/**
 * Controlador para crear usuarios de tipo administrador
 */
class PedidosController extends AppController
{
  function before_filter()
  {
    View::template('admin');
    $valido = New Administrador;
    if(!$valido->logged()){
      Redirect::to("administrador/index/entrar");
    }
  }
  
  
  public function index()
  {
     
  }
  
  /**
   * Listar usuarios con hijos
   */
  public function listar_pedidos()
  {
    $pedidos["data"] = (New Pedidos)->getPedidos();
    $this->data = $pedidos;
    View::select(null, "json");
  }

}


?>
