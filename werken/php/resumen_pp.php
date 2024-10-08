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
$sql =	" EXECUTE sp_WEB_RESUMEN_COMPLETO " . $numero ;
$d->execute($sql);

?>
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
		if ( $row['marca_resumen'] == 1) 
		{
			echo "<td valign='top'>";
			echo "<a href='busqueda_detalle.php?tipo_busqueda=titulo&texto=" . $row['nombre_resumen'] . "'>" . $row['nombre_resumen'];
			echo "</a>&nbsp;" . $row['issn_resumen'] . "<td>\n";
		} 
		else 
		{
			 if ( $row['marca_resumen'] == 2) 
			{
				echo "<td valign='top'>";
				echo "<a href=". $row['nombre_resumen'] . " target='_blank'>" . $row['nombre_resumen'];
				echo "</a>&nbsp;" . $row['issn_resumen'] . "<td>\n";
			} 
			else
			{  
			echo "<td width='65%' valign='top' align='left' class='texto-normal'>". $row['nombre_resumen'] . "</td>\n";
			}
		}
		echo "</tr>\n";
	}
	$d->close();
?>
</table>

</body>
</html>
