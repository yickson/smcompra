<?php

/**
 * Modelo para gestionar las licencias de los productos
 */
class Licences extends ActiveRecord
{
  
    public function DesactivarLicencia($array_licencias){
	foreach($array_licencias as $licencia):
	    $licencia = (New Licences)->find_by_sql('SELECT * FROM licences WHERE usuario_id ='.Session::get("iduser").
		                                        ' AND producto_id ='.$licencia);
	    						
	    $licencia->estado = true;
	    $licencia->save();
	endforeach;
	
    }
}


?>
