<?php
require "../include/session.php";
session_init_nocache();

require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

$rut = $_SESSION['rut'];
$nombre = $_SESSION['nombre_usuario'];
$accion=param("accion","mostrar");
$id=param("id","");
$sid = session_id();

$d = new Database;
if ($accion == "eliminar") {
	$sql = " DELETE FROM carro_solicitud_material where csm_correlativo=$id and csm_session= '$sid'";
	$d->execute($sql);
}
$sql = " SELECT * from carro_solicitud_material where csm_rut = '$rut' and csm_session= '$sid' order by csm_correlativo ";
$d->execute($sql);


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
      <div align="right"><a href="solicitud_material.php?accion=mas"><img src="../images/agregar_mas.png" border="0" hspace="0" vspace="0"></a>&nbsp;&nbsp;
      <a href="carro_guardar.php"><img src="../images/enviar_solicitud.png" border="0" hspace="0" vspace="0"></a>&nbsp;&nbsp;
      <a href="cta_prestamos.php"><img border=0 src="../images/volver.jpg" ALT="volver a mi cuenta personal"></a>&nbsp;&nbsp;
	  <a href="javascript:window.close()"><img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>	</div>
	  </td>
</tr>
<?php if ($d->get_numrows()< 1) { ?>
<tr>
<td colspan="4">El carro está vacío, por favor ingrese el material a solicitar.</td>
</tr>
<?php
exit;
} // if
?>
<tr bgcolor="#003399" align="center">
<td>Nº</td>
<td><b>Solicitudes</b></td><td colspan="2"><b>Acción</b></td></tr>
<?php 
$i=1;
while ($row=$d->get_row()) { 

?>
<tr class="texto-small">
<td bgcolor='#6666CC' align='center'><b><?php echo $i; ?>.-<b></td>
<td bgcolor='#9999FF'>
<?php 
if (rtrim($row['csm_titulo'])) echo "<b> Titulo: </b><font class='texto-tabla'>" . $row['csm_titulo'] . "</font>,<br> "; 
if (rtrim($row['csm_autor_editor'])) echo "<b> Autor/Editor: </b><font class='texto-tabla'>" .$row['csm_autor_editor']. "</font>, <br>"; 
if (rtrim($row['csm_editorial'])) echo "<b> Editorial: </b><font class='texto-tabla'>" . $row['csm_editorial']. "</font>, <br>"; 
if (rtrim($row['csm_isbn_issn'])) echo "<b> Isbn/Issn: </b><font class='texto-tabla'>" . $row['csm_isbn_issn']. "</font>, "; 
if (rtrim($row['csm_fecha_publicacion'])) echo "<b> Año de Publicación: </b><font class='texto-tabla'>". $row['csm_fecha_publicacion']. "</font>, "; 
if ($row['csm_cantidad']) echo "<b> Cantidad: </b><font class='texto-tabla'>". $row['csm_cantidad']. "</font>,<br> "; 
if (rtrim($row['csm_observacion'])) echo "<b> Observación: </b><font class='texto-tabla'>" . $row['csm_observacion']. "</font>, <br>"; 
if (!empty($row['csm_tipo_material'])) {
	echo "<b> Tipo de Material:</b><font class='texto-tabla'> ";
	if ($row['csm_tipo_material'] == "M") { 
		echo "Libro</font>,"; 
	} else { 
		echo "Revista</font>, ";
	}
	echo "&nbsp;&nbsp;";
} 	
if ($row['csm_prioridad'] == 1) echo "<b> Prioridad:</b> <font class='texto-tabla'>Urgente</font><br>"; 
if ($row['csm_reparticion']) echo "<b>Repartición:</b> <font class='texto-tabla'>" . $row['csm_reparticion'] . '</font>'; 
?>
</td>
<td bgcolor='#6666CC' align='center'><a href="
	<?php echo $PHP_SELF . '?id='. $row['csm_correlativo'] . '&accion=eliminar'; ?>">Eliminar</td>
<td bgcolor='#6666CC' align='center'><a href="solicitud_material.php
	<?php  echo '?id=' . $row['csm_correlativo'] . '&accion=editar&nitems=' . $d->get_numrows(); ?>">Editar</a></td>
</tr>
<?php 
$i++;
} ?>
</table>
</blockquote>



</body>
</html>
