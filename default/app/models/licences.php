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
    
    public function getLicenciasPorAlumno(){
	$carrito = json_decode(Session::get("carrito"));
	$alimnos_lic = array();
	$licencia = array();
	$alumnos = Session::get("alumno");
	$j=0;
	foreach($alumnos as  $al):
	    $alimnos_lic[$j]["rut"] = $al->rut;
	    foreach($carrito as $carr):
		    $items = (New Licences)->find_by_sql("SELECT * FROM licences WHERE alumno_id =".$al->id." and producto_id =".$carr[1]);
		    if($items->producto_id == $carr[1] and $items->alumno_id == $carr[0]){
			$licencia[] = $items->codigo;
			$items->estado = true; //Se desactiva el estado de la licencia del producto del alumno
			$items->update();
		    }
	    endforeach;
	    $alimnos_lic[$j]["licencias"] = $licencia;
	    $licencia = array();
	    $j++;
	endforeach;
	return $alimnos_lic;
    }
}


?>
