<?php
require "../include/session.php";
session_init();

require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";
//require "debuglib.php";
if ($conf['web']['showtime']) { $t['ini'] = getmicrotime(); }
/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
//print_a($_POST);
$texto = param("texto", ""); 
$tipo_busqueda = param("tipo_busqueda", "materia");
$pag = param("pag", "1");
$texto1='';


if(isset($_GET['texto']) ){
	if ($_SESSION['ind_busqueda'] == 0) $texto1 = "%" . $texto;
	else if ($_SESSION['ind_busqueda'] == 1) $texto1 = $texto;
} else {
	if(!isset($_POST['ind_busqueda'])){
		$texto1 = "%" . $texto ;
		$_SESSION['ind_busqueda'] = 0;
	} else {
		$texto1 = $texto;
		$_SESSION['ind_busqueda'] =1 ;
	}
}


/*
* valida si la consulta ya habia sido almacenada previamente en alguna session,
* de lo contrario se conecta a la BD para extraer los resultados.
* Para utilizar la informacion almacenada en una session, los datos de consulta
* (texto, tipo_busqueda) recibidos por el web deben ser identicos a los almacenados en
* la session, de lo contrario significa que el usuario esta buscando otra cosa y el contenido
* de la session debe ser refrescada.  
*/ 


if ($texto1 != "") {
  if (($_SESSION["texto_busqueda"] != $texto1) || ($_SESSION["tipo_busqueda"] != $tipo_busqueda )) {
	$d = new Database;
	if ($conf['web']['showtime']) { $t['connect'] = getmicrotime();	}

	$sqlstring ="execute sp_WEB_busqueda_palabras_claves '$texto1', 3";


	$d->execute($sqlstring);
	if ($conf['web']['showtime']) {$t['query'] = getmicrotime();	}
	$numrows = $d->get_numrows();
	
	$data_busqueda = array();
	while ($row = $d->get_row()) { 
		$tmp_array = array ($row['nombre_busqueda'],$row['nro_control'],
								$row['tipo'], $row['autor'],
								$row['publicacion'], $row['dewey'] );
		array_push ($data_busqueda, $tmp_array); 
	}
	if ($conf['web']['showtime']) { $t['dataset'] = getmicrotime();	}
	//almacena los datos recuperados desde la BD en la session 
	$_SESSION["busqueda"] 			= $data_busqueda;
	$_SESSION["tipo_busqueda"] 	= $tipo_busqueda;
	$_SESSION["texto_busqueda"] 	= $texto1;
	$_SESSION["nav_pagina"] 		= $pag;
	$_SESSION["busq_numrows"]		= $numrows;
	unset ($data_busqueda, $tmp_array);
	if ($conf['web']['showtime']) { $t['session'] = getmicrotime();	}
	
	$d->close();
	$pag=1;
	if ($conf['web']['showtime']) { $t['data_free'] = getmicrotime();	}
  } 
  $numrows = $_SESSION["busq_numrows"];
} else {
	$numrows=0;
}

if ($conf['web']['showtime']) { $t['end_script'] = getmicrotime();	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<title> Busqueda por título </title>
</head>
<body>
<script language="javascript" src="valida.js" type="text/javascript"></script>
<?php 
	if ($conf['web']['showtime']) { show_time($t); }
	?>
<table border="0">
<tr>
  <td class="celda-buscar"><?php require "applet_busqueda.php"; ?></td>
  <td class="aciertos"><?php echo $numrows; ?> aciertos.</td>
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
			echo "<tr><td width='50' valign='top'><img src='../images/monografia.gif' width='32' height='32' border='0' alt='monografía'>";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "' onClick='valida_url(this)'>";
			$busq = ($conf['web']['hilight']) ? hilight($_SESSION['busqueda'][$i][nombre_busqueda], $texto) : $_SESSION['busqueda'][$i][nombre_busqueda];
			echo $busq . "</a>. <br>";
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "<font class='publicacion'> [" . $_SESSION['busqueda'][$i][publicacion] . "]</font>. ";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][dewey] . "</font>";
			echo "</font></td></tr>\n";
		}
		if (preg_match('/[s]/',$_SESSION['busqueda'][$i][tipo])) {
			echo "<tr><td width='50' valign='top'><img src='../images/seriada.gif' width='34' height='28' border='0' alt='publicación seriada'> ";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias_pp.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "' onClick='valida_url(this)'>";
			$busq = ($conf['web']['hilight']) ? hilight($_SESSION['busqueda'][$i][nombre_busqueda], $texto) : $_SESSION['busqueda'][$i][nombre_busqueda];
			echo $busq . "</a>. <br>";			
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][dewey] . "</font>";
			echo "</font></td></tr>\n";
		}	
		if (preg_match('/[b]/',$_SESSION['busqueda'][$i][tipo])) {
			echo "<tr><td width='50' valign='top'><img src='../images/seriada.gif' width='34' height='28' border='0' alt='publicación seriada'> ";
			echo "<td width='100%'>";
			echo "<font class='texto-url'><a HREF='existencias_art_pp.php?numero=". $_SESSION['busqueda'][$i][nro_control] . "' onClick='valida_url(this)'>";
			$busq = ($conf['web']['hilight']) ? hilight($_SESSION['busqueda'][$i][nombre_busqueda], $texto) : $_SESSION['busqueda'][$i][nombre_busqueda];
			echo $busq . "</a>. <br>";
			echo "<font class='autor'>" . $_SESSION['busqueda'][$i][autor] . "</font>";
			echo "&nbsp; <font class='dewey'>" . $_SESSION['busqueda'][$i][dewey] . "</font>";
			echo "</font></td></tr>\n";
		}		
	}  // for
} else {  // no existen registros
?>
<table border="0" width="100%">
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
 <td colspan="2">No se encontró "<?php echo hilight($texto, $texto); ?>"  en <?php echo $conf['web'][$tipo_busqueda]; ?>...</td>
</tr>
<?php } ?>	  
</table>
<?php require "../include/html_validator.php"; ?>
</body>
</html>
