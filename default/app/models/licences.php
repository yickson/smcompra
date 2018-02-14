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

    public function getLicenciasActivas()
    {
      //Obtiene todas las licencias que estan activas
      $datos = (New Licences)->find_all_by_sql("SELECT l.id, l.codigo, l.tipo, l.estado, u.nombre as usuario, a.nombre as alumno, p.nombre as producto FROM licences l INNER JOIN usuarios u ON (u.id = l.usuario_id) INNER JOIN alumnos a ON (a.id = l.alumno_id) INNER JOIN productos p ON (p.id = l.producto_id) WHERE l.estado = 1 ");
      return $datos;
    }

    public function ratios_licencias()
    {
      $colegios = array();
      $datos = (New Licences)->find_all_by_sql("SELECT e.rbd, e.nombre, a.curso, a.establecimiento_id, a.id as alumno FROM licences l INNER JOIN alumnos a ON (a.id = l.alumno_id) INNER JOIN establecimientos e ON (e.id = a.establecimiento_id) GROUP BY e.nombre, a.curso");
      $i = 0;
      foreach ($datos as $key => $valor) {
        $licencias = (New Licences)->find_by_sql("SELECT COUNT(*) as total FROM alumnos WHERE curso = $valor->curso AND establecimiento_id = $valor->establecimiento_id");
        $venta = (New Licences)->find_by_sql("SELECT COUNT(*) as licVendidas FROM alumnos a INNER JOIN licences l ON (l.alumno_id = a.id) WHERE curso = $valor->curso AND establecimiento_id = $valor->establecimiento_id AND l.estado = 1");
        $colegios[$i]['rbd'] = $valor->rbd;
        $colegios[$i]['colegio'] = $valor->nombre;
        $colegios[$i]['curso'] = (New Cursos)->find($valor->curso)->nombre;
        $colegios[$i]['total'] = $licencias->total;
        $colegios[$i]['ventas'] = $venta->licVendidas;
        $colegios[$i]['fill'] = ROUND(($venta->licVendidas/$licencias->total)*100, 2);
        $i++;
      }

      return $colegios;
    }
}


?>
