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
     public static function getImagen($imagen, $with = 70)
     {
        $img = "<img src='".PUBLIC_PATH."img/productos/$imagen'/ width='".$with."'>";
         return $img;
     }

     /**
     * @param $i int
     * @return array Devuelve Sub-total, Iva, Total, y Boton Pagar
     */
     public static function getTotal($i, $array,$subtotal,$total)
     {
	 (Session::get('tipo')==1)?$items = $i+3:$items = $i+3;
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
			$array[$j]["tipo"] = "Despacho";
			$array[$j]["total"] = "$".number_format($despacho, 0 , ' , ' ,  '.');
			$array[$j]["boton"] = " ";
		break;
		case $i+2:
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

    /**
     * Devuelve botones para datatable de lista de usuarios
     * @param object $usuario
     */
    public static function getBtnUsuarios($usuario){
	$img_mapa = "<img src='".PUBLIC_PATH."img/map1.png' width=45'>";
	$btn = "<button data-id='".$usuario->id."' class='btn btn-info hijos'> <span class='badge'>$usuario->hijos</span></button>
		<span class='direccion_usuario' data-id='".$usuario->id."'>$img_mapa</span>";
	return $btn;
    }

    public static function getBtnMail($rut, $orden){
      //$usuario = (New Usuarios)->find_by_rut($rut);
      //print_r($usuario); die();
      $btn = "<button id = '".$rut."' value = '".$orden."' class='btn btn-mail btn-success'> <i class='fa fa-envelope' aria-hidden='true'></i></button>";
      return $btn;
    }
}
