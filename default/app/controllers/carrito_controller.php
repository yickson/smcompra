<?php
require_once APP_PATH ."extensions/helpers/datatable_acciones.php";

/**
 * Controlador para gestionar el carrito de compra
 */
class CarritoController extends AppController
{
  function before_filter()
  {
    View::template('carrito');
  }
  public function index()
  {
    //Esta vista no debería cargar nada
    $alumnos = Input::post('alumno');
    //print_r($alumnos);die();
    $l = new Alumnos;
    $datos = (New Alumnos)->verificar($alumnos);
    foreach ($alumnos as $key => $valor) {
      $alumno[] = (New Alumnos)->find_by_rut($l->verificador($valor['rut'])); //Metodo para verificar un vacío
    }

    $this->usuario = Session::get('iduser');
    $this->tipo = Session::get('tipo');
    $this->alumno = $alumno;
    //View::select(null, null);
  }

  /**
   * Retorna información del alumno
   * @param  $id post(int)
   * @return json
   **/
  public function getAlumno(){
      $id = $_POST["id"];
      $alumno = (new Alumnos)->find($id);
      $nombre_establecimiento = (new Establecimientos)->getNombreById($alumno->establecimiento_id);
      $alumno->establecimiento_nombre = $nombre_establecimiento;
      $this->data = $alumno;
      View::select(null,"json");
  }

  /**
   * Retorna Pproductos de los hijos del profesor
   * @param  $id post(int)
   * @return json
   **/
  public function getProductos(){
      $id_usuario = $_POST["id_usuario"];
      $tipo = $_POST["tipo"];
      $alumnos = "";
      
      switch($tipo):
	  case 1: //Apoderado
	        $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
							  (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, li.alumno_id as id_alumno
							   FROM productos as p
							   INNER JOIN licences li ON (li.producto_id = p.id and li.usuario_id = $id_usuario)
							   ORDER BY li.alumno_id ASC");
	  break;
	  case 2: //Profesor
	        $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
							   (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, pa.alumno_id as id_alumno
							    FROM productos as p
							    INNER JOIN profesor_alumnos pa ON (pa.producto_id = p.id and pa.usuario_id = $id_usuario)
							    ORDER BY pa.alumno_id ASC");
	  break;
      endswitch;

      $this->data = $alumnos;
      View::select(null,"json");
  }

  public function comprar(){
    $productos_arr = $_POST["productos_arr"];
    $this->arr = $productos_arr;
    $this->tipo = $tipo;
  }
  
  public function dataTableListarCarrito(){
    $carrito = New Carrito();
    $productos = $carrito->getListaProductos();
    $this->data  = $productos;
    View::select( null , 'json_carrito' );
  }
  
  public function datatableValidarPago(){
    $carrito = New Carrito();
    $total = $carrito->getTotalByTipoUsuario();
    $this->total  = $total;
    View::select( null , 'json_carrito' );
  }
}

?>
