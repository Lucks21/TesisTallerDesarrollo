<?php
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$nombre = param("nombre", ""); 
$correo = param("correo","");
$observaciones = param("observaciones","");

$rut = param("rut", ""); 
$dv = param("dv","");
$biblio_ref = param("biblio_ref","");
$carrera_ref = param("carrera_ref","");

if(trim($rut)  == '')
$rut = 'null';


if(trim($carrera_ref)  == '')
$carrera_ref = 'null';

	$d = new Database;
	$sql = "insert into sugerencias (sug_nombre, sug_mail, sug_observaciones, campus_tb_campus , sug_rut , sug_dv, carrera_tb_carrera) ";
	$sql .= "values('$nombre','$correo','$observaciones', '$biblio_ref', $rut, '$dv',  $carrera_ref) ";
	//echo $sql;
	$d->execute($sql);

?>	

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Sugerencias</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
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
<body>
<blockquote>
<table border="0" width="100%">
   <tr bgcolor="#003399"><td class="titulo" >&nbsp;Sugerencias</td></tr>
   <tr><td height="26" background="../images/motivo.gif">&nbsp;</td></tr>
  	<tr><td>&nbsp;</td></tr>
	<tr>
      <td>Estimado Usuario, su mensaje ser&aacute; respondido por el Staff de 
        la Direcci&oacute;n de Bibliotecas.</td>
    </tr>
	<tr><td>&nbsp;</td></tr>
</table>	
</blockquote>
</body>
</html>
