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
    public function getProductosByUsuario(){
      $id_usuario = Session::get("iduser");
      $tipo = Session::get("tipo");
      $hijos = Session::get("hijos");
      $sql = "";
      $i=1;
      $cantidad_hijos = count($hijos);
      $alumnos = null;
      switch($tipo):
	  case $this::APODERADO: 
		foreach($hijos as $hijo):
		if($cantidad_hijos == 1){
		    $sql .= "li.alumno_id = ".$hijo["id"]." ";
		}else{
		    if($i < $cantidad_hijos){
			$sql .= "li.alumno_id = ".$hijo["id"]." OR ";
		    }else{
		       $sql .= "li.alumno_id = ".$hijo["id"]." ";
		    }
		}
		$i++;
		endforeach;
	        $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
							  (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, li.alumno_id as id_alumno, li.estado
							   FROM productos as p
							   INNER JOIN licences li ON (li.producto_id = p.id and li.usuario_id = $id_usuario and ($sql))
							   ORDER BY li.alumno_id ASC");
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
