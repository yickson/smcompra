<?php

/**
 * Modelo de los templates para enviar los correos
 */
class Plantillas
{

  public function profesor($detalles, $direccion)
  {
    $contenido = '<table max-width="800" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td height="80" align="left" valign="middle" bgcolor="ffffff" style="font-family:Arial, Helvetica, sans-serif; color:#ffffff;">
          <div style="font-size:15px;">
            <b></b>
          </div>
          <div style="font-size:30px;">
            <b><img src="http://serviciosm.cl/img/smlogomin2.jpg" width="80" height="80" style="display:block;"></b>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <table width="650" cellpadding="0" cellspacing="0" align="center">
            <tr>
              <td width="650">
                <h2 align="center">Orden de compra</h2>
                <p></p>
              </td>
            </tr>
          </table>
          </td>
          <!-- 2 column layout with 10px spacing -->
          </tr>
          <tr>
          <td>
            <table width="650" cellpadding="0" cellspacing="0" align="center">
              <tr>
                <th width="325">
                  <h2>Método de pago</h2>
                </th>
                <th width="325">
                  <h2>Envío y dirección</h2>
                </th>
              </tr>
              <tr>
                <td width="295" align="center">WebPay</td>
                <td width="295">En 15 días hábiles a partir de la fecha de la compra
                    Dirección de envío
                    Calle: '.$direccion->calle.' '.$direccion->numero.'
                    Comuna: '.$direccion->comuna_nombre.', Región: '.$direccion->region_nombre.'</td>
              </tr>
            </table>
          </td>
          </tr>
          <br><br>
          <!-- 4 column layout with 0px spacing -->
          <tr>
          <td>
            <table width="650" cellpadding="0" cellspacing="0" align="center" border="1" style="font-family:Arial, Helvetica, sans-serif;>
              <tr>
                <th width="162">
                  <h3>Proyecto</h3>
                </th>
                <th width="162">
                  <h3>Asignatura</h3>
                </th>
                <th width="162">
                  <h3>Detalle</h3>
                </th>
                <th width="162">
                  <h3>Valor</h3>
                </th>
              </tr>
              </table>
              <table width="650" cellpadding="0" cellspacing="0" align="center" border="1" style="font-family:Arial, Helvetica, sans-serif;>
              <!-- Aquí se encuentra el bucle del los pedidos -->';

              $total = 0;
              foreach ($detalles as $value) {
                $contenido .= '<tr>';
                $contenido .= '<td width="162">'.$value->proyecto.'</td>';
                $contenido .= '<td width="162">'.$value->nombre.'</td>';
                $contenido .= '<td width="162">'.$value->descripcion.'</td>';
                $contenido .= '<td width="162">$'.number_format($value->valor, 0, ' ', '.').'</td></tr>';
                $total += $value->valor;
                if($value === end($detalles)){
                  $total += 3090; //Monto por el despacho fijo
                  $contenido .= '<tr>
                    <td></td>
                    <td></td>
                    <td>Total (Despacho incluido)</td>
                    <td>$'.number_format($total, 0, ' ', '.').'</td>
                  </tr>';
                }
              }
              $contenido .= '</table>';
  $contenido .= '
          </td>
          </tr>
          <!-- 1 column layout with 0px spacing -->
          <tr>
            <td align="center" valign="middle" style="padding-bottom:5px;">
              <img src="http://serviciosm.cl/img/divider.gif" width="650" height="28">
            </td>
          </tr>
          <tr>
          <td>
            <table width="650" cellpadding="0" cellspacing="0" align="center">
              <tr>
                <td width="650">
                  <p>¿Tiene alguna duda?
                  Este email contiene información personal --- Por favor, no lo reenvíe. Si tiene alguna llámenos al 600 381 13 12.

                  Llámenos al 600 381 13 12.

                  Ediciones SM Chile S.A. Coyancura 2283, Oficina 203, Providencia, Santiago.

                  </p>
                </td>
              </tr>
            </table>
          </td>
      </tr>
    </table>';

    return $contenido;
  }

  public function apoderado($detalles)
  {
    $lic3 = (New Alumnos)->caso_especial();
    $contenido = '<table max-width="800" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td height="80" align="left" valign="middle" bgcolor="ffffff" style="font-family:Arial, Helvetica, sans-serif; color:#ffffff;">
          <div style="font-size:15px;">
            <b></b>
          </div>
          <div style="font-size:30px;">
            <b><img src="http://serviciosm.cl/img/smlogomin2.jpg" width="80" height="80" style="display:block;"></b>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <table width="650" cellpadding="0" cellspacing="0" align="center">
            <tr>
              <td width="650">
                <h2 align="center">Orden de compra</h2>
                <p></p>
              </td>
            </tr>
          </table>
          </td>
          <!-- 2 column layout with 10px spacing -->
          </tr>
          <tr>
          <td>
            <table width="650" cellpadding="0" cellspacing="0" align="center">
              <tr>
                <th width="650">
                  <h2>Método de pago</h2>
                </th>
              </tr>
              <tr>
                <td width="650" align="center">WebPay</td>
              </tr>
            </table>
          </td>
          </tr>
          <br><br>
          <!-- 4 column layout with 0px spacing -->
          <tr>
          <td>
            <table width="650" cellpadding="0" cellspacing="0" align="center" border="1" style="font-family:Arial, Helvetica, sans-serif;>
              <tr>
                <th width="162">
                  <h3>Proyecto</h3>
                </th>
                <th width="162">
                  <h3>Asignatura</h3>
                </th>
                <th width="162">
                  <h3>Detalle</h3>
                </th>
                <th width="162">
                  <h3>Valor</h3>
                </th>
              </tr>
              <!-- Aquí se encuentra el bucle del los pedidos -->
              <table width="650" cellpadding="0" cellspacing="0" align="center" border="1" style="font-family:Arial, Helvetica, sans-serif;>';
              $total = 0;
              $i = 0;
              foreach ($detalles as $value) {
                $contenido .= '<tr>';
                $contenido .= '<td width="162">'.$value->proyecto.'</td>';
                $contenido .= '<td width="162">'.$value->nombre.'</td>';
                if($value->id == 360){
                  $contenido .= '<td width="162">'.$lic3[$i].'</td>';
                  $i++;
                }else{
                  $contenido .= '<td width="162">'.$value->codigo.'</td>'
                }

                $contenido .= '<td width="162">$'.number_format($value->valor, 0, ' ', '.').'</td></tr>';
                $total += $value->valor;
                if($value === end($detalles)){
                  $contenido .= '<tr>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td>$'.number_format($total, 0, ' ', '.').'</td>
                  </tr>';
                }
              }
              $contenido .= '</table>';
  $contenido .= '
            </table>
          </td>
          </tr>
          <!-- 1 column layout with 0px spacing -->
          <tr>
            <td align="center" valign="middle" style="padding-bottom:5px;">
              <img src="http://serviciosm.cl/img/divider.gif" width="650" height="28">
            </td>
          </tr>
          <tr>
          <td>
            <table width="650" cellpadding="0" cellspacing="0" align="center">
              <tr>
                <td width="650">
                  <p>¿Tiene alguna duda?
                  Este email contiene información personal --- Por favor, no lo reenvíe. Si tiene alguna llámenos al 600 381 13 12.

                  Llámenos al 600 381 13 12.

                  Ediciones SM Chile S.A. Coyancura 2283, Oficina 203, Providencia, Santiago.

                  </p>
                </td>
              </tr>
            </table>
          </td>
      </tr>
    </table>';

    return $contenido;
  }
}


?>
