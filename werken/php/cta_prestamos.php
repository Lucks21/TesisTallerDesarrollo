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
<title>Consulta Cuenta Corriente Usuario</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body >
  <table border="0" width="100%">
    <tr bgcolor="#003399"><td class="titulo">PRESTAMOS - Cuenta Personal Usuario</td></tr>
    <tr><td height="26" background="../images/motivo.gif">&nbsp;</td></tr>
    <tr><td height="26"> 
        <div align="right">
        	<a href="javascript:window.location.href='cta_acceso.php'">
        		<img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>
        	</div>
      </td>
    </tr>
    
    <tr><td height="26">
        <div align="right" class="texto-bold"> <?php echo $fecha; ?>

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
<table border="0" width="100%" cellspacing="1" cellpadding="0" class="texto-tabla">

    <tr bgcolor="#003399">
      <td width="169" align="right"><b>Nombre :&nbsp;</b></td>
      <td width="459">&nbsp;<?php $nombre = rtrim($row['apepat_usuario']) ." ". rtrim($row['apemat_usuario']) ." ". rtrim($row['nombre_usuario']); echo $nombre; ?> </td>
    </tr>
    <tr bgcolor="#6666cc"> 
      <td width="169" bgcolor="#6666cc" align="right"><b> Estado  Actual :&nbsp; </b></td>
      <td width="459">&nbsp;<?php echo $row['nombre_tb_estado_usr']; ?></td>
    </tr>
    <tr>
      <td width="169" bgcolor="#003399" align="right"><b> Carrera  o Departamento :&nbsp; </b></td>
      <td width="459" bgcolor="#003399">&nbsp;<?php echo $row['departamento']; ?></td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
</table>
<table border="0" cellspacing="1" cellpadding="0" class="texto-tabla">
    <tr> 
      <td align="center" width="150">
		<form method="POST" action="cta_suspensiones.php" >
	 	<input type="submit" value="   Suspensiones   " name="suspensiones" class="botonenviar">
		</form>
 	  </td>
	  <td align="center" width="150">
	     <form method="POST" action="cta_reservas.php" >
	     <input type="submit" value="     Reservas     " name="reservas" class="botonenviar">
		 </form>
      </td>
  	  <td align="center" width="150">
	     <form method="POST" >
	     <input type= "button"  class="botonenviar"value="Renovación Préstamo" name="renueva" onclick = "document.forms.renovacion.submit()">
		 </form>
      </td>

<?php if ($row['solicitud_web_usuario'] == 1) {?>
      <td width="150" align="center">
		  <form method="post" action="solicitud_material.php"><?php $_SESSION['nombre_usuario'] = $nombre;?>
		  <input type="hidden" name="accion" value="inicio" >
		  <input type="submit" value="Solicitar Material" name="sol_mat" class="botonenviar"></form>
	  </td>
	  <td width="150" align="center">
		  <form method="post" action="solicitud_material_consulta.php">
		  <input type="submit" value="  Ver Solicitudes " name="boton_consulta" class="botonenviar"></form>
	  </td>
<?php } ?>      
    </tr>
</table>
<?php }
?>
<br><br>
<form method="POST" action="renovacion_prestamo.php" name="renovacion">
<table border="0" width="100%" cellspacing="1" class="texto-tabla-small">
   <tr><td colspan='5' class="texto-bold"><b>Préstamos Vigentes</b></td></tr>
    <tr bgcolor="#003399">
      <td width="10%" align="center" valign="middle">Tipo Préstamo</td>
      <td width="60%" align="center" valign="middle">Título</td>
      <td width="10%" align="center" valign="middle">Formato</td>
      <td width="10%" align="center" valign="middle">Préstamo</td>
      <td width="10%" align="center" valign="middle">Devolución Normal</td>
	  <td width="10%" align="center" valign="middle">Seleccionar</td>
 </tr>
<?php

$sql = " EXECUTE sp_web_prestamos_vig  '$rut'";
$d->execute($sql);
$nrows = $d->get_numrows();
if ($nrows > 0 ) {
	$count = 0;
 while ($row=$d->get_row()) 
 { 
 	$count = $count + 1 ;?>
    <tr> 
      <td align="center"  width="10%" bgcolor="#6666CC"><?php echo $row['nombre_tb_prestamo']; ?></td>
      <td width="60%" bgcolor="#9999FF"><?php echo $row['nombre_busqueda']; ?><br><?php if ($row['representa']) { echo $row['representa']; } ?></td>
      <td  align="center" width="10%" bgcolor="#6666CC"><?php echo $row['nombre_tb_format']; ?></td>
      <td align="center"  width="10%" bgcolor="#9999FF"><?php echo $row['fecha_prestamo']; ?></td>
      <td align="center"  width="10%" bgcolor="#6666CC"><?php echo $row['devolucion_prestamo']; ?></td>
	  <td align="center"  width="10%" bgcolor="#9999FF"><input name="seleccionar<?php echo $count ?>" type="checkbox" value="<?php echo $row['nro_registro'] ?>"  ></td>
	  <!--<input name="contador" type="hidden" value="<?php echo $nrows2; ?>"> la variable $nrows2 no tiene valor-->
    </tr>
<?php  
 } //while 
} else  {
?>
    <tr  bgcolor="#6666cc"><td colspan='6'>Usuario no registra préstamos vigentes...</td></tr>
<?php }
 
?>
		<!--agregado Roberto 07-07-2005-->
	  <input name="contador" type="hidden" value="<?php echo $nrows; ?>"> 
</table>
</form>
<br>
<table border="0" width="100%" cellspacing="1" class="texto-tabla-small">
   <tr><td colspan='6' class="texto-bold"><b>Préstamos Históricos</b></td></tr>
    <tr bgcolor="#003399">
      <td width="10%" align="center" valign="middle">Tipo Préstamo</td>
      <td width="50%" align="center" valign="middle">Título</td>
      <td width="10" align="center" valign="middle">Formato</td>
      <td width="10%" align="center" valign="middle">Préstamo</td>
      <td width="10%" align="center" valign="middle">Devolución Normal</td>
      <td width="10%" align="center" valign="middle">Devolución Real</td>
     </tr>   
<?php 

$sql = " EXECUTE sp_web_prestamos_his '$rut'";
$d->execute($sql);
$nrows = $d->get_numrows();
if ($nrows > 0) { 
  while ($row=$d->get_row()) { ?>
    <tr> 
      <td width="10%" align="center"  bgcolor="#6666CC"><?php echo $row['tipo_prestamo_his']; ?></td>
      <td width="50%" bgcolor="#9999FF"><?php echo $row['titulo_his']; ?>.<b> <?php echo $row['clasificacion']; ?> </b><br>
	          <?php if ($row['representa']) { echo $row['representa']; } ?>
      </td>
      <td width="10%" align="center"  bgcolor="#6666CC"><?php echo $row['nombre_tb_format']; ?></td>
      <td width="10%" align="center"  bgcolor="#9999FF"><?php echo $row['fecha_prestamo_his']; ?></td>
      <td width="10%" align="center"  bgcolor="#6666CC"><?php echo $row['devolucion_prestamo_his']; ?></td>
      <td width="10%" align="center" bgcolor="#9999FF"><?php echo $row['devolucion_real_prestamo_his']; ?></td>
    </tr>
<?php
  } //while 
} else { ?>
    <tr bgcolor="#6666cc"><td colspan='6'>Usuario no registra Préstamos Históricos...</td></tr>
<?php 
} //if 
$d->close();
?>
  </table>		
</body>
</html>
