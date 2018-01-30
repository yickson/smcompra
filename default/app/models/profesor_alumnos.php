<?php

/**
 * Modelo auxiliar
 */
class ProfesorAlumnos extends ActiveRecord
{
  
    public function DesactivarTexto($array_textos){
	foreach(json_decode($array_textos) as $texto):
	    
	    $texto = (New ProfesorAlumnos)->find_by_sql('SELECT * FROM profesor_alumnos WHERE usuario_id ='.$_COOKIE["clienteSM"].
		                                        ' AND producto_id ='.$texto[1].' AND alumno_id ='.$texto[0]);
	    						
	    $texto->estado = true;
	    $texto->save();
	endforeach;
    }
    
    public function getFullData(){
	$data = $this->find_all_by_sql("SELECT es.rbd, es.nombre as colegio, u.id as id_profesor, u.nombre as profesor, 
					(SELECT COUNT(al.id) FROM alumnos as al WHERE al.apoderado_id = u.id) as hijos 
					FROM profesor_alumnos as pa 
					INNER JOIN usuarios u ON (pa.usuario_id = u.id)
					INNER JOIN alumnos a ON (a.apoderado_id = u.id and a.id = pa.alumno_id)
					INNER JOIN establecimientos es ON (es.id =  a.establecimiento_id)
					GROUP BY pa.usuario_id");
	
	return $data;
    }
}


?>
