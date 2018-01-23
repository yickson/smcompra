<?php

//require "/home/servic57/smcompra/app/libs/php_excel.php";

class Cron {
	
	protected static $_config ;
	
	public function __construct()
	{
        
    $conexion = mysqli_connect("localhost", "root", "", "smcompras"); 
    
    $fecha_actual = date("Y-m-d 16:00:01", strtotime("-2 day"));
    $fecha_anterior = date("Y-m-d 16:00:02" , strtotime("-3 day"));
    $sql = mysqli_query($conexion, "SELECT pa.id, p.nombre as producto, p.codigo as sap, ROUND(p.valor*0.5) as precio, u.nombre as usuario, a.nombre as alumno, r.nombre as region, co.nombre as comuna, d.calle 
				    FROM profesor_alumnos pa INNER JOIN usuarios u ON (u.id = pa.usuario_id) 
				    INNER JOIN alumnos a ON (a.id = pa.alumno_id)
				    INNER JOIN productos p ON (p.id = pa.producto_id) 
				    INNER JOIN pedidos pe ON (pe.producto_id = pa.producto_id)
				    INNER JOIN direcciones d ON (d.id_usuario = u.id) 
				    INNER JOIN regiones r ON (r.id = d.id_region) 
				    INNER JOIN comunas co ON (co.id = r.id) 
				    WHERE pa.estado = 1 (pe.fecha BETWEEN '".$fecha_anterior."' AND '".$fecha_actual."' )");
    
    Load::lib("php_excel");
    $objPHPExcel = New PHPExcel;
   // $informe = new Pedidos();
//    
//    foreach($informe as $data):
//	
//    endforeach;
    $objPHPExcel->setActiveSheetIndex(0);
    $columnaActive = 2;
    $definicion_celdas = array(
	"A" => "ID",
	"B" => "Codigo",
	"C" => "Fecha",
	"D" => "Nombre",
	"E" => "Rut",
	"F" => "Monto"
    );
    
    //Construccion cabeceras
    foreach($definicion_celdas as $columna => $valor):
	$objPHPExcel->getActiveSheet()->getColumnDimension("{$columna}")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", $valor);
    endforeach;
    
     print_r($informe = mysqli_fetch_assoc($sql));
   die();
    //Contruccion celdas
    while( $informe = mysqli_fetch_assoc($sql)){
	$objPHPExcel->getActiveSheet()->setCellValue("A{$columnaActive}", $informe["id"]);
	$objPHPExcel->getActiveSheet()->setCellValue("B{$columnaActive}", $informe["buyOrder"]);
	$objPHPExcel->getActiveSheet()->setCellValue("C{$columnaActive}", $informe["fecha"]);
	$objPHPExcel->getActiveSheet()->setCellValue("D{$columnaActive}", $informe["nombre"]);
	$objPHPExcel->getActiveSheet()->setCellValue("E{$columnaActive}", $informe["rut"]);
	$objPHPExcel->getActiveSheet()->setCellValue("F{$columnaActive}", $informe["monto"]);
	$columnaActive ++;
	}
    
    $filename = date("Y-m-d").".xlsx"; 
    header('Content-Type: application/vnd.ms-excel'); 
    header('Content-Disposition: attachment;filename="'.$filename.'"'); 
    header('Cache-Control: max-age=0'); 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
    $objWriter->save('/home/servic57/public_html/smcompra/files/informes/'.$filename.'');
    
    
     echo "exito";
        	
	}

}

$excel = new Cron();

?>