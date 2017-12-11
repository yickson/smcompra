<?php

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
    foreach ($alumnos as $key => $valor) {
      $alumno = (New Alumnos)->find_by_rut($valor['rut']);
      var_dump($alumno->nombre, $alumno->id);
      echo '<br>';
    }
    $this->apoderado = Session::get('iduser');
    $this->alumno = $alumno;
    //var_dump($this->apoderado);
    View::select(null, null);
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
      $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
						   (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, pa.alumno_id as id_alumno
						   FROM productos as p
						   INNER JOIN profesor_alumnos pa ON (pa.producto_id = p.id and pa.usuario_id = $id_usuario)
						   ORDER BY pa.alumno_id ASC");

      $this->data = $alumnos;
      View::select(null,"json");
  }
}

?>
