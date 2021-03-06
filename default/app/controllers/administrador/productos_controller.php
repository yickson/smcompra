<?php

/**
 * Controller para gestionar los productos comprados
 */
class ProductosController extends AppController
{

  function before_filter()
  {
    View::template('admin');
    $valido = New Administrador;
    if(!$valido->logged()){
      Redirect::to("administrador/index/entrar");
    }
    else{
      $this->admin = Session::get('nombre', 'administrador');
      if(!$this->acl->is_allowed($this->userRol, $this->controller_name, $this->action_name)){
  			//Flash::error("Acceso negado");
  			//return false;
        Redirect::to('administrador');
		  }
    }
  }

  public function index()
  {
    //Vista de productos activos en plataforma
  }

  public function agregar()
  {
    //Vista para agregar nuevos productos
    $this->cursos = (New Cursos)->find();
    $this->tipos = (New ProductosTipo)->find();
  }

  public function editar($id)
  {
    //Edicion de los productos
    $producto = (New Productos)->find($id);
    $this->producto = $producto;
    $this->cursos = (New Cursos)->find();
    $this->tipos = (New ProductosTipo)->find();
  }

  //Llamadas AJAX en los productos

  public function listar_productos()
  {
    $productos["data"] = (New Productos)->getProductos();
    $this->data = $productos;
    View::select(NULL, 'json');
  }

  public function editar_producto()
  {
    $id = Input::post('id');
    $descripcion = Input::post('descripcion');
    $proyecto = Input::post('proyecto');
    $tipo = Input::post('tipo');
    $nombre = Input::post('nombre');
    $nivel = Input::post('nivel');
    $codigo = Input::post('codigo');
    $valor = Input::post('valor');
    $producto = (New Productos)->find($id);
    $producto->descripcion = $descripcion;
    $producto->proyecto = $proyecto;
    $producto->tipo = $tipo;
    $producto->nombre = $nombre;
    $producto->nivel_id = $nivel;
    $producto->codigo = $codigo;
    $producto->valor = $valor;
    if($producto->save()){
      $this->data = 1;
    }else{
      $this->data = 2;
    }
    View::select(null, 'json');
  }

  public function agregar_producto()
  {
    $descripcion = Input::post('descripcion');
    $proyecto = Input::post('proyecto');
    $tipo = Input::post('tipo');
    $nombre = Input::post('nombre');
    $nivel = Input::post('nivel');
    $codigo = Input::post('codigo');
    $valor = Input::post('valor');
    $producto = (New Productos);
    $producto->descripcion = $descripcion;
    $producto->proyecto = $proyecto;
    $producto->tipo = $tipo;
    $producto->nombre = $nombre;
    $producto->nivel_id = $nivel;
    $producto->codigo = $codigo;
    $producto->valor = $valor;
    if($producto->save()){
      $this->data = 1;
    }else{
      $this->data = 2;
    }
    View::select(null, 'json');
  }
}



?>
