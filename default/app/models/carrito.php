<?php

/**
 * Modelo para gestionar carrito
 */
class Carrito extends ActiveRecord
{
  const APODERADO = 1;
  const PROFESOR  = 2;
  const VALOR_DESPACHO = 3090;

   /*
    * @return $total_format int Devuelve total segun tipo de usuario APODERADO / PROFESOR
    */
    public function getTotalByTipoUsuario(){
	$productos_sql = new Productos;
	$result = null;
	$total = null;
	$productos_arr = json_decode($_POST["arr"]);
	foreach($productos_arr as $producto):
	    $result = $productos_sql->find($producto[1]);
	    $total += $this->total($result->valor);
	endforeach;
	return $total;
    }

    /*
    * @return $productos array Devuelve Lista de productos
    */
    public function getListaProductos(){
	$productos_apo = new EstablecimientoProyecto();
	$productos_pro = new Productos();
	$establecimientos = new Establecimientos();
	$productos = array();
	$result = null;
	$total_format = null;
	$total = null;
	$productos_format = array();
	Session::set("carrito", $_POST["arr"]);
	$productos_arr = json_decode($_POST["arr"]);
	$i=0;
	$array_hijo = array();
	$alumnos = new Alumnos();
	foreach(Session::get("hijos") as $key => $hijo):
	    foreach($productos_arr as $producto):
		if($hijo["id"] == $producto[0]){
		    if(Session::get("tipo") == $this::APODERADO){
			$est = $alumnos->find($hijo["id"])->establecimiento_id;
			$result = $productos_apo->find_by_sql("SELECT ep.curso_id, ep.rbd, ep.producto_id, pt.nombre, pt.valor, pt.imagen, pt.nivel
							      FROM establecimiento_proyecto as ep 
							      INNER JOIN productos pt ON (pt.id = ep.producto_id)
							      INNER JOIN proyectos py ON (py.id = ep.proyecto_id)
							      WHERE rbd = ". $establecimientos->find($est)->rbd."
							      AND   curso_id = ". $alumnos->find($hijo["id"])->curso ."
							      AND producto_id =". $producto[1]."");
			$caso = $this->casos($result->curso_id, $result->rbd, $result->producto_id);
			switch($caso):
			    case "normal":
				$productos_format[$i]["imagen"] = datatableAcciones::getImagen($result->imagen);
			    break;
			    case "ohiggins":
				$productos_format[$i]["imagen"] = datatableAcciones::getImagen("Se_Protagonista/pack_ohiggins.png", 140);
			    break;
			endswitch;
		    }else{
			$result = $productos_apo->find_by_sql("SELECT p.nivel_id, p.nivel, p.id as producto_id, p.nombre, p.valor, p.imagen, p.proyecto
							      FROM productos as p
							      WHERE p.nivel_id = ". $alumnos->find($hijo["id"])->curso ."
							      AND p.id =". $producto[1]."");
			$productos_format[$i]["imagen"] = datatableAcciones::getImagen($result->imagen);
		    }
		    $productos_format[$i]["descripcion"] = $result->nombre." ".$result->nivel." ".$result->proyecto;
		    $productos_format[$i]["tipo"] = (Session::get("tipo")==1)?"Licencia":"Texto";
		    $total = $this->total($result->valor);
		    $total_format += $total;
		    $productos_format[$i]["total"] = $this->formatNumeros($total);
		    $productos_format[$i]["boton"] = datatableAcciones::getBtnCarrito($producto[0], $result->producto_id);
		    $total += $total_format;
		    $i++;
		}
	    endforeach;
	endforeach;
	    
	
	$total = $this->valorDespacho($total_format);
	$subtotal_decimal = round($total / 1.19);
	$subtotal = $this->formatNumeros($subtotal_decimal);
	$iva = round($subtotal_decimal * 0.19);
	$iva = $this->formatNumeros($iva);
	$total = $this->formatNumeros($total);
	$productos["data"] = datatableAcciones::getTotal($i, $productos_format, $subtotal, $iva, $total);
	return  $productos;
    }
    
    /**
     * Devuelve el caso de algun establecimiento con un producto en particular
     * @param string $caso
     * @param integer $rbd
     * @param integer $producto
     */
    public function casos($curso, $rbd, $producto){
	$caso = "normal";
	
	if($curso == 8 && $rbd == 2200 && $producto == 360){
	    $caso = "ohiggins";
	}
	return $caso;
    }

    /**
     *
     **/
    public function total($result){
	$total_format = null;
	switch(Session::get('tipo')):
	    case $this::APODERADO:
	       $total_format = $result;
	    break;
	    case $this::PROFESOR:
		$total_format = $result * 0.5;
	    break;
	endswitch;
	return $total_format;
    }

    public function formatNumeros($valor){
	$numero = number_format($valor, 0 , ' , ' ,  '.');
	return $numero;
    }

    public function valorDespacho($monto){
	switch(Session::get('tipo')):
	    case $this::APODERADO:
	       $monto = $monto;
	    break;
	    case $this::PROFESOR:
		$monto = $monto + $this::VALOR_DESPACHO;
	    break;
	endswitch;

	return $monto;
    }

    public static function generarOrden($n)
    {
      //Generamos la orden de la compra
      //SM1701281611
      $key = '';
      $pattern = '1234567890';
      $max = strlen($pattern)-1;
      for($i=0;$i < $n;$i++) $key .= $pattern{mt_rand(0,$max)};
      $cadena = 'SM'.$key;
      $orden = (New WebpayTransaccion)->find_by_BuyOrder($cadena);
      if(empty($orden)){
        return $cadena;
      }
      else{
        self::generarOrden($n);
      }
    }
}


?>
