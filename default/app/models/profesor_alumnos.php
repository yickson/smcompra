<?php

/**
 * Modelo auxiliar
 */
class ProfesorAlumnos extends ActiveRecord
{
  
    public function DesactivarTexto($array_textos){
	foreach($array_textos as $texto):
	    $texto = (New ProfesorAlumnos)->find_by_sql('SELECT * FROM profesor_alumnos WHERE usuario_id ='.Session::get("iduser").
		                                        ' AND producto_id ='.$texto);
	    						
	    $texto->estado = true;
	    $texto->save();
	endforeach;
    }
}


?>
