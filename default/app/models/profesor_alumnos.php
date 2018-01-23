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
}


?>
