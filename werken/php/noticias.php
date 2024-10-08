<?php
require "../include/session.php";
session_init();

require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

$pag = param("pag", "1");

if (! $_SESSION["busqueda"]) {
	$d = new Database;
	$sql = " EXECUTE sp_WEB_libros_nuevos " ;
	$d->execute($sql);
	$numrows = $d->get_numrows();

	$data_busqueda = array();
	while ($row = $d->get_row()) { 
		// ver config.php en donde se definen las posiciones de los elementos en el vector
		// se debe respetar dicho orden! 
		$tmp_array = array ($row['nombre_busqueda'],$row['nro_control'],
								$row['tipo'], $row['autor'],
								$row['publicacion'], $row['titulo'], $row['mes'], $row['ano'] );
		array_push ($data_busqueda, $tmp_array); 
	}
	//almacena los datos recuperados desde la BD en la session 
	$_SESSION["busqueda"] 		= $data_busqueda;
	$_SESSION["nav_pagina"] 	= $pag;
	$_SESSION["busq_numrows"]	= $numrows;

	unset ($data_busqueda, $tmp_array);
} else {
	$numrows = $_SESSION["busq_numrows"];
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<title>Novedades del Mes</title>
</head>

<body>

<?php   if ( $numrows > 0 ) { ?>

<table border='0' width='100%' height='32'>
<tr class="tr-color"><td colspan='2' class='titulo'>
&nbsp;Material Bibliográfico ingresado en <em><?php echo $_SESSION['busqueda'][0][mes], "-",  $_SESSION['busqueda'][0][ano]; ?></em>
</tr></td>
<tr><td align='center' colspan='2'><?php echo gen_NAV_index($numrows,$pag,1) ?> </td></tr>
<tr><td colspan='2'> </td>

<?php 
	// imprime los resultados de la busqueda
	for ($i=pager("ini",$numrows,$pag,1); $i<=pager("max",$numrows,$pag,1) ; $i++) {

		if (preg_match('/[am]/',$_SESSION['busqueda'][$i][tipo])) {
			echo "<tr><td width='50' align='top'><img src='../images/monografia.gif' width='32' height='32' border='0' alt='monografía'> ";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "'>";
			echo $_SESSION['busqueda'][$i][titulo] . "</a>. <br>";
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "<font class='publicacion'> [" . $_SESSION['busqueda'][$i][publicacion] . "]</font>. ";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][nombre_busqueda] . "</font>";
			echo "</font></td></tr>\n";
		}
		if (preg_match('/[s]/',$_SESSION['busqueda'][$i][tipo])) {
			echo "<tr><td width='50' align='top'><img src='../images/seriada.gif' width='34' height='28' border='0' alt='publicación seriada'>";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias_pp.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "'>";
			echo $_SESSION['busqueda'][$i][titulo] . "</a>. <br>";
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][nombre_busqueda] . "</font>";
			echo "</font></td></tr>\n";
		}	
		if (preg_match('/[b]/',$_SESSION['busqueda'][$i][tipo])) {
			echo "<tr><td width='50' align='top'><img src='../images/seriada.gif' width='34' height='28' border='0' alt='publicación seriada'>";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias_art_pp.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "'>";
			echo $_SESSION['busqueda'][$i][titulo] . "</a>. <br>";
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][nombre_busqueda] . "</font>";
			echo "</font></td></tr>\n";
		}		
	}
} else {  // no existen registros	?>
<table border="0" width="100%">
<tr class="tr-color"><td colspan='2' class='titulo'>&nbsp;Material Bibliográfico</tr></td>
<tr><td colspan='2'>&nbsp;</td></tr>
<tr><td colspan='2'>No existen novedades ingresadas al catalogo durante el mes...</td></tr>

<?php } ?>
</table>

</body>
</html>
