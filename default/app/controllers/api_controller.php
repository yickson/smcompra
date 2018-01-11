<?php 
class apiController extends AppController{
    
    //Constantes
    const PROFESOR = 2;
    
    /**
     * 
     */
    public function cargaProfesorHijo(){
	//Seteo de variables de servidor
	ini_set('memory_limit', '-1');
        set_time_limit(0);
	error_reporting(1);
	
	//Libreria
	Load::lib("php_excel");
	
	//Ubicacion y carga de archivo
	$archivo = "../public/files/cargas_masivas/1carga_hijo_profesor.xlsx";
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
	
	//Instancias
	$establecimiento = new Establecimientos();
	
	
	//Seleccionador de Celdas
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:R1493');
	
	foreach ($excel_reader->getWorksheetIterator() as $test=>$worksheet):
            $i = 1;
            foreach ($worksheet->getRowIterator() as $fila=>$row):
		//Instancias
		$profesor  = new Usuarios();
		$alumno    = new Alumnos();
		$direccion = new Direcciones();
		$productos = new Productos();
		$profesor_alumno_producto = new ProfesorAlumnos();
		
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
			    
			} catch(PHPExcel_Exception $e) {
			     print_r($e->getMessage());die(); // no cells in row -> add nothing, just silently ignore
			}
		    endforeach;
		    
