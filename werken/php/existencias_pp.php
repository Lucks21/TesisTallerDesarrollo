<?php
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";


/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$numero = param("numero", ""); 

$d = new Database;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<title>Título Seleccionado</title>
</head>
<body>

 <table border="0" width="100%">
	<tr class="tr-color"><td colspan='2' class='titulo'>&nbsp; Título Seleccionado </td></tr>
	<tr><td colspan='2' >&nbsp;</td></tr>
	<tr><td width="70%" >&nbsp; </td>
       <td width="30%" align='right'>
       	<a href="resumen_pp.php?numero=<?php echo $numero; ?>">
       	<img src="../images/e_mas_info3.jpg" alt="Información Bibliográfica" border='0'></a>
        </td>
   </tr>
 </table>

<table border="0" width="100%">
<?php 

$sql =	" EXECUTE sp_WEB_resumen_pp " . $numero ;
$d->execute($sql);

while ($row = $d->get_row()) { ?>
<tr>
   <td width='35%' align='right' valign='top' class='texto-bold'><?php echo $row['titulo_resumen']; ?> :</td>
   <td width='65%' valign='top' align='left' class='texto-normal'><?php echo $row['nombre_resumen']; ?></td>
</tr>
<?php } ?>

<tr><td colspan='2'>&nbsp;</td></tr>
</table>
<font class='texto-hi'>Existencias:</font><br>&nbsp;

<table border="0" width="100%" class='texto-tabla'>
<tr bgcolor="#003399" class='texto-usmall'> 
	<td width="1%" height='20'>&nbsp;</td>
	<td width="33%"  height='20'>Campus</td>
	<td width="65%"  height='20'>Representación</td>
	<td width="1%" height='20'>&nbsp;</td>
</tr>
<?php 

$sql =	" EXECUTE sp_WEB_periodicidad " . $numero;
$d->execute($sql);

while ($row = $d->get_row()) {  ?>
<tr> 
  	<td width="1%" bgcolor='#6666CC' height='20'>&nbsp;</td>
	<td width="33%" valign="top" align="left"  bgcolor='#9999FF' height='20'><?php echo $row['campus_repre']; ?></td>
   <td width="65%" valign="top" align="left" bgcolor='#6666CC' height='20'><?php echo $row['represen_repre']; ?></td>
	<td width="1%" bgcolor='#9999FF' height='20'>&nbsp;</td>
</tr>
<?php } ?>
<tr bgcolor="#003399"> 
	<td width="1%" height='20'>&nbsp;</td>
	<td width="34%" height='20'>&nbsp;</td>
	<td width="65%" height='20'>&nbsp;</td>
	<td width="1%" height='20'>&nbsp;</td>
</tr>
</table>
<p class='texto-mall-hi'>Para entender el significado de la puntuación usada en la descripción de existencias haga
<a href="../ayuda/puntuacion.htm" target="_blank">click aquí</a></p>
<?php 
$d->close();
require "../include/html_validator.php"; 
?>

</body>
</html>
