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
		    $sql .= "p.nivel_id = ".$hijo["curso"]." AND ep.rbd = ".$hijo["rbd"]."";
		    $sql2 .= "al.id = ".$hijo["id"]." ";
		}else{
		    if($i < $cantidad_hijos){
			$sql .= "(p.nivel_id = ".$hijo["curso"]." AND ep.rbd = ".$hijo["rbd"].") OR ";
			$sql2 .= "al.id = ".$hijo["id"]." OR ";
		    }else{
		       $sql .= "(p.nivel_id = ".$hijo["curso"]."  AND ep.rbd = ".$hijo["rbd"].")";
		       $sql2 .= "al.id = ".$hijo["id"]." ";
		    }
		}
		$i++;
		endforeach;
		
	        $alumnos = (new Productos)->find_all_by_sql("SELECT  ep.id, ep.rbd, ep.producto_id as id_producto, p.proyecto,
							    (SELECT nombre FROM proyectos WHERE id = ep.proyecto_id) as proyecto, p.nombre as asignatura, ep.curso_id as nivel, p.imagen as img, 
							    (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, al.id as id_alumno,
							    (SELECT estado FROM licences WHERE producto_id = ep.producto_id AND alumno_id = al.id) as estado
							    FROM establecimiento_proyecto as ep 
							    INNER JOIN productos p ON (ep.producto_id = p.id AND ep.curso_id = p.nivel_id) 
							    INNER JOIN alumnos al ON (al.curso = ep.curso_id AND ($sql2))
							    WHERE $sql");
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
}


?>
