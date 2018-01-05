<?php

require "/home/servic57/smcompra/app/libs/php_excel.php";

class Cron {
	
	protected static $_config ;
	
	public function __construct()
	{
        
    $conexion = mysqli_connect("localhost", "servic57_compra", "Dun#X6JG00b*", "servic57_smcompra"); 
    
    $sql = mysqli_query("SELECT p.id, 
					  (SELECT nombre FROM usuarios WHERE id = p.usuario_id) as nombre, 
					  (SELECT rut FROM usuarios WHERE id = p.usuario_id) as rut,
					   wp.buyOrder , wp.monto, wp.fecha
					   FROM pedidos as p
					   INNER JOIN webpay_transaccion wp ON (p.transaccion_id = wp.id and codigoRespuesta = 0)", $conexion);
					   
    $informe = mysql_fetch_array($sql);
    print_r($informe);die();
    $objPHPExcel = New PHPExcel;
   // $informe = new Pedidos();
//    
//    foreach($informe as $data):
//	
//    endforeach;
    $objPHPExcel->setActiveSheetIndex(0);
    $columnaActive = 2;
    $definicion_celdas = array(
	"A" => "Codigo",
	"B" => "Fecha",
	"C" => "Nombre",
	"D" => "Rut",
	"E" => "Monto"
    );
    
    //Construccion cabeceras
    foreach($definicion_celdas as $columna => $valor):
	$objPHPExcel->getActiveSheet()->getColumnDimension("{$columna}")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", $valor);
    endforeach;
    
    //Contruccion celdas
    foreach($informe as $data):
	$objPHPExcel->getActiveSheet()->setCellValue("A{$columnaActive}", $data["buyOrder"]);
	$objPHPExcel->getActiveSheet()->setCellValue("B{$columnaActive}", $data["fecha"]);
	$objPHPExcel->getActiveSheet()->setCellValue("C{$columnaActive}", $data["nombre"]);
	$objPHPExcel->getActiveSheet()->setCellValue("D{$columnaActive}", $data["rut"]);
	$objPHPExcel->getActiveSheet()->setCellValue("E{$columnaActive}", $data["monto"]);
	$columnaActive ++;
    endforeach;
    header('Content-Type: application/vnd.ms-excel'); 
    header('Content-Disposition: attachment;filename="text.xlsx"'); 
    header('Cache-Control: max-age=0'); 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
    $objWriter->save('/home/servic57/public_html/smcompra/files/informes/text.xlsx');
    
    
     echo "exito";
        	
	}

}

$excel = new Cron();

?>