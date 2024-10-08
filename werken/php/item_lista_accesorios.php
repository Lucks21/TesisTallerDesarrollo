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
$sql =	" EXECUTE sp_WEB_existencia_accesorios " . $numero;
$d->execute($sql);
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<title>Título Seleccionado</title>
</head>

<body>

 <table border="0" width="100%">
	<tr class="tr-color"><td colspan='2' class='titulo'>&nbsp; Accesorios </td></tr>
	<tr><td colspan='2' >&nbsp;</td></tr>
 </table>
 
<table border="0" cellpadding="4" width="100%" class="texto-tabla">
<tr bgcolor="#003399" class="texto-usmall"> 
	<td width="10%" height="39" align="center" bgcolor="#003399">Reserva en línea</td>
	<td width="40%" height="39" align="left">Ubicación</td>
	<td width="5%" height="39" align="center">Días Préstamo</td>
	<td width="10%" height="39" align="center" bgcolor="#003399">Formato</td>
	<td width="10%" height="39" align="center" bgcolor="#003399">Estado</td>
	<td width="10%" height="39" align="center">Copias</td>
</tr>

<?php 
$row = "";
while ($row = $d->get_row()) {
	echo "<tr>\n";
	if ($row['categoria_tb_catego'] == 3 ) {
		if ( !$row['fecha_final_alta_existe'] || $row['fecha_final_alta_existe'] == 1) {
			echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC' >";
			$url = "item_reserva_accesorio2.php";
			$url .= "?numero=" . $row['nro_control'] . "&amp;volumen=" . $row['nro_volumen_existe'];
			$url .= "&amp;parte=" . $row['nro_parte_existe'] . "&amp;suplemento=" . $row['nro_suplemento_existe'];
			$url .= "&amp;campus=" . $row['campus_tb_campus'] . "&amp;formato=" . $row['formato_tb_format'];
			echo "<a href='" . $url . "'><font class='url-hi'><b>Reservar</b></font></a></td>\n";
		} else {
			echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC'>&nbsp;&nbsp;</td>";
		}
	} else {
			echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC'>&nbsp;&nbsp;</td>";
	}
	
	//echo "<tr align='center' height='39'>";
	echo "\t<td width='40%' align='left' bgcolor='#9999FF'>" . $row['nombre_tb_campus'] . "</td>\n";
	echo "\t<td width='5%' align='center' bgcolor='#9999FF'>" . $row['dias'] . "</td>\n";
	echo "\t<td width='10%' align='center' bgcolor='#6666CC'>" . $row['nombre_tb_format'] . "</td>\n";
	echo "\t<td width='10%'align='left'  bgcolor='#9999FF'><b>" . $row['nombre_tb_estado'] . "</b></td>\n";
	echo "\t<td width='10%' align='center' bgcolor='#6666CC'>" . $row['Total'] . "</td>\n";
	echo "</tr>\n";
}

?>

<tr bgcolor="#003399"> 
	<td width="10%">&nbsp;</td>
	<td width="40%">&nbsp;</td>
	<td width="5%">&nbsp;</td>
	<td width="10%">&nbsp;</td>
	<td width="10%">&nbsp;</td>
	<td width="10%">&nbsp;</td>
</tr>
</table>
<?php require "../include/html_validator.php"; ?>
</body>
</html>
