<?php
require "../include/session.php";
session_init();
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";
require_once "../include/debuglib.php";
/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$texto = param("texto", ""); 
$tipo_busqueda = param("tipo_busqueda", "autor");
$pag = param("pag", "1");


/*
* valida si la consulta ya habia sido almacenada previamente en alguna session,
* de lo contrario se conecta a la BD para extraer los resultados.
* Para utilizar la informacion almacenada en una session, los datos de consulta
* (texto, tipo_busqueda) recibidos por el web deben ser identicos a los almacenados en
* la session, de lo contrario significa que el usuario esta buscando otra cosa y el contenido
* de la session debe ser refrescada.  
*/ 
if ($texto != "") {
	if (($_SESSION["texto_busqueda"] != $texto) || ($_SESSION["tipo_busqueda"] != $tipo_busqueda )) {
		$d = new Database();
		$texto_aux=str_replace("'","''",stripslashes($texto));
		$sqlstring ="execute sp_WEB_detalle_busqueda '$texto_aux','" . $conf['db'][$tipo_busqueda]. "'";
		$d->execute($sqlstring);
		$numrows = $d->get_numrows();
		$data_busqueda = array();
		while ($row = $d->get_row()) { 
			$tmp_array = array ($row['nombre_busqueda'],$row['nro_control'],
									$row['tipo'], $row['autor'],
									$row['publicacion'], $row['dewey'] );
			array_push ($data_busqueda, $tmp_array); 
		}
		//almacena los datos recuperados desde la BD en la session 
		$_SESSION["busqueda"] 		= $data_busqueda;
		$_SESSION["tipo_busqueda"] 	= $tipo_busqueda;
		$_SESSION["texto_busqueda"] 	= $texto;
		$_SESSION["nav_pagina"] 	= $pag;
		$_SESSION["busq_numrows"]	= $numrows;
		unset ($data_busqueda, $tmp_array);
		// Recupera la informacion de "VEASE..."
		$sqlstring  = "execute sp_WEB_vease_ademas '$texto_aux', '" . $conf['db'][$tipo_busqueda]. "'";
		$d->execute($sqlstring);
		$numrows = $d->get_numrows();
		if ($numrows >0) {
			$tmp_array = array();
			$data_busqueda = array();
			while ($row = $d->get_row()) { 
				array_push ($data_busqueda, $row['nombre_busqueda']); 
			}
			$_SESSION["vs_vease"]	= $data_busqueda;	
			$_SESSION["vs_numrows"]	= $numrows;
			unset ($tmp_array, $data_busqueda);
		} 
		else {
			$_SESSION["vs_numrows"] = 0;
		}
		$d->close();
		$pag=1;
	} 
	$numrows = $_SESSION["busq_numrows"];
} 
else {
	$numrows=0;
	$_SESSION["vs_numrows"]	= 0;
	$_SESSION["busq_numrows"] = 0;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<title>Detalle de la búsqueda</title>
</head>
<body>
<table cellspacing="2" border="0">
<tr>
  <td class="celda-buscar"><?php require "applet_busqueda.php"; ?></td>
  <td class="aciertos"><?php echo $numrows; ?> aciertos</td>
</tr>
</table>  
<?php   
 if ($numrows >0) { 
?> 
<table border="0" width="100%">
<?php
	echo "<tr><td align='center' colspan='2'>\n" . gen_NAV_index($numrows,$pag,1) . "\n</td></tr>\n";
	echo "\n<tr><td colspan='2'> </td>\n";	
	// imprime los resultados de la busqueda 
	for ($i=pager("ini",$numrows,$pag,1); $i<=pager("max",$numrows,$pag,1) ; $i++) {
		if (preg_match('/[am]/',$_SESSION['busqueda'][$i][tipo])) {
			echo "<tr><td width='50' valign='top'><img src='../images/monografia.gif' width='32' height='32' border='0' alt='monografía'> ";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "'>";
			echo $_SESSION['busqueda'][$i][nombre_busqueda] . "</a>. <br>";
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "<font class='publicacion'> [" . $_SESSION['busqueda'][$i][publicacion] . "]</font>. ";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][dewey] . "</font>";
			echo "</font></td></tr>\n";
		}
		if (preg_match('/[s]/',$_SESSION['busqueda'][$i][tipo])) {
			echo "<tr><td width='50' valign='top'><img src='../images/seriada.gif' width='34' height='28' border='0' alt='publicación seriada'> ";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias_pp.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "'>";
			echo $_SESSION['busqueda'][$i][nombre_busqueda] . "</a>. <br>";
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][dewey] . "</font>";
			echo "</font></td></tr>\n";
		}	
		if (preg_match('/[b]/',$_SESSION['busqueda'][$i][tipo])) {
			echo "<tr><td width='50' valign='top'><img src='../images/seriada.gif' width='34' height='28' border='0' alt='publicación seriada'> ";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias_art_pp.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "'>";
			echo $_SESSION['busqueda'][$i][nombre_busqueda] . "</a>. <br>";
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][dewey] . "</font>";
			echo "</font></td></tr>\n";
		}		
	}
} else {  // no existen registros
?>
<table border="0" width="100%">
<tr><td colspan='2'>&nbsp;</td></tr>
<tr><td colspan='2'>No existen aciertos en el catálogo para la "<?php echo $_SESSION['tipo_busqueda']; ?>" ingresada...</td></tr>
<?php } ?>	  
</table>

<?php if ($_SESSION['vs_numrows'] > 0 ) { ?>
<table border="0" width="100%">
	<tr><td><br><hr></td></tr>
	<tr><td class="texto-small">Véase Además por : <?php $tipo_busqueda; ?></td></tr>
</table>
<table border="0" width="100%">
<?php
	for ($i=0; $i<$_SESSION['vs_numrows']; $i++) {
		echo "<tr>";
		echo "<td width='50' class='vease-usmall'><img src='../images/diamante.gif'></td>";
		$txt = ($conf['web']['hilight']) ? hilight($_SESSION['vs_vease'][$i], $texto) : $_SESSION['vs_vease'][$i];
		$url = "busqueda_detalle.php?texto=" . $_SESSION['vs_vease'][$i]. "&tipo_busqueda=" . $tipo_busqueda; 
		echo "<td width='100%' class='vease-usmall'><a href='". $url ."'>" . $txt  ."</a></td>";
	   echo "</tr>\n";
	}  //for
	?>
</table>	
<?php 
} //if
?>
<?php require "../include/html_validator.php"; ?>
</body>
</html>
