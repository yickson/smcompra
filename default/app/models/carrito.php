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
	$productos_arr = explode(",", $_POST["arr"]);
	foreach($productos_arr as $producto):
	    $result = $productos_sql->find($producto);
	    $total += $this->total($result->valor);
	endforeach;
	return $total;
    }
    
    /*
    * @return $productos array Devuelve Lista de productos
    */
    public function getListaProductos(){
	$productos_sql = new Productos;
	$productos = array();
	$result = null;
	$total = null;
	$productos_format = array();
	$productos_arr = explode(",", $_POST["arr"]);
	$i=0;
	foreach($productos_arr as $producto):
	    $result = $productos_sql->find($producto);
	    $productos_format[$i]["imagen"] = datatableAcciones::getImagen($result->imagen);
	    $productos_format[$i]["descripcion"] = $result->descripcion;
	    $productos_format[$i]["cantidad"] = 1;
	    $total += $this->total($result->valor);
	    $productos_format[$i]["total"] = $this->formatNumeros($total);
	    $productos_format[$i]["boton"] = datatableAcciones::getBtnCarrito($result->id);
	    $i++;
	endforeach;

	$subtotal_decimal = round($total / 1.19);
	$subtotal = $this->formatNumeros($subtotal_decimal);
	$iva = round($subtotal_decimal * 0.19);
	$iva = $this->formatNumeros($iva);
	$total = $this->valorDespacho($total);
	$total = $this->formatNumeros($total);
	$productos["data"] = datatableAcciones::getTotal($i, $productos_format, $subtotal, $iva, $total);
	return  $productos;
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
}


?>
