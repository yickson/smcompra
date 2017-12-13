<?php

/**
 * Clase agregar botones a la tabla datatable
 * @category   Kumbia
 * @package    BtnAcciones
 */
class datatableAcciones
{
    /**
     * Visualiza sugerencia en base a lo escrito
     *
     * @param int $id id registro
     */
     public static function getBtnCarrito($id)
     {
       $botones = "<button  data-id='$id' class='btn btn-danger ver' data-toggle='modal' data-target='#Buscar'>
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
	 $items = $i+4;
	 $despacho = 3090;
	 for($j=$i; $j<$items; $j++):
	    $array[$j]["imagen"] = " ";
	    $array[$j]["descripcion"] = " ";
	    switch($j):
		case $i+0:
		     $array[$j]["cantidad"] = "Sub-Total";
		     $array[$j]["total"] = "$".$subtotal;
		     $array[$j]["boton"] = " ";

		break;
		case $i+1:
		     $array[$j]["cantidad"] = "IVA";
		     $array[$j]["total"] = "$".$iva;
		     $array[$j]["boton"] = " ";
		break;
		case $i+2:
		    if(Session::get('tipo') == 2){
			$array[$j]["cantidad"] = "Despacho";
			$array[$j]["total"] = "$".number_format($despacho, 0 , ' , ' ,  '.');
			$array[$j]["boton"] = " ";
		    }
		break;
		case $i+3:
		     $array[$j]["cantidad"] = "Total";
		     $array[$j]["total"] = "$".$total;
		     $array[$j]["boton"] = "<button  class='btn btn-primary pagar'>
					     Ir a entrega y pago de productos <i class=''></i>
					    </button>";
		break;
	    endswitch;
	 endfor;
         return $array;
     }
}
