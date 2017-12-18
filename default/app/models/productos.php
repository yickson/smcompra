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
      $id_usuario = $_POST["id_usuario"];
      $tipo = $_POST["tipo"];
      $alumnos = null;
      switch($tipo):
	  case $this::APODERADO: 
	        $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
							  (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, li.alumno_id as id_alumno, li.estado
							   FROM productos as p
							   INNER JOIN licences li ON (li.producto_id = p.id and li.usuario_id = $id_usuario)
							   ORDER BY li.alumno_id ASC");
	  break;
	  case $this::PROFESOR:
	        $alumnos = (new Productos)->find_all_by_sql("SELECT p.id as id_producto, p.nombre as asignatura, p.proyecto, p.nivel, p.imagen as img,
							   (SELECT nombre FROM productos_tipo WHERE id = p.tipo) as tipo, p.valor, pa.alumno_id as id_alumno, li.estado
							    FROM productos as p
							    INNER JOIN profesor_alumnos pa ON (pa.producto_id = p.id and pa.usuario_id = $id_usuario)
							    ORDER BY pa.alumno_id ASC");
	  break;
      endswitch;
    return $alumnos;
    }
}


?>
