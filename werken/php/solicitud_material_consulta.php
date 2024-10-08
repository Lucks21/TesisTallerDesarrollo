<?php
require  "../include/session.php";
session_init_nocache();

require "../include/database.php";
require "../include/functions.php";

$rut = ($_SESSION['rut']) ? $_SESSION['rut'] : param("rut",""); 

$rut=format_rut($rut);

$d = new Database;
$sql = " sp_web_solicitud_material '$rut' ";
$d->execute($sql);

?>
<html>
<head>
<TITLE>Solicitud de Material</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#CCCCFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" link="#6666CC" vlink="#3333FF" alink="#6666CC" text="#003399">
<script language="javascript" src="valida_solicitud_material.js" type="text/javascript"></script>

<blockquote> 
<table width="620" border="0" cellspacing="0" cellpadding="4">
<tr><td bgcolor="#000099"><font face="Arial, Helvetica, sans-serif" size="2"><font face="Arial, Helvetica, sans-serif" size="2"><img src="../images/motivo1.gif" width="18" height="28" align="absmiddle"></font><font color="#FFFFFF"><b>SOLICITUDES DE MATERIAL EFECTUADAS</b></font></font></td></tr>
<tr background="../images/motivo.gif"> 	<td align="left" valign="top" height="5" colspan="4">&nbsp; </td></tr>
<tr><td height="26" colspan="3"><div align="right"><a href="cta_prestamos.php"><img border=0 src="../images/volver.jpg" ALT="volver a mi cuenta personal"></a>&nbsp;&nbsp;<a href="javascript:window.close()"><img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a></div></td></tr>	
</table>
<table width="620" border="0" cellspacing="2" cellpadding="4" >
<tr><td>&nbsp;</td></tr>
<?php 
$i=1;
while ($row = $d->get_row()) {  ?>
<tr class="texto-normal">
	<td class="texto-tabla" width="70" align="center" valign="top" bgcolor="#6666CC">-<?php echo $i++; ?>-<br><br><?php echo $row['fecha']; ?></td>
	<td bgcolor='#9999FF'>
		Descripción : <font class="texto-tabla"><?php echo $row['descripcion']; ?></font><br>
		Cantidad : <?php echo $row['cantidad']; ?><br>
		Observación : <?php echo $row['observacion']; ?><br>
		<b>Estado : <?php echo $row['estado']; ?></b>
	</td>
</tr>
<?php } ?>
</table>
</blockquote>      
</body>
</html>
