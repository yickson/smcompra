<?php

require_once APP_PATH ."extensions/helpers/datatable_acciones.php";

/**
 * Controlador para obtener información de utilidad para el representante
 */
class RepresentanteController extends AppController
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
	$zona = (new RepresentanteZona)->find_by_representante(Session::get("id", "administrador"))->zona;
	$establecimientos = (new Establecimientos)->getEstByRepresentante($zona);
	
	
	$this->filtro_est = $establecimientos;
  }

  /**
   * Listar usuarios con hijos
   */
  public function data_representante()
  {
    $buscar = null;
    if(Input::hasPost("buscar")){
	$buscar = Input::post("buscar");  
    }
    $representante = new ProfesorAlumnos();
    $data = $representante->getFullData($buscar);
    $arr = array();
    $i=0;
    foreach($data as $r):
	$arr[$i]["rbd"] = $r->rbd;
	$arr[$i]["colegio"] = $r->colegio;
	$arr[$i]["profesor"] = $r->profesor;
	$arr[$i]["profesor_rut"] = $r->profesor_rut;
	$arr[$i]["alumno"] = datatableAcciones::getBtnHijos($r->id_profesor, $r->hijos);
	$arr[$i]["boton"] = "En construcción.";
	++$i;
    endforeach;
    $asd["data"] = $arr;
    $this->data = $asd;
    View::select(null, "json");
  }
  
  /**
   * Obtengo datos de los hijos del profesor
   */
  public function hijosProductos(){
    $id = Input::post("profesor");
    $profesor_model = (new ProfesorAlumnos)->find_all_by_usuario_id($id);
    $i=0;
    foreach($profesor_model as $p):
	$profesor[$i]["alumno_rut"]    = (new Alumnos)->find($p->alumno_id)->rut;
	$profesor[$i]["alumno_nombre"] = (new Alumnos)->find($p->alumno_id)->nombre;
	$profesor[$i]["producto"]      = (new Productos)->find($p->producto_id)->descripcion;
	$profesor[$i]["estado"]        = datatableAcciones::getBtnEstados($p->estado);
	$i++;
    endforeach;
    $this->data = $profesor;
    View::select(null,"json");
  }
}


?>
