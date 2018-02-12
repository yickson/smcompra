<?php 
class apiController extends AppController{
    
    //Constantes
    const TEXTO = 1;
    const LICENCIA  = 2;
    const PROFESOR = 2;
    const APODERADO  = 1;
    
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
	$archivo = "../public/files/cargas_masivas/2carga_hijo_profesor.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	
	//Variables Globales
	$primera    = true;
	$fila_vacia = false;
	
	//Instancias Globales
	$establecimiento = new Establecimientos();
	
	//Seleccionador de Celdas
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:S240');
	
	foreach ($excel_reader->getWorksheetIterator() as $test=>$worksheet):
            $i = 1;
	    $x = 1;
            foreach ($worksheet->getRowIterator() as $fila=>$row):
		//Variables locales
		//$rbd        = null; $colegio  = null; $rut_p      = null;
		$nombre_pro = null; $nombre_h = null; $rut_h      = null;
		$curso      = null; $codigo   = null;  $producto  = null;
		$telefono   = null; $numero   = null; $casa_depto = null;
		$calle      = null; $comuna   = null; $region     = null;
		
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
			$x++;
			 print_r("ya existe!!! profesor". $profesor->id);
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
			    $productos->tipo        = $this::TEXTO;
			    $productos->nivel_id    = $curso;
			    $productos->codigo      = $codigo;
			    $productos->save();
			    $this->asignarProductos($profesor_alumno_producto, $profesor->id, $alumno_id, $productos->id);
			}
			
			$direccion->find_by_sql('SELECT * FROM direcciones WHERE calle = "'.$calle.'" AND id_region = "'.$region.'" AND id_comuna = "'.$comuna.'" AND numero = "'.$numero.'" AND id_usuario="'.$profesor->id.'" ');
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
		    }else{
			 print_r("no existe!!! profesor ". $profesor->id);
			//No existe profesor, lo creamos...
			$profesor->rut      = $rut_p;
			$profesor->nombre   = $nombre_pro;
			$profesor->tipo     = $this::PROFESOR;
			$profesor->telefono = $telefono; 
			$profesor->save();
			
			//Le asignamos una direccion
			$direccion->find_by_sql('SELECT * FROM direcciones WHERE calle = "'.$calle.'" AND id_region = "'.$region.'" AND id_comuna = "'.$comuna.'" AND numero = "'.$numero.'" AND id_usuario="'.$profesor->id.'" ');
			if($direccion->id != null){
			    $direccion->id_region  = $region;
			    $direccion->id_comuna  = $comuna;
			    $direccion->id_usuario = $profesor->id;
			    $direccion->calle      = $calle;
			    $direccion->numero     = $numero;
			    $direccion->create();
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
			    $productos->tipo        = $this::TEXTO;
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
	print_r("total:".$x);
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
    
    
    public function cargaLicenciasES(){
	Load::lib("php_excel");
	
	$archivo = "../public/files/cargas_masivas/1carga_licencias_es.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:C3326');
	
	//Variables
	$primera = true;
	$producto  = null; $codigo = null;
	
	//Instancias
	
	foreach ($excel_reader->getWorksheetIterator() as $test=>$worksheet):
            $i = 1;
            foreach ($worksheet->getRowIterator() as $fila=>$row):
		
		//Instancias
		$producto = new Productos();
		$licencia = new Licences();
		
		if(!$primera){
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(true);
		    foreach ($cellIterator as $cell):
		        $colIndex = $cell->getColumn();
			$rowIndex = $row->getRowIndex();
			$cell = $worksheet->getCell($colIndex . $rowIndex);
			
			try {
			     
			    //Producto
			    if($colIndex == "A"){
				$codigo_sap = $cell->getCalculatedValue();
			    }
			    
			    //Licencia
			    if($colIndex == "C"){
				$codigo_lic = $cell->getCalculatedValue();
			    }
			    
			} catch(PHPExcel_Exception $e) {
			     print_r($e->getMessage());die();
			}
		    endforeach;
		    
		    //Buscamos si existe el producto
		    $producto->find_by_codigo($codigo_sap);
		    if($producto->id !=null){
			//Buscamos si existe la licencia
			$licencia->find_by_codigo($codigo_lic);
			if($licencia->id != null){
			    //existe no hagas nada
			}else{
			    //Buscamos alumno para asignacion de licencia
			    $alumno   = new Alumnos();
			    $alumno->find_by_sql("SELECT al.*, l.producto_id as producto_id_licencia 
						    FROM alumnos as al 
						    INNER JOIN establecimiento_proyecto ep ON 
						    ((al.curso = ".$producto->nivel_id." AND ep.curso_id = ".$producto->nivel_id.") 
						     AND ep.rbd = (SELECT e.rbd 
								   FROM establecimientos as e
								   WHERE e.id = al.establecimiento_id)
								   AND (ep.rbd != 1788 AND ep.rbd != 14379 AND ep.rbd != 25654) ) 
						    LEFT JOIN licences l ON (l.producto_id = ep.producto_id AND al.id = l.alumno_id) 
						    WHERE al.curso = ".$producto->nivel_id." 
						    AND l.alumno_id is null
						    AND ep.producto_id = ".$producto->id."
						    AND al.apoderado_id is null limit 1");
			   
			    if($alumno->producto_id_licencia != null){
				    //existe alumno no lo necesito para agregar mas licencias
			    }else{
				$licencia->codigo      = $codigo_lic;
				$licencia->tipo        = "espania";
				$licencia->producto_id = $producto->id;
				$licencia->alumno_id   = $alumno->id;
				$licencia->estado      = "0";
				$licencia->save();
			    }
			}
		    }else{
			
		    }
		    
		    
		}
		$primera  = false;
	    endforeach;
	endforeach;
	$this->data = "Perfecto!";
	View::select(null, "json");
    }
    
    
    public function cargaAlumnos(){
	Load::lib("php_excel");
	
	$archivo = "../public/files/cargas_masivas/2carga_alumnos.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:E995');
	
	//Variables
	$primera = true; 
	$rbd  = null; 
	$nombre = null; 
	$curso = null; 
	$rut = null;
	
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
			    
			    //Rut
			    if($colIndex == "C"){
				$rut = $cell->getCalculatedValue();
			    }
			    
			    //Nombre
			    if($colIndex == "D"){
				$nombre = $cell->getCalculatedValue();
			    }
			    
			    //Curso
			    if($colIndex == "E"){
				$curso = $cell->getCalculatedValue();
			    }
			    
			    
			} catch(PHPExcel_Exception $e) {
			     print_r($e->getMessage());die();
			}
		    endforeach;
		    
		    //Buscamos si existe el alumno
		    $alumno->find_by_rut($rut);
		    if($alumno->id !=null){
			print_r("existo");
			//si existe el alumno
		    }else{
			$alumno->nombre = $nombre;
			$alumno->rut = $rut;
			$alumno->curso = $curso;
			$alumno->establecimiento_id = $establecimiento->find_by_rbd($rbd)->id;
			$alumno->save();
			
		    }
		    $i++;
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
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:H107');
	
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
		    
		    //Buscamos si existe tupla en $producto
		    $producto->find_by_codigo($codigo);
		    if($producto->id != null){
			//Buscamos si existe tupla en $establecimiento_proyecto
			$establecimiento_proyecto->find_by_sql('SELECT * FROM establecimiento_proyecto WHERE rbd = "'.$rbd.'" AND curso_id="'.$curso.'" AND proyecto_id = "'.$proyecto.'" AND producto_id = '.$producto->id.'');
			if($establecimiento_proyecto->id != null){
			    //existe no hagas nada
			    //print_r("existe".$establecimiento_proyecto->id );
			}else{
			    $establecimiento_proyecto->rbd = $rbd;
			    $establecimiento_proyecto->curso_id = $curso;
			    $establecimiento_proyecto->proyecto_id = $proyecto;
			    $establecimiento_proyecto->producto_id = $producto->id;
			    $establecimiento_proyecto->save();
			}
		    }else{
			$producto->descripcion = $nombre;
			$producto->tipo        = $this::LICENCIA;
			$producto->nivel_id    = $curso;
			$producto->codigo      = $codigo;
			$producto->save();
			$establecimiento_proyecto->find_by_sql('SELECT * FROM establecimiento_proyecto WHERE rbd = "'.$rbd.'" AND curso_id="'.$curso.'" AND proyecto_id = "'.$proyecto.'" AND producto_id = '.$producto->id.'');
			if($establecimiento_proyecto->id != null){
			    //existe no hagas nada
			}else{
			    $establecimiento_proyecto->rbd = $rbd;
			    $establecimiento_proyecto->curso_id = $curso;
			    $establecimiento_proyecto->proyecto_id = $proyecto;
			    $establecimiento_proyecto->producto_id = $producto->id;
			    $establecimiento_proyecto->save();
			}
		    }
		}
		$primera  = false;
	    endforeach;
	endforeach;
	$this->data = "Perfecto!";
	View::select(null, "json");
    }
    
    /**
     * Se cargan las zonas representates y supervisores
     */
    public function cargaEstZona(){
	Load::lib("php_excel");
	
	$archivo = "../public/files/cargas_masivas/establecimiento_zona.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:E3960');
	
	//Variables Globales
	$primera = true;
	$rbd  = null; $zona = null; $representante = null; $supervisor = null;
	
	foreach ($excel_reader->getWorksheetIterator() as $test=>$worksheet):
            $i = 1;
            foreach ($worksheet->getRowIterator() as $fila=>$row):
		
		//Instancias
		$establecimiento = new Establecimientos();
		$representante_zona = new RepresentanteZona() ;
		$supervisor_representante = new SupervisorRepresentante();
		
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
				$rbd = trim($cell->getCalculatedValue());
			    }
			    
			    //Zona
			    if($colIndex == "C"){
				$zona = trim(str_replace('.',',',($cell->getCalculatedValue())));
			    }
			    
			    //Representante
			    if($colIndex == "D"){
				$representante = trim($cell->getCalculatedValue());
			    }
			    
			    //Supervisor
			    if($colIndex == "E"){
				$supervisor = trim($cell->getCalculatedValue());
			    }
			    
			    
			} catch(PHPExcel_Exception $e) {
			     print_r($e->getMessage());die();
			}
		    endforeach;
		    
		    //Buscamos si existe tupla en $establecimiento
		    $establecimiento->find_by_rbd($rbd);
		    if($establecimiento->id != null){
			$zona_model          = new Zonas();
			$zona_model->find_by_valor($zona);
			if($zona_model->id != null){
			    $establecimiento->zona = $zona_model->id;
			    $establecimiento->save();
			    
			    $representante_model = new Representantes(); 
			    $representante_model->find_by_nombre($representante);
			    if($representante_model->id != null){
				$representante_zona->representante = $representante_model->id;
				$representante_zona->zona = $zona_model->id;
				try{
				    $representante_zona->save();
				}catch(Exception $ex){
				   
				}
				$supervisor_model = new Supervisor();
				$supervisor_model->find_by_nombre($supervisor);
				if($supervisor_model->id != null){
				    $supervisor_representante->supervisor    = $supervisor_model->id;
				    $supervisor_representante->representante = $representante_model->id;
				    try{
					$supervisor_representante->save();
				    }catch(Exception $ex){

				    }
				}
			    }
			}
		    }
		}
		$primera  = false;
	    endforeach;
	endforeach;
	$this->data = "Perfecto!";
	View::select(null, "json");
    }
    
    
    
    
    /**
     * Se cargan las zonas representates y supervisores
     */
    public function cargaSeguimiento(){
	Load::lib("php_excel");
	
	$archivo = "../public/files/seguimientos/seguimientos_4.xlsx";
        $tipo = PHPExcel_IOFactory::identify($archivo);
        $excel = PHPExcel_IOFactory::createReader($tipo);
        $excel_reader = $excel->load($archivo);
	//$excel_reader->setActiveSheetIndex(0)->rangeToArray('A1:E3960');
	
	//Variables Globales
	$primera = true;
	
	foreach ($excel_reader->getWorksheetIterator() as $test=>$worksheet):
            $i = 1;
            foreach ($worksheet->getRowIterator() as $fila=>$row):
		
		//Instancias
		$despacho = new Despacho();
		$orden_compra  = null; $transporte = null; $ot = null;
		
		if(!$primera){
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(true);
		    foreach ($cellIterator as $cell):
		        $colIndex = $cell->getColumn();
			$rowIndex = $row->getRowIndex();
			$cell = $worksheet->getCell($colIndex . $rowIndex);
			
			try {
			     
			    //Orden de Compra
			    if($colIndex == "C"){
				$orden_compra = trim($cell->getCalculatedValue());
			    }
			    
			    //Transporte
			    if($colIndex == "I"){
				$transporte = trim(str_replace('.',',',($cell->getCalculatedValue())));
			    }
			    
			    //Codigo OT
			    if($colIndex == "J"){
				
				$ot = trim($cell->getCalculatedValue());
			    }
			    
			    
			} catch(PHPExcel_Exception $e) {
			     print_r($e->getMessage());die();
			}
		    endforeach;
		    
		    //Buscamos si existe tupla en $establecimiento
		    $despacho->find_by_orden_compra($orden_compra);
		    if($despacho->id != null and $orden_compra != "REVISAR"){
			//Existe entonces no se debe guardar no debe existir duplicidad
			print_r("ya existe ".$orden_compra." / ");
		    }else{
			//No existe entonces guardamos
			
			$despacho->orden_compra = $orden_compra;
			$despacho->transporte   = $this->getTransporte($transporte);
			$despacho->ot           = $ot;
			$despacho->save();
		    }
		}
		$primera  = false;
	    endforeach;
	endforeach;
	$this->data = "Perfecto!";
	View::select(null, "json");
    }
    
    public function getTransporte($transporte){
	$transporte_model = new Transporte();
	$transporte_model->find_by_alias($transporte);
	if($transporte_model->id != null){
	    $transporte = $transporte_model->id;
	}else{
	    $transporte = 1;
	}
	return $transporte;
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