		    //Buscar Profesor
		    $profesor->find_by_rut($rut_p);
		    if($profesor->id != null){ 
			$alumno_id = $this->asignarAlumnos($alumno, $rut_h, $profesor->id, $nombre_h, $curso, $establecimiento->find_by_rbd($rbd)->id);
			
			//Asignamos el producto
			$productos->find_by_codigo($codigo);
			if($productos->id != null){
			    //si existe
			    //buscar producto asignado
			    $this->asignarProductos($profesor_alumno_producto, $profesor->id, $alumno_id, $productos->id);
			}else{
			    print_r("no existe producto");
			    $productos->descripcion = $nombre_pro;
			    $productos->tipo        = $this::PROFESOR;
			    $productos->nivel_id    = $curso;
			    $productos->codigo      = $codigo;
			    $productos->save();
			    $this->asignarProductos($profesor_alumno_producto, $profesor->id, $alumno_id, $productos->id);
			}
		    }else{
			
			//No existe profesor, lo creamos...
			$profesor->rut      = $rut_p;
			$profesor->nombre   = $nombre_pro;
			$profesor->tipo     = $this::PROFESOR;
			$profesor->telefono = $telefono; 
			$profesor->save();
			
			//Le asignamos una direccion
			$direccion->find_by_sql('SELECT * FROM direcciones WHERE calle = "'.$calle.'" AND id_region = "'.$region.'" AND id_comuna = "'.$comuna.'" AND numero = "'.$numero.'"');
			if($direccion->id != null){
			    // existe direccion no la volvemos a crear.
			}else{
			    $direccion->id_region  = $region;
			    $direccion->id_comuna  = $comuna;
			    $direccion->id_usuario = $profesor->id;
			    $direccion->calle      = $calle;
			    $direccion->numero     = $numero;
			    $direccion->save();
			}
			
			//Le asignamos la carga de Hijos
			$alumno_id = $this->asignarAlumnos($alumno, $rut_h, $profesor->id, $nombre_h, $curso, $establecimiento->find_by_rbd($rbd)->id);
			
			//Asignamos el producto
			$productos->find_by_codigo($codigo);
			if($productos->id != null){
			    //si existe
			    //buscar producto asignado
			    $this->asignarProductos($profesor_alumno_producto, $profesor->id, $alumno_id, $productos->id);
			}else{
			    $productos->descripcion = $producto;
			    $productos->tipo        = $this::PROFESOR;
			    $productos->nivel_id    = $curso;
			    $productos->codigo      = $codigo;
			    $productos->save();
			    $this->asignarProductos($profesor_alumno_producto, $profesor->id, $alumno_id, $productos->id);
			}
		    }
		}
		$i++;
		$primera = false;
	    endforeach;
	endforeach;
	$this->data = "Perfecto!";
	View::select(null, "json");
    }
    
    /**
     * 
     */
    public function cargaProductos(){
	Load::lib("php_excel");
	
	$archivo = "../public/files/cargas_masivas/1productos.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:C484');
	
	//Variables
	$primera = true;
	$codigo  = null; $nombre = null; $curso = null;
	
	//Instancias
	$producto = new Productos();
	
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
			
			try {
			     
			    //Codigo
			    if($colIndex == "A"){
				$codigo = $cell->getCalculatedValue();
			    }
			    
			    //Nombre Producto
			    if($colIndex == "B"){
				$nombre = $cell->getCalculatedValue();
			    }
			    
			    //Curso
			    if($colIndex == "C"){
				$curso = $cell->getCalculatedValue();
			    }
			    
			} catch(PHPExcel_Exception $e) {
			     print_r($e->getMessage());die();
			}
		    endforeach;
		    
		    //Verificamos que el producto no exista, si no existe lo agregamos
		    $producto->find_by_codigo($codigo);
		    
		    if($producto->id !=null){
			//existe
		    }else{
//			$producto->descripcion = $nombre;
//			$producto->curso       = $curso;
			echo "producto ". $codigo . " No existe";
		    }
		}
		$primera  = false;
	    endforeach;
	endforeach;
	$this->data = "Perfecto!";
	View::select(null, "json");
    }
    
    
    public function cargaLicenciasCL(){
	Load::lib("php_excel");
	
	$archivo = "../public/files/cargas_masivas/1carga_licencias_cl.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:E635');
	
	//Variables
	$primera = true;
	$rbd  = null; $rut = null; $nombre = null; $curso = null;
	
	//Instancias
	$establecimiento = new Establecimientos();
	
	foreach ($excel_reader->getWorksheetIterator() as $test=>$worksheet):
            $i = 1;
            foreach ($worksheet->getRowIterator() as $fila=>$row):
		
		//Instancias
		$alumno = new Alumnos();
		
		if(!$primera){
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(true);
		    foreach ($cellIterator as $cell):
		        $colIndex = $cell->getColumn();
			$rowIndex = $row->getRowIndex();
			$cell = $worksheet->getCell($colIndex . $rowIndex);
			
			try {
			     
			    //RBD
			    if($colIndex == "A"){
				$rbd = $cell->getCalculatedValue();
			    }
			    
			    //rut
			    if($colIndex == "C"){
				$rut = $cell->getCalculatedValue();
			    }
			    
			    //Nombre
			    if($colIndex == "D"){
				$nombre = $cell->getCalculatedValue();
			    }
			    
			    //curso
			    if($colIndex == "E"){
				$curso = $cell->getCalculatedValue();
			    }
			    
			} catch(PHPExcel_Exception $e) {
			     print_r($e->getMessage());die();
			}
		    endforeach;
		    
		    //Buscamos si existe alumno
		    $alumno->find_by_rut($rut);
		    if($alumno->id != null){
			//existe no hagas nada
		    }else{
			$alumno->nombre = $nombre;
			$alumno->rut    = $rut;
			$alumno->curso  = $curso;
			$alumno->establecimiento_id = $establecimiento->find_by_rbd($rbd)->id;
			$alumno->save();
		    }
		}
		$primera  = false;
	    endforeach;
	endforeach;
	$this->data = "Perfecto!";
	View::select(null, "json");
    }
    
    
    public function cargaEstProyectos(){
	Load::lib("php_excel");
	
	$archivo = "../public/files/cargas_masivas/establecimiento_poyecto.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:E635');
	
	//Variables
	$primera = true;
	$rbd  = null; $rut = null; $nombre = null; $curso = null;
	
	//Instancias
	$establecimiento = new Establecimientos();
	
	foreach ($excel_reader->getWorksheetIterator() as $test=>$worksheet):
            $i = 1;
            foreach ($worksheet->getRowIterator() as $fila=>$row):
		
		//Instancias
		$establecimiento_proyecto = new EstablecimientoProyecto();
		$producto = new Productos();
		
		if(!$primera){
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(true);
		    foreach ($cellIterator as $cell):
		        $colIndex = $cell->getColumn();
			$rowIndex = $row->getRowIndex();
			$cell = $worksheet->getCell($colIndex . $rowIndex);
			
			try {
			     
			    //RBD
			    if($colIndex == "A"){
				$rbd = $cell->getCalculatedValue();
			    }
			    
			    //Codigo
			    if($colIndex == "C"){
				$codigo = $cell->getCalculatedValue();
			    }
			    
			    //Nombre
			    if($colIndex == "D"){
				$nombre = $cell->getCalculatedValue();
			    }
			    
			    //Proyecto
			    if($colIndex == "E"){
				$proyecto = $cell->getCalculatedValue();
			    }
			    
			    //Asignatura
			    if($colIndex == "F"){
				$asignatura = $cell->getCalculatedValue();
			    }
			    
			    //Curso
			    if($colIndex == "G"){
				$curso = $cell->getCalculatedValue();
			    }
			    
			} catch(PHPExcel_Exception $e) {
			     print_r($e->getMessage());die();
			}
		    endforeach;
		    
		    //Buscamos si existe tupla en $establecimiento_proyecto
		    $producto->find_by_codigo($codigo);
		    if($producto->id != null){
			$establecimiento_proyecto->find_by_sql('SELECT * FROM establecimiento_proyecto WHERE rbd = "'.$rbd.'" AND curso_id="'.$curso.'" AND proyecto_id = "'.$proyecto.'" AND producto_id = '.$producto->id.'');
			if($establecimiento_proyecto->id != null){
			    //existe no hagas nada
			    //print_r("existe".$establecimiento_proyecto->id );
			}else{
			    //print_r("No existe".$establecimiento_proyecto->id );
			}
		    }else{
			print_r("no existe producto ".$codigo." ");
		    }
		}
		$primera  = false;
	    endforeach;
	endforeach;
	die();
	$this->data = "Perfecto!";
	View::select(null, "json");
    }
    
    /**
     * Asigna productos en la relacion de profesor alumnos
     * @param object  $profesor_alumno_producto
     * @param integer $profesor
     * @param integer $alumno
     * @param integer $productos
     */
    public function asignarProductos($profesor_alumno_producto, $profesor, $alumno, $productos){
	$profesor_alumno_producto->find_by_sql("SELECT *
						FROM profesor_alumnos 
						WHERE usuario_id = ".$profesor." 
						AND   alumno_id  = ".$alumno." 
						AND producto_id  = ".$productos."");
	if($profesor_alumno_producto->id != null){
	    print_r("ya existe!!!". $productos);
	    //si existe
	}else{
	    $profesor_alumno_producto->usuario_id = $profesor;
	    $profesor_alumno_producto->alumno_id = $alumno;
	    $profesor_alumno_producto->producto_id = $productos;
	    $profesor_alumno_producto->estado = "0";
	    $profesor_alumno_producto->save();
	}
    }
    
    /**
     * Relacion el alumno con el apoderado/profesor
     * @param object  $alumno
     * @param string  $rut_h
     * @param integer $profesor
     * @param string  $nombre_h
     * @param string  $curso
     * @param integer $establecimiento
     */
    public function asignarAlumnos($alumno, 
				   $rut_h, 
	                           $profesor, 
				   $nombre_h,
				   $curso,
				   $establecimiento)
    {
	$alumno->find_by_rut($rut_h);
	if($alumno->id != null){
	    // existe hijo
	    $alumno->apoderado_id = $profesor;
	    $alumno->save();
	}else{
	     //No existe hijo
	    $alumno->nombre = $nombre_h;
	    $alumno->rut    = $rut_h;
	    $alumno->curso  = $curso;
	    $alumno->establecimiento_id = $establecimiento;
	    $alumno->apoderado_id = $profesor;
	    $alumno->save();
	}
	return $alumno->id;
    }
} 
