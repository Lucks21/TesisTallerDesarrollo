<?php
require  "../include/session.php";
session_init_nocache();
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";
require_once "../include/debuglib.php";
/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/

if (param("ini","0")) {
	$rut = param("rut", ""); 
	$k = param("clave","");
	/* se encripta la clave y se almacena en la sesion */
	$k = get_secret(strtoupper($k));
	$_SESSION['clave'] = $k;
	/* se almacena el rut en la session */
	$_SESSION['rut'] = $rut;
}
else 
{
	/* se recuperan los datos de la sesion */
	$rut = $_SESSION['rut'];
	$k = $_SESSION['clave'];

	$clave_base  =  $_SESSION['clave_base'];
}  



$rut = format_rut($rut);
$d = new Database;

$date = getdate();

if(strlen($date[mon]) == 2 )
$fecha = $date[mday].'-'.$date[mon].'-'.$date[year];
else
$fecha = $date[mday].'-0'.$date[mon].'-'.$date[year];

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>APA STYLE</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body >
  <table border="0" width="100%">
    <tr bgcolor="#003399"><td class="titulo">Norma de estilos APA 6ta Edición</td></tr>
    <tr><td height="26" background="../images/motivo.gif">&nbsp;</td></tr>
    <tr><td height="26"> 
        <div align="right">
        	<a href="javascript:window.location.href='download_apa.php'">
        		<img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>
   	  </div>
      </td>
    </tr>
  </table>
<?php 
$sql = "EXECUTE sp_WEB_usuario '$rut'" ;
$d->execute($sql);
$nrows = $d->get_numrows();
if ($nrows == 0) {
  	echo "<h4>Usted no está autorizado a utilizar esta opción.</h4>";
	echo "Verifique que su información sea correcta (RUT y clave). <br>";
	echo "Si lo es, comuníquese con la Dirección de Bibliotecas. <br>";
	echo "</body></html>";
	exit;
}
while ($row=$d->get_row())  {
  if ($k != get_secret(strtoupper(rtrim($row['cc'])))) 
  {
  	echo "<h4>Usted no está autorizado a utilizar esta opción.</h4>";
	echo "Verifique que su clave ingresada sea correcta. <br>";
	echo "Si lo es, comuníquese con la Dirección de Bibliotecas. <br>";
	echo "</body></html>";
	exit;
   }
?>
<?php }
?>
	  <input name="contador" type="hidden" value="<?php echo $nrows;?>"> 
</table>
</form>
<br>
<table border="0" width="100%" cellspacing="1" class="texto-tabla-small">
    <tr bgcolor="#003399">
      <td align="center" valign="middle">APA Style Guide to Electronic References</td>
  </tr>   
<?php 

$sql = " EXECUTE sp_web_prestamos_his '$rut'";
$d->execute($sql);
$nrows = $d->get_numrows();
if ($nrows > 0) { 
  //while ($row=$d->get_row()) { ?>
    <tr> 
      <td align="center"  bgcolor="#9999FF"><iframe src="../html/.ebooks/.APA_6_REV.pdf" style="width:1000px; height:1125px;" frameborder="0"></iframe>&nbsp;</a></td>
    </tr>
        <tr bgcolor="#003399"><td>&nbsp;</td></tr>
<?php
  //} //while 
} else { ?>
    <tr bgcolor="#9999FF"><td>Si no puede ver este documento contactese con su biblioteca</td></tr>
<?php 
} //if 
$d->close();
?>
</table>		
</body>
</html>
