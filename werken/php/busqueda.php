<?php
//require "../include/session.php";
//session_init();
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";
require "../include/debuglib.php";
#require "../include/xssProtect/xss.php";
if ($conf['web']['showtime']) { $t['ini'] = getmicrotime(); }
/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$texto = param("texto", ""); 
$texto1='';
if(isset($_GET['texto']) ){
	if ($_SESSION['ind_busqueda'] == 0) $texto1 = "%".$texto;
	else if ($_SESSION['ind_busqueda'] == 1) $texto1 = $texto;
} 
else {
	if(!isset($_POST['ind_busqueda'])) {
		$texto1 = "%".$texto;
		$_SESSION['ind_busqueda'] = 0;
	} else {
		$texto1 = $texto;
		$_SESSION['ind_busqueda'] =1 ;
	}
}
$tipo_busqueda = param("tipo_busqueda", "materia");
$pag = param("pag", "1");


/*
* valida si la consulta ya habia sido almacenada previamente en alguna session,
* de lo contrario se conecta a la BD para extraer los resultados.
* Para utilizar la informacion almacenada en una session, los datos de consulta
* (texto, tipo_busqueda) recibidos por el web deben ser identicos a los almacenados en
* la session, de lo contrario significa que el usuario esta buscando otra cosa y el contenido
* de la session debe ser refrescada.  
*/ 
$done = "";
if ($texto != "") {
	if (($_SESSION["texto_busqueda"] != $texto1) || ($_SESSION["tipo_busqueda"] != $tipo_busqueda )) {
		$done = ""; //(db)
		$texto1=str_replace("'","''",stripslashes($texto1));
		$d = new Database();	
		$sqlstring ="execute sp_WEB_busqueda_palabras_claves '$texto1','" . $conf['db'][$tipo_busqueda]. "'";
		$d->execute($sqlstring);
		$numrows = $d->get_numrows();
		$data_busqueda = array();
		while ($row = $d->get_row()) {
			array_push ($data_busqueda, htmlentities($row['nombre_busqueda'])); 
		}
		//almacena los datos recuperados desde la BD en la session 
		$_SESSION["busqueda"] 			= $data_busqueda;
		$_SESSION["tipo_busqueda"] 		= $tipo_busqueda;
		$_SESSION["texto_busqueda"] 	= $texto1;
		$_SESSION["nav_pagina"] 		= $pag;
		$_SESSION["busq_numrows"]		= $numrows;
		unset ($data_busqueda);
		if ($conf['web']['showtime']) { $t['session1_set'] = getmicrotime(); }		
		// Recupera la informacion de "VEASE..."
		unset($data);
		$sqlstring  = "execute sp_WEB_vease '$texto', '" . $conf['db'][$tipo_busqueda]. "'";
		$d->execute($sqlstring);
		$numrows = $d->get_numrows();
		if ($numrows>0) {
			$vease_data_1 = array();
			$vease_data_2 = array();
			while ($row = $d->get_row()) { 
				array_push ($vease_data_1, $row['nombre_busqueda']); 
				array_push ($vease_data_2, $row['nombre_vease']); 
			}
			$_SESSION["vs_nombre_busqueda"]	= $vease_data_1;	
			$_SESSION["vs_nombre_vease"]		= $vease_data_2;	
			$_SESSION["vs_numrows"]				= $numrows;
			unset ($vease_data_1, $vease_data_2);
		} 
		else {
			$_SESSION["vs_numrows"] = 0;
		}
		session_write_close();
		$d->close();
		$pag=1;
	} 
	$numrows = $_SESSION["busq_numrows"];
} 
else {
	$numrows=0;
}
if ($conf['web']['showtime']) { $t['end'] = getmicrotime(); }	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>B�squeda en el cat�logo</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37008911-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script language="javascript" src="valida.js" type="text/javascript"></script>
<table cellspacing="2" border="0" >
<tr>
  <td class="celda-buscar"><?php require "applet_busqueda.php"; ?></td>
  <td class="aciertos"><?php echo $numrows; ?> aciertos</td>
</tr>
</table>
  
<table border="0" width="100%">
<?php   if ($numrows >0) { 
	echo "<tr><td align='center' colspan='2'>\n" . gen_NAV_index($numrows,$pag,0) . "\n $done </td></tr>\n";
	echo ($conf['web']['itemlist']) ? "\n<tr><td colspan='2'>\n" : "";
	echo ($conf['web']['itemlist']) ? $conf['web']['itemlist_type_o'] : "<ul>";
	// imprime los resultados de la busqueda 

	for ($i=pager("ini",$numrows,$pag,0); $i<=pager("max",$numrows,$pag,0) ; $i++) {
		echo ($conf['web']['itemlist']) ? "" : "\n<tr><td colspan='2'>\n";
		$y = $i+1;
		echo ($conf['web']['itemlist']) ? "<li value=$y>" : "";
		$aux=str_replace("'","&#39;",$_SESSION['busqueda'][$i]);
		$url = "busqueda_detalle.php?texto=" .$aux. "&amp;tipo_busqueda=" . $tipo_busqueda; 
	   echo "<font class='texto-url'><a href='". $url ."' onClick='valida_url(this)'>";
	   $tmp = ($conf['web']['hilight']) ? hilight($_SESSION['busqueda'][$i], $texto) : $_SESSION['busqueda'][$i];
	   echo $tmp;
	   echo "</a></font>\n";
		echo ($conf['web']['itemlist']) ? "" : "</td></tr>\n";
	}
	echo ($conf['web']['itemlist']) ? $conf['web']['itemlist_type_c'] : "</ul>\n";
	echo ($conf['web']['itemlist']) ? "\n</td></tr>\n" : "";
   
?>

<?php		// sin aciertos de la busqueda 
	} elseif ($numrows <= 0) {  
 ?>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">No se encontr� "<?php echo hilight($texto, $texto); ?>"  en <?php echo $conf['web'][$tipo_busqueda]; ?>...</td></tr>
    
<?php }  ?>
</table>
<?php if ($_SESSION['vs_numrows'] > 0 ) { ?>
<table border="0" width="100%">
<?php
	echo "<tr><td colspan='3'><hr></td></tr>\n";
	for ($i=0; $i<$_SESSION['vs_numrows']; $i++) {
		echo "<tr>\n";
		$txt = ($conf['web']['hilight']) ? hilight($_SESSION['vs_nombre_vease'][$i], $texto) : $_SESSION['vs_nombre_vease'][$i];
		echo "<td width='267' class='vease-usmall'>". $txt ."</td>\n";
	   echo "<td width='93' class='vease-usmall'>V�ase por :</td>\n";
		$txt = ($conf['web']['hilight']) ? hilight($_SESSION['vs_nombre_busqueda'][$i], $texto) : $_SESSION['vs_nombre_busqueda'][$i];
		$url = "busqueda_detalle.php?texto=" . $_SESSION['vs_nombre_busqueda'][$i]. "&amp;tipo_busqueda=" . $tipo_busqueda ; 
		echo "<td width='273' class='vease-usmall'><a href='". $url ."' onClick='valida_url(this)'>" . $txt  ."</a></td>\n";
	   echo "</tr>\n";
	} //for
	echo "</table>";
}  // if
?>

<?php require "../include/html_validator.php"; ?>
</body>
</html>
