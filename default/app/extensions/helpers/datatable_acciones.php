<?php

/**
 * Clase agregar botones a la tabla datatable
 * @category   Kumbia
 * @package    BtnAcciones
 */
class datatableAcciones
{
   
    public function __construct() {

    }
    /**
     * Visualiza sugerencia en base a lo escrito
     *
     * @param int $id id registro
     */
     public static function getBtnCarrito($alumno, $id)
     {
       $botones = "<button  data-alumno='$alumno' data-prod='$id' class='btn btn-danger eliminar' data-toggle='modal' data-target='#Buscar'>
                     Eliminar <i class='fa fa-trash'></i>
                   </button>";
         return $botones;
     }
     
     /**
     * @param $imagen string
     * @return string Devuelve etiqueta imagen, segun ruta enviada.
     */
     public static function getImagen($imagen)
     {
        $img = "<img src='".PUBLIC_PATH."img/productos/$imagen'/ width='70'>";
         return $img;
     }
     
     /**
     * @param $i int
     * @return array Devuelve Sub-total, Iva, Total, y Boton Pagar
     */
     public static function getTotal($i, $array,$subtotal,$iva,$total)
     {
	 (Session::get('tipo')==1)?$items = $i+3:$items = $i+4;
	 $despacho = 3090;
	 $tabla = array();
	 for($j=$i; $j<$items; $j++):
	    if(Session::get('tipo') == 1){
	     switch($j):
		case $i+0:
		     $array[$j]["imagen"] = " ";
		     $array[$j]["descripcion"] = " ";
		     $array[$j]["tipo"] = "Sub-Total";
		     $array[$j]["total"] = "$".$subtotal;
		     $array[$j]["boton"] = " ";

		break;
		case $i+1:
		     $array[$j]["imagen"] = " ";
		     $array[$j]["descripcion"] = " ";
		     $array[$j]["tipo"] = "IVA";
		     $array[$j]["total"] = "$".$iva;
		     $array[$j]["boton"] = " ";
		break;
		case $i+2:
		     $array[$j]["imagen"] = " ";
		     $array[$j]["descripcion"] = " ";
		     $array[$j]["tipo"] = "Total";
		     $array[$j]["total"] = "$".$total;
		     $array[$j]["boton"] = "<button  class='btn btn-primary pagar'>
					    Pagar <i class=''></i>
					    </button>";
		break;
	    endswitch;
	 }else{
	     switch($j):
		case $i+0:
		     $array[$j]["imagen"] = " ";
		     $array[$j]["descripcion"] = " ";
		     $array[$j]["tipo"] = "Sub-Total";
		     $array[$j]["total"] = "$".$subtotal;
		     $array[$j]["boton"] = " ";

		break;
		case $i+1:
		     $array[$j]["imagen"] = " ";
		     $array[$j]["descripcion"] = " ";
		     $array[$j]["tipo"] = "IVA";
		     $array[$j]["total"] = "$".$iva;
		     $array[$j]["boton"] = " ";
		break;
		case $i+2:
			$array[$j]["imagen"] = " ";
		        $array[$j]["descripcion"] = " ";
			$array[$j]["tipo"] = "Despacho";
			$array[$j]["total"] = "$".number_format($despacho, 0 , ' , ' ,  '.');
			$array[$j]["boton"] = " ";
		break;
		case $i+3:
		     $array[$j]["imagen"] = " ";
		     $array[$j]["descripcion"] = " ";
		     $array[$j]["tipo"] = "Total";
		     $array[$j]["total"] = "$".$total;
		     $array[$j]["boton"] = "<button  class='btn btn-primary despacho' data-toggle='modal'>
					     Verifica tu Dirección <i class=''></i>
					    </button>";
		break;
	    endswitch;
	 }
	 endfor;
         return $array;
     }
    
     /**
      * Devuelve boton para datatable de pedidos
      * @return string | $btn
      */
    public static function getBtnPedidos()
    {
	$btn = "<button class='btn btn-info'>2</button>";
	return $btn;
    }
}
