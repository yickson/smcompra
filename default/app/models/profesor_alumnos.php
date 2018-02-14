<?php

/**
 * Modelo auxiliar
 */
class ProfesorAlumnos extends ActiveRecord
{

    public function DesactivarTexto($array_textos){
	foreach(json_decode($array_textos) as $texto):

	    $texto_model = (New ProfesorAlumnos)->find_by_sql('SELECT * FROM profesor_alumnos WHERE usuario_id ='.$_COOKIE["clienteSM"].
		                                        ' AND producto_id ='.$texto[1].' AND alumno_id ='.$texto[0]);
	    $texto_model->estado = true;
	    $texto_model->save();
	endforeach;
    }

    public function getFullData($buscar){
    	$zona = new RepresentanteZona();
	$zona->find_by_sql("SELECT zona FROM representante_zona WHERE representante = ".Session::get("id", "administrador"));
	$est   = "";
	$pro   = "";
	$and   = "";
	$where = "";
	$i = 1;

	//Filtros
	if(!empty($buscar["establecimientos"])){
	    $cantidad_est = count($buscar["establecimientos"]);
	    foreach($buscar["establecimientos"] as $e):
		if($cantidad_est == 1){
		    $est = " AND a.establecimiento_id = $e ";
		}else{
		    if($i < $cantidad_est){
			$and = " AND (";
			$est .= "a.establecimiento_id = $e  OR ";
		    }else{
			$est .= "a.establecimiento_id = $e )";
		    }
		}
		$i++;
	    endforeach;
	}

	if(!empty($buscar["profesor"])){
	    $pro = " AND nombre  like '%".$buscar["profesor"]."%'";
	}

	if(!empty($buscar["estado"])){
	    if($buscar["estado"] == 1){
		$where = "WHERE pa.estado = 1";
	    }

	    if($buscar["estado"] == 0){
		$where = "WHERE pa.estado = 0";
	    }
	}

	$data = $this->find_all_by_sql("SELECT es.rbd, es.nombre as colegio, u.id as id_profesor, u.rut as profesor_rut, u.nombre as profesor,
					(SELECT COUNT(al.id) FROM alumnos as al WHERE al.apoderado_id = u.id) as hijos
					FROM profesor_alumnos as pa
					INNER JOIN usuarios u ON (pa.usuario_id = u.id $pro)
					INNER JOIN alumnos a ON (a.apoderado_id = u.id $and $est)
					INNER JOIN establecimientos es ON (es.id = a.establecimiento_id and es.zona = $zona)
					$where
					GROUP BY pa.usuario_id");

	return $data;
    }

  public function ratios_colegios()
  {
    $colegios = array();
    $datos = (New ProfesorAlumnos)->find_all_by_sql("SELECT e.rbd, e.nombre, a.curso, a.establecimiento_id FROM profesor_alumnos pa INNER JOIN alumnos a ON (a.id = pa.alumno_id) INNER JOIN establecimientos e ON (e.id = a.establecimiento_id) GROUP BY e.nombre, a.curso");
    $i = 0;
    foreach ($datos as $key => $valor) {
      $textos = (New ProfesorAlumnos)->find_by_sql("SELECT COUNT(*) as total FROM profesor_alumnos pa INNER JOIN alumnos a ON (a.id = pa.alumno_id) WHERE a.curso = $valor->curso AND a.establecimiento_id = $valor->establecimiento_id");
      $venta = (New ProfesorAlumnos)->find_by_sql("SELECT COUNT(*) as total FROM profesor_alumnos pa INNER JOIN alumnos a ON (a.id = pa.alumno_id) WHERE a.curso = $valor->curso AND a.establecimiento_id = $valor->establecimiento_id AND pa.estado = 1");
      $colegios[$i]['rbd'] = $valor->rbd;
      $colegios[$i]['colegio'] = $valor->nombre;
      $colegios[$i]['curso'] = (New Cursos)->find($valor->curso)->nombre;
      $colegios[$i]['total'] = $textos->total;
      $colegios[$i]['ventas'] = $venta->total;
      $colegios[$i]['fill'] = ROUND(($venta->total/$textos->total)*100, 2);
      $i++;
    }

    return $colegios;
  }
}


?>
