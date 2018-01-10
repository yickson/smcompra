<?php 
class apiController extends AppController{
    
    //Constantes
    const PROFESOR = 2;
    
    public function cargaProfesorHijo(){
	
	Load::lib("php_excel");
	
	$archivo = "../public/files/cargas_masivas/1carga.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	
	//Variables
	$primera    = true;
	$fila_vacia = false;
	$rbd        = null; $colegio  = null; $rut_p      = null;
	$nombre_pro = null; $nombre_h = null; $rut_h      = null;
	$curso      = null; $codigo   = null;  $producto  = null;
	$telefono   = null; $numero   = null; $casa_depto = null;
	$calle      = null; $comuna   = null; $region     = null;
	
		
	//Seleccionador de Celdas
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:R1493');
	
	foreach ($excel_reader->getWorksheetIterator() as $test=>$worksheet):
            $i = 1;
            foreach ($worksheet->getRowIterator() as $fila=>$row):
		if(!$primera){
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(true);
		    foreach ($cellIterator as $cell):
		        $colIndex = $cell->getColumn();
			$rowIndex = $row->getRowIndex();
			$cell = $worksheet->getCell($colIndex . $rowIndex);
			
			//Lectura del contenido de las celdas
			 try {
			     
			    //RBD
			    if($colIndex == "B"){
				$rbd = $cell->getCalculatedValue();
			    }

			    //Colegio
			    if($colIndex == "C"){
				$colegio = $cell->getCalculatedValue();
			    }
			    
			    //Rut Profesor
			    if($colIndex == "D"){
				$rut_p = $cell->getCalculatedValue();
			    }
			    
			    //Nombre Profesor
			    if($colIndex == "E"){
				$nombre_pro = $cell->getCalculatedValue();
			    }
			    
			    //Nombre Hijo
			    if($colIndex == "F"){
				$nombre_h = $cell->getCalculatedValue();
			    }
			    
			    //Rut Hijo
			    if($colIndex == "G"){
				$rut_h = $cell->getCalculatedValue();
			    }
			    
			    //Curso
			    if($colIndex == "H"){
				$curso = $cell->getCalculatedValue();
			    }
			    
			    //Codigo SAP
			    if($colIndex == "I"){
				$codigo = $cell->getCalculatedValue();
			    }
			    
			    //Producto
			    if($colIndex == "J"){
				$producto = $cell->getCalculatedValue();
			    }
			    
			    //Telefono
			    if($colIndex == "K"){
				$telefono = $cell->getCalculatedValue();
			    }
			    
			    //Numero
			    if($colIndex == "M"){
				$numero = $cell->getCalculatedValue();
			    }
			    
			    //Casa -Depto
			    if($colIndex == "N"){
				$casa_depto = $cell->getCalculatedValue();
			    }
			    
			    //Calle
			    if($colIndex == "P"){
				$calle = $cell->getCalculatedValue();
			    }
			    
			    //Comuna
			    if($colIndex == "Q"){
				$comuna = $cell->getCalculatedValue();
			    }
			    
			    //Region
			    if($colIndex == "R"){
				$region = $cell->getCalculatedValue();
			    }
			    
			    //Instancias
			    $profesor = new Usuarios();
			    $alumno   = new Alumnos();
			    $producto = new Productos();
			    
			    
			    $profesor->find_by_rut($rut_p);
			    
			    if($profesor->id != null){
				print_r("existe");
			    }else{
				echo $nombre_pro;
				$profesor->rut    = $rut_p;
				$profesor->nombre = $nombre_pro;
				$profesor->tipo   = $this::PROFESOR;
				$profesor->save();
				print_r("no existe");
			    }
			    die();
			    
			    
			} catch(PHPExcel_Exception $e) {
			     // no cells in row -> add nothing, just silently ignore
			}
		    endforeach;
		}
		$primera = false;
	    endforeach;
	endforeach;
	die();
	$this->data = "OK0";
	View::select(null, "json");
    }
} 
