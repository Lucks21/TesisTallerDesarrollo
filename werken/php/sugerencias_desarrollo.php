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

?>	

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Sugerencias</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
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
