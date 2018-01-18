<?php

/**
 * Modelo para productos (Libros o licencias)
 */
class Productos extends ActiveRecord
{
    const APODERADO = 1;
    const PROFESOR  = 2;

    /**
     *
     * @return object $alumnos
     */
    public function getProductosByUsuario($hijos){
      $id_usuario = Session::get("iduser");
      $tipo = Session::get("tipo");
//      $hijos = Session::get("hijos");
      $sql = "";
      $sql2 = null;
      $i=1;
      $cantidad_hijos = count($hijos);
      $alumnos = null;
      switch($tipo):
	  case $this::APODERADO:
		foreach($hijos as $hijo):
		if($cantidad_hijos == 1){
		    $sql .= "ep.curso_id  = ".$hijo["curso"]." AND ep.rbd = ".$hijo["rbd"]."";
		    $sql2 .= "al.id = ".$hijo["id"]." ";
		}else{
		    if($i < $cantidad_hijos){
			$sql .= "(ep.curso_id = ".$hijo["curso"]." AND ep.rbd = ".$hijo["rbd"].") OR ";
			$sql2 .= "al.id = ".$hijo["id"]." OR ";
		    }else{
		       $sql .= "(ep.curso_id  = ".$hijo["curso"]."  AND ep.rbd = ".$hijo["rbd"].")";
		       $sql2 .= "al.id = ".$hijo["id"]." ";
		    }
		}
		$i++;
		endforeach;
	        $alumno = (new Productos)->find_all_by_sql("SELECT  *
							    FROM establecimiento_proyecto as ep
							    WHERE $sql");
		$lista_productos = new Productos();
		$alumnos = array();
		$i=0;
		foreach($hijos as $hijo):
		    foreach($alumno as $al):
			if($hijo["rbd"] == $al->rbd){
			    $lista_productos->find($al->producto_id);
			    $alumnos[$i]["rbd"] = $al->rbd;
			    $alumnos[$i]["id_producto"] = $al->producto_id;
			    $alumnos[$i]["proyecto"] = $lista_productos->proyecto;
			    $alumnos[$i]["asignatura"] = $lista_productos->nombre;
			    $alumnos[$i]["nivel"] = (new Cursos)->find($al->curso_id)->nombre;
			    $alumnos[$i]["img"] = $lista_productos->imagen;
			    $alumnos[$i]["tipo"] = $lista_productos->tipo;
			    $alumnos[$i]["valor"] = $lista_productos->valor;
			    $alumnos[$i]["id_alumno"] = $hijo["id"];
			    $alumnos[$i]["estado"] = (new Licences)->find_by_sql("SELECT estado FROM licences
				                                                  WHERE producto_id = ".$al->producto_id."
				                                                  AND alumno_id = ". $hijo["id"]."")->estado;
			    $i++;
			}
		    endforeach;
		endforeach;
	  break;
	  case $this::PROFESOR:
	        foreach($hijos as $hijo):
		    if($cantidad_hijos == 1){
			  $sql .= "pa.alumno_id = ".$hijo["id"]." ";
		      }else{
			  if($i < $cantidad_hijos){
			      $sql .= "pa.alumno_id = ".$hijo["id"]." OR ";
			  }else{
			     $sql .= "pa.alumno_id = ".$hijo["id"]." ";
			  }
		      }
		      $i++;
		endforeach;
	        $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
							   (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, pa.alumno_id as id_alumno, pa.estado
							    FROM productos as p
							    INNER JOIN profesor_alumnos pa ON (pa.producto_id = p.id and pa.usuario_id = $id_usuario and ($sql))
							    ORDER BY pa.alumno_id ASC");
	  break;
      endswitch;
    return $alumnos;
    }

    public function getProductosActivos()
    {
      $productos = (New Productos)->find_all_by_sql("SELECT pa.id, p.nombre as producto, p.codigo, u.nombre as usuario, a.nombre as alumno FROM profesor_alumnos pa INNER JOIN usuarios u ON (u.id = pa.usuario_id) INNER JOIN alumnos a ON (a.id = pa.alumno_id) INNER JOIN productos p ON (p.id = pa.producto_id) WHERE pa.estado = 1");
      return $productos;
    }
}


?>
