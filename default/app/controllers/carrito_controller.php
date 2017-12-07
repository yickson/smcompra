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
      $id_profesor = $_POST["id_profesor"];
      $alumnos = (new Alumnos)->find_all_by_sql("");
      
      $this->data = $alumnos;
      View::select(null,"json");
  }
}

?>
