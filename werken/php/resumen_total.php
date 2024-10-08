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
	$sql =	" EXECUTE sp_WEB_RESUMEN " . $numero ;
	// echo $sql;
	$d->execute($sql);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<title>Resumen Bibliográfico</title>
</head>

<body>

<table border="0" width="100%">
    <tr class='tr-color'><td class='titulo'>Resumen Bibliográfico</td></tr>
</table>

<table border="0" width="100%">
<tr><td colspan='2'></td></tr>
<?php
	while ($row = $d->get_row()) { 
		echo "<tr>\n";
		if ( !$row['titulo_resumen'] ) {
			echo "\t<td width='35%' align='right' valign='top' class='texto-bold'>" . $row['titulo_resumen'] . "</td>\n";
		} else {
	      echo "\t<td width='35%' align='right' valign='top' class='texto-bold'>" . $row['titulo_resumen'] . " :</td>\n";
		}
		if ( $row['marca_resumen'] == 1) {
			echo "<td valign='top' width='100%'>" . $row['nombre_resumen'] . "&nbsp";
		    echo "<a href='busqueda_detalle.php?tipo_busqueda=dewey&texto=". $row['dewey_resumen'] . "'>" . $row['dewey_resumen'] . "</a><td>\n";
		} 
		else 
		{  
			if ( $row['marca_resumen'] == 2) 
			{
			echo "<td valign='top' width='100%' class='texto-normal'>";
			echo "<a href=". $row['nombre_resumen'] . " target='_blank'>" . $row['nombre_resumen'];
			echo "</a>&nbsp;". "<td>\n";
//			echo "</a>&nbsp;" . $row['issn_resumen'] . "<td>\n";
			} 
			else
			{
				echo "\t<td width='65%' valign='top' align='left' class='texto-normal'>". $row['nombre_resumen'] . "</td>\n";
			}
		}
		echo "</tr>\n";
	}

?>
</table>
<?php require "../include/html_validator.php"; ?>
</body>
</html>
