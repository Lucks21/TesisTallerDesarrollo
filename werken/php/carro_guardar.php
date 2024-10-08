<?php
require  "../include/session.php";
session_init_nocache();

require "../include/database.php";
require "../include/functions.php";

$rut = ($_SESSION['rut']) ? $_SESSION['rut'] : param("rut",""); 
$sid = session_id();


$rut = format_rut($rut);
if ($rut && $sid) {
	
	$d = new Database;
	$sql = "sp_WEB_carro_solicitud_material 'guardar', '$rut', '$sid', null, null, null, ";
	$sql .= " null, null, null, null, null, null, null, null " ;
	$d->execute($sql);
	$row = $d->get_row();
	$error = $row['valor_retorno'];
	$nro_items = $row['nro_items'];
} else {
	$error = 9;
}

?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Carro de Solicitd de Material</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<blockquote>
<table width="620" border="0" cellpadding="4" cellspacing="2"> 
<tr bgcolor="#003399"><td class="titulo" colspan="4">Material solicitado</td></tr>
<tr background="../images/motivo.gif"> 	<td align="left" valign="top" height="5" colspan="4">&nbsp; </td></tr>
<tr><td height="26" colspan="4"> 
      <div align="right"><a href="cta_prestamos.php"><img border=0 src="../images/volver.jpg" ALT="volver a mi cuenta personal"></a>&nbsp;&nbsp;
      <a href="javascript:window.close()">
	  <img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>
	  </div>
	  </td>
</tr>
<?php 
if ($error == 1 || $error==9) { ?>
<tr><td class="url-hi"><strong>El material solicitado no pudo ser ingresado
	en la base, inténtelo nuevamente</strong></td></tr>	
</table>
<?php 
exit;
}
if ($nro_items==0) {  ?>
<tr><td class="texto-small"><strong>Atención! : no hay elementos en el carro,<br> por favor
<a href="solicitud_material.php?accion=inicio">ingrese por lo menos una o más solicitudes.</a></strong></td></tr>	
</table>
<?php
exit;
} //if

?>
<tr>
<td class="texto-small">Estimado Usuario, su SOLICITD DE MATERIAL se registró exitosamente (con <?php echo $nro_items; ?> ítems).
Se recomienda que consulte periódicamente el estado de la solicitud 
en su cuenta corriente.<p>
Si lo desea puede :
<ul>
<li>Ingresar más <a href="solicitud_material.php?accion=inicio">solicitudes de material.</a>
<li>Ver el estado de las <a href="solicitud_material_consulta.php">solicitudes enviadas previamente</a>.
<li>Volver a su <a href="cta_prestamos.php">Cuenta Corriente.</a>
<li><a href="javascript:window.close()">Cerrar</a> esta ventana.
</ul></td>
</tr>
</table>
</blockquote>
</body>
</html>
