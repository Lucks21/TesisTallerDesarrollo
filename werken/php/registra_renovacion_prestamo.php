<?php
require  "../include/session.php";
session_init_nocache();
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";
/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$rut 	= $_POST['rut'];
$clave  = $_POST['clave'];
$k 		=  get_secret(strtoupper($clave)); 
$rut 	= format_rut($rut);
$libros = $_SESSION['exis_concatenadas'];

$d = new Database;
$sql = " EXECUTE sp_WEB_usuario '$rut'" ;
$d->execute($sql);

if ($d->get_numrows() < 1) 
{	
	echo "<html><head><title>Consulta Cuenta Corriente Usuario</title><link href=\"../conf/estilo.css\" rel=\"stylesheet\" type=\"text/css\"></head>";
	echo "<body> ";
	echo "<form action=\"cta_prestamos.php\" name=\"cuenta\" method=\"post\">";
  	echo "<h4>Usted no está autorizado a utilizar esta opción.</h4>";
	echo "Verifique que su información sea correcta (RUT y clave). <br>";
	echo "Si lo es, comuníquese con la Dirección de Bibliotecas. <br>";
	echo "<tr><td align=\"left\"><a href=\"javascript:window.close()\"><img src=\"../images/etiquetas/cerrar_ventana.gif\" width=\"90\" height=\"19\" border=\"0\"></a>&nbsp;</td></tr>";
	echo "</form></body></html>";
	exit;
}
while ($row=$d->get_row())  {
  if ($k != get_secret(strtoupper(rtrim($row['cc'])))) 
  { 
	echo "<html><head><title>Consulta Cuenta Corriente Usuario</title><link href=\"../conf/estilo.css\" rel=\"stylesheet\" type=\"text/css\"></head>";
	echo "<body>";
	echo "<form action=\"cta_prestamos.php\" name=\"cuenta\" method=\"post\">";
  	echo "<h4>Usted no está autorizado a utilizar esta opción.</h4>";
	echo "Verifique que su clave ingresada sea correcta. <br>";
	echo "Si lo es, comuníquese con la Dirección de Bibliotecas. <br>";
	echo "<tr><td align=\"left\"><a href=\"javascript:window.close()\"><img src=\"../images/etiquetas/cerrar_ventana.gif\" width=\"90\" height=\"19\" border=\"0\"></a>&nbsp;</td></tr>";
	echo "</form></body></html>";
	exit;
   }
}
$sql 	= "EXECUTE sp_web_renovacion_prestamo '$rut', '$clave','$libros' ";
//echo $sql ;
$d->execute($sql);
$row	  = $d->get_row();
$error   = $row['valor'];
$mensaje = $row['mensaje'];
?>
<html>
<head>
<title>Registro Renovación Prestamos</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body >
<form name="cuenta" action="../php/cta_prestamos.php" method="post">
<table width="70%" align="LEFT"  class="texto-bold" >
      <tr><td ><strong><?php echo $mensaje ?></td></tr>
  	  <tr><td align="left"><a href="javascript:window.close()"><img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>&nbsp;</td></tr>				
<!--  <tr><td align = "center"><input name="submit" type="submit" class="botonenviar" value="  Volver " onclick = "document.forms.cuenta.submit()"></td></tr> 	 -->	 
</table>	  
<?php 
$d->close();
?>
</FORM>
</body>
</html>
