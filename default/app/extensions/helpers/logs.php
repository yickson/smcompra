<?php

/**
 * Clase agregar botones a la tabla datatable
 * @category   Kumbia
 * @package    BtnAcciones
 */
class Logs
{

    public function __construct() {

    }

    public function accesoCarrito(){
	$nombre_archivo = "log_acceso_carrito.txt";
	if(file_exists($nombre_archivo))
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "* ID_USUARIO: ".Session::get("iduser")."\n";
		$mensaje .= "* HIJOS: ".json_encode(Session::get("alumno"));
		$mensaje .= "* NAVEGADOR/VERSION: ".json_encode(Session::get("navegador"));
	    }
	}

	else
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "* ID_USUARIO: ".Session::get("iduser")."\n";
		$mensaje .= "* HIJOS: ".json_encode(Session::get("alumno"));
		$mensaje .= "* NAVEGADOR/VERSION: ".json_encode(Session::get("navegador"));
	    }
	}

	if($archivo = fopen($nombre_archivo, "a"))
	{
	    if(fwrite($archivo, date("Y-m-d H:i:s"). " ". $mensaje. "\n"))
	    {

	    }
	    else
	    {

	    }

	    fclose($archivo);
	}
    }
    
     public function envioWebpay(){
	$nombre_archivo = "log_envio_webpay.txt"; 
	if(file_exists($nombre_archivo))
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.\n";
		$mensaje .= "* ID_USUARIO: ".$_COOKIE["clienteSM"]."\n";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "* ID_USUARIO: ".$_COOKIE["clienteSM"]."\n";
	    }
	}

	else
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.\n";
		$mensaje .= "* ID_USUARIO: ".$_COOKIE["clienteSM"]."\n";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "* ID_USUARIO: ".$_COOKIE["clienteSM"]."\n";
	    }
	}
	if($archivo = fopen($nombre_archivo, "a"))
	{
	    if(fwrite($archivo, date("Y-m-d H:i:s"). " ". $mensaje. "\n"))
	    {

	    }
	    else
	    {

	    }

	    fclose($archivo);
	}
    }
    
    
    public function restConnecta($data){
	$nombre_archivo = "log_rest_connecta.txt"; 
	if(file_exists($nombre_archivo))
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.\n";
		$mensaje .= "* ID_USUARIO: ".$_COOKIE["clienteSM"]."\n";
		$mensaje .= "* HIJOS: ".$_COOKIE["alumnosSM"]."\n";
		$mensaje .= "* RESULT: ".json_encode($data)."\n";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "* ID_USUARIO: ".$_COOKIE["clienteSM"]."\n";
		$mensaje .= "* HIJOS: ".$_COOKIE["alumnosSM"]."\n";
		$mensaje .= "* RESULT: ".json_encode($data)."\n";
	    }
	}

	else
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.\n";
		$mensaje .= "* ID_USUARIO: ".$_COOKIE["clienteSM"]."\n";
		$mensaje .= "* HIJOS: ".$_COOKIE["alumnosSM"]."\n";
		$mensaje .= "* RESULT: ".json_encode($data)."\n";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "* ID_USUARIO: ".$_COOKIE["clienteSM"]."\n";
		$mensaje .= "* HIJOS: ".$_COOKIE["alumnosSM"]."\n";
		$mensaje .= "* RESULT: ".json_encode($data)."\n";
	    }
	}
	if($archivo = fopen($nombre_archivo, "a"))
	{
	    if(fwrite($archivo, date("Y-m-d H:i:s"). " ". $mensaje. "\n"))
	    {

	    }
	    else
	    {

	    }

	    fclose($archivo);
	}
    }
    

    public function respuestaWebpay($token, $status, $resp){
	$nombre_archivo = "log_resp_webpay.txt";
	if(file_exists($nombre_archivo))
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.";
		$mensaje .= "* ID_USUARIO: ".$resp->sessionId."\n";
		$mensaje .= "* TOKEN: ".$token."\n";
		$mensaje .= "* STATUS: ".$status->responseCode."\n";
		$mensaje .= "* ORDENCOMPRA: ".$status->buyOrder."\n";
		$mensaje .= "* MONTO: ".$status->amount."\n";
		$mensaje .= "* CARRITO: ".$_COOKIE["carritoSM"];
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "* ID_USUARIO: ".$resp->sessionId."\n";
		$mensaje .= "* TOKEN: ".$token."\n";
		$mensaje .= "* STATUS: ".$status->responseCode."\n";
		$mensaje .= "* BuyOrder: ".$_COOKIE["buyOrderSM"]."\n";
		$mensaje .= "* CARRITO: ".$_COOKIE["carritoSM"];
	    }
	}

	else
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.";
		$mensaje .= "* ID_USUARIO: ".$resp->sessionId."\n";
		$mensaje .= "* TOKEN: ".$token."\n";
		$mensaje .= "* STATUS: ".$status->responseCode."\n";
		$mensaje .= "* ORDENCOMPRA: ".$status->buyOrder."\n";
		$mensaje .= "* MONTO: ".$status->amount."\n";
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "* ID_USUARIO: ".$resp->sessionId."\n";
		$mensaje .= "* TOKEN: ".$token."\n";
		$mensaje .= "* STATUS: ".$status->responseCode."\n";
		$mensaje .= "* ORDENCOMPRA: ".$status->buyOrder."\n";
		$mensaje .= "* MONTO: ".$status->amount."\n";
	    }
	}

	if($archivo = fopen($nombre_archivo, "a"))
	{
	    if(fwrite($archivo, date("Y-m-d H:i:s"). " ". $mensaje. "\n"))
	    {

	    }
	    else
	    {

	    }

	    fclose($archivo);
	}
    }

     public function respuestaWebpayException($token, $status, $ex, $resp){
	$nombre_archivo = "log_resp_webpay_ex.txt";
	if(file_exists($nombre_archivo))
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.";
		$mensaje .= "* ID_USUARIO: ".$resp->sessionId."\n";
		$mensaje .= "* TOKEN: ".$token."\n";
		$mensaje .= "* STATUS: ".$status->responseCode."\n";
		$mensaje .= "* ORDENCOMPRA: ".$status->buyOrder."\n";
		$mensaje .= "* MONTO: ".$status->amount."\n";
		$mensaje .= "* Exception: ".$ex;
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "* ID_USUARIO: ".$resp->sessionId."\n";
		$mensaje .= "* TOKEN: ".$token."\n";
		$mensaje .= "* STATUS: ".$status."\n";
		$mensaje .= "* Exception: ".$ex."\n";
		$mensaje .= "* BuyOrder: ".$_COOKIE["buyOrderSM"];
	    }
	}

	else
	{
	    if(empty(Session::get("iduser"))){
		$mensaje  = "El Archivo $nombre_archivo se ha creado \n";
		$mensaje .= "La session de usuario se ha perdido y no se ha podido crear un registro.";
		$mensaje .= "* ID_USUARIO: ".$resp->sessionId."\n";
		$mensaje .= "* TOKEN: ".$token."\n";
		$mensaje .= "* STATUS: ".$status->responseCode."\n";
		$mensaje .= "* ORDENCOMPRA: ".$status->buyOrder."\n";
		$mensaje .= "* MONTO: ".$status->amount."\n";
		$mensaje .= "* Exception: ".$ex;
	    }else{
		$mensaje  = "El Archivo $nombre_archivo se ha modificado \n";
		$mensaje .= "* ID_USUARIO: ".$resp->sessionId."\n";
		$mensaje .= "* TOKEN: ".$token."\n";
		$mensaje .= "* STATUS: ".$status."\n";
		$mensaje .= "* Exception: ".$ex."\n";
		$mensaje .= "* BuyOrder: ".$_COOKIE["buyOrderSM"];
	    }
	}

	if($archivo = fopen($nombre_archivo, "a"))
	{
	    if(fwrite($archivo, date("Y-m-d H:i:s"). " ". $mensaje. "\n"))
	    {

	    }
	    else
	    {

	    }

	    fclose($archivo);
	}
    }
}
