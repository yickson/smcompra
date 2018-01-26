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
    //$valido = New Administrador;
    /*if(!$valido->logged()){
      Redirect::to("administrador/index/entrar");
    }*/
  }


  public function index()
  {

  }

  public function mailer()
  {
    //Vista de los pedidos con la opción de reenviar los pedidos a los clientes
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

  public function listar_pedidos_mailer()
  {
    $pedidos["data"] = (New Pedidos)->getPedidosMail();
    $this->data = $pedidos;
    View::select(null, 'json');
  }

  public function enviarMail()
  {
    //Debo primero buscar el tipo de usuario
    $ordenCompra = Input::post('orden');
    $usuario = (New Usuarios)->getTipoMail($ordenCompra);
    $this->data = $usuario;
    View::select(null, 'json');
  }

}


?>
