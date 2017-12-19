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
	$total_format = null;
	$total = null;
	$productos_format = array();
	Session::set("carrito", $_POST["arr"]);
	$productos_arr = explode(",", $_POST["arr"]);
	$i=0;
	foreach($productos_arr as $producto):
	    $result = $productos_sql->find($producto);
	    $productos_format[$i]["imagen"] = datatableAcciones::getImagen($result->imagen);
	    $productos_format[$i]["descripcion"] = $result->nombre." ".$result->nivel." ".$result->proyecto;
	    $productos_format[$i]["tipo"] = (Session::get("tipo")==1)?"Licencia":"Texto";
	    $total = $this->total($result->valor);
	    $total_format += $total;
	    $productos_format[$i]["total"] = $this->formatNumeros($total);
	    $productos_format[$i]["boton"] = datatableAcciones::getBtnCarrito($result->id);
	    $total += $total_format;
	    $i++;
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
