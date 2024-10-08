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
	$sql =	" EXECUTE sp_WEB_RESUMEN_ART_PP " . $numero ;
	$d->execute($sql);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<title>Título seleccionado</title>
</head>

<body">

<table border="0" width="100%">
    <tr class='tr-color'><td class='titulo'>Título seleccionado</td></tr>
</table>

<table border="0" width="100%">
<tr><td colspan='2'>&nbsp;</td></tr>
<tr><td colspan='2' class="texto-bold">Este título corresponde a un <strong>artículo de publicación seriada</strong></td></tr>
<tr><td colspan='2'>&nbsp;</td></tr>
<?php
	while ($row = $d->get_row()) { 
		echo "<tr>\n";
		if ( !$row['titulo_resumen'] ) {
			echo "\t<td width='35%' align='right' valign='top' class='texto-bold'>" . $row['titulo_resumen'] . "</td>\n";
		} else {
	      echo "\t<td width='35%' align='right' valign='top' class='texto-bold'>" . $row['titulo_resumen'] . " :</td>\n";
		}
		if ( $row['marca_resumen'] == 1) {
			echo "<td valign='top' width='35%'>";
		    echo "<a href='busqueda_detalle.php?tipo_busqueda=titulo&texto=". $row['nombre_resumen'] . "'>" . $row['nombre_resumen'] . "</a>\n";
		    echo "&nbsp;" . $row['ubicacion_resumen']. "<td>\n";
		} else {  
			echo "\t<td width='65%' valign='top' align='left' class='texto-normal'>". $row['nombre_resumen'] . "</td>\n";
		}
		echo "</tr>\n";
	}

?>
</table>

</body>
</html>
