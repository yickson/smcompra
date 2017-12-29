<?php

/**
 * Modelo para la gestion de correos
 */
class Email
{

  public static function enviar($destino, $detalles, $direccion)
  {
    $destino = "yicksonr@gmail.com";
    $encoding = "utf-8";
    $from_name = "Ediciones SM";
    $from_mail = "no-responder@smcompra.cl";
    // Preferences for Subject field
    $subject_preferences = array(
        "input-charset" => $encoding,
        "output-charset" => $encoding,
        "line-length" => 76,
        "line-break-chars" => "\r\n"
    );

    // Mail header
    $header = "Content-type: text/html; charset=".$encoding." \r\n";
    $header .= "From: ".$from_name." <".$from_mail."> \r\n";
    $header .= "MIME-Version: 1.0 \r\n";
    $header .= "Content-Transfer-Encoding: 8bit \r\n";
    $header .= "Date: ".date("r (T)")." \r\n";
    $header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

    $asunto = "Detalles de su compra";
    $html = (New Plantillas)->profesor($detalles, $direccion); //Carga de template

    mail($destino, $asunto, $html, $header) or die("Su mensaje no pudo enviarse.");
  }
}



?>
