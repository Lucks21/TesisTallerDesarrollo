<?php
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$rut = param("rut", ""); 
$clave = param("clave", ""); 
$campus = param("campus", ""); 
$numero = param("numero", ""); 
$accion = param("accion", "reserva");
$rut = format_rut($rut);

$d = new Database;
if ($accion == "reserva") {
	$sql = "EXECUTE sp_WEB_GENERA_RESERVA '$rut', '$clave', $numero, '$campus' ";
	$d->execute($sql);
	$row=$d->get_row();
	$error = $row['valor'];
	$fecha = $row['fecha'];
	$campus = $row['campus'];
} elseif ($accion == "anular") {
	$sql = "exec sp_WEB_anula_reserva '$rut', $numero ";
	$d->execute($sql);
	$row=$d->get_row();
	$error = $row['error'];
} else {
	echo "Acción no válida [script: registra_reserva]";
	exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Validación Reserva</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body LINK="#6666CC" VLINK="#3333FF" bgcolor="#CCCCFF" text="#003399" alink="#6666CC">
<script language="JavaScript">
function cta_usuario()
{ 
window.open("cta_acceso.php", "_new", "resizable=1,scrollbars=1 ,toolbar=1,location=0,menubar=0,status=0") 
} 
</script>

<table border="0" width="100%">
    <tr bgcolor="#003399"><td class="titulo" >&nbsp;Gestión de Reservas</td></tr>
    <tr><td height="26" background="../images/motivo.gif">&nbsp;</td></tr>

<?php 
// Anulacion de la reserva
if ($accion == "anular") {
	if ($error==0 ) { ?>
<tr><td height="26"> 
        <div align="right"><a href="cta_prestamos.php"><img border=0 src="../images/volver.jpg" ALT="volver a mi cuenta personal"></a>&nbsp;&nbsp;<a href="javascript:window.close()">
        		<img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>
        	</div>
      </td>
</tr>
<tr>
  <td>Su reserva se ha anulado en forma exitosa</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<?php 
} else {  // if error  ?>
<tr><td height="26"> 
        <div align="right"><a href="javascript:window.close()">
        		<img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>
        	</div>
      </td>
</tr>
<tr><td>Su reserva se ha anulado en forma exitosa</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<?php } // if error

exit;
} //if  accion
?>

<tr><td>&nbsp;</td></tr>		

<?php 
// validacion del resultado de la reserva 
if ($error==1) { ?>
      <tr><td ><strong>Usted no está autorizado a utilizar esta opción<br><br>
            </strong><small>Verifique que su información sea correcta (RUT 
            y clave). Si lo es, comuníquese con la Dirección de Bibliotecas.</small></td>
	  </tr>
<?php 
} //if

if ($error==2) { ?>
      <tr> 
   	     <td><big>Usuario no se encuentra vigente</big><br>
            <small>Sólo los usuarios vigentes pueden generar una reserva</small></td>
	  </tr>
<?php 
} //if

if ($error==9) { ?>
      <tr>
          <td width="100%"><big>Error al generar la reserva</big><br>
   		 <small>Por favor vuelva a intentarlo</small></td>
  		</tr>
<?php 
} //if

if ($error==10) { ?>
     <tr>
	    <td width="100%"><p align="center"><big>La reserva no fue generada</big><br>
	    <small>Usuario con un ejemplar en préstamo para el mismo título</small></td>
    </tr>
<?php 
} //if

if ($error==4) { ?>    
    <tr>
	    <td width="100%"><p align="center"><big>La reserva no fue generada</big><br>
	    <small>El libro fue prestado o cambio de estado mientras usted realizaba la reserva, por
   		 favor vuelva a intentarlo</small></td>
  </tr>
<?php 
} //if

if ($error==5) { ?>
    <tr> 
	    <td width="100%" class="texto-small-hi"><strong>La reserva no fue generada</strong><br><br>
	    Usuario excede la cantidad de reservas para esta materia. 
	    <br><br>Si desea puede consultar su <a href="javascript:cta_usuario()">cuenta corriente.</a></td>
  </tr>
<?php 
} //if

if ($error==6) { ?>
    <tr>
	    <td width="100%"><strong>La reserva no fue generada</strong>><br><br>
	    <small>Usuario excede la cantidad de reservas para este título</small></td>
    </tr>
<?php 
} //if

if ($error==3) { ?>
	 <tr>
   	 <td width="100%" class="texto-small"><strong>Su reserva se ha realizado en forma exitosa</strong>. <br><br>
   	 Si desea anular esta reserva debe ingresar a su <a href="javascript:cta_usuario()">cuenta 
            corriente.</a></td>
  </tr>
<?php 
} //if
?>
</table>
</body>
</html>
