<?php
require  "../include/session.php";
session_init_nocache();
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

/* se recuperan los datos de la sesion */
$rut 	= $_SESSION['rut'];
$k		= $_SESSION['clave'];

$rut = format_rut($rut);

$_SESSION['rut'] = $rut ;
$_SESSION['clave'] = $k;

$cuenta = 0;
//print_r($_POST);
// concatena la variable libro con todos los seleccionados
for ($i=1;$i<=$_POST['contador'];$i++)
{
	if (isset($_POST['seleccionar'.$i]))
	{
		$libro .= ','.$_POST['seleccionar'.$i];
	}	
}
//
if (!isset($libro))
{
	echo "<html><head><title>Renovacion Préstamo</title>";
	echo "<link href=\"../conf/estilo.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
	echo "<form action=\"cta_prestamos.php\" name=\"cuenta\" method=\"post\">";
	echo "<table align = \"center\" ><tr><td align = \"center\"><br><strong> Debe Seleccionar al Menos un Libro para Renovar. </strong></td></tr><br>";
	echo "<tr><td align = \"center\"><input name=\"submit\" type=\"submit\" class=\"botonenviar\" value=\"  Volver \" onclick = \"document.forms.cuenta.submit()\"></td></tr> ";
	echo "</table></form></body></html>";	 	
	exit;
}
//conecta a BD
$d = new Database;

// valida las existencias a renovar 	
$sql = " EXECUTE sp_WEB_VALIDA_RENOVACION_PRESTAMO  '$rut', '$libro'" ;
$d->execute($sql);

?>
<html>
<head>
<title>Renovación Prestamo</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body >
<table width="100%" border="0" align="center">
  <tr>
    <td align="center" class="titulo">Renovación de Préstamos</td>
  </tr>
</table>
<form name="aceptados" action="../php/cta_autoriza_renovacion.php" method="post">
<table border="0" width="100%" cellspacing="1" class="texto-tabla-small">
   <tr><td colspan='6' class="texto-bold"><b>Préstamos Aprobados</b></td></tr>
    <tr bgcolor="#003399">
      <td width="10%" align="center" valign="middle" >Tipo Préstamo</td>
      <td width="50%" align="center" valign="middle">Título</td>
      <td width="10" align="center" valign="middle">Formato</td>
      <td width="10%" align="center" valign="middle">Fecha Préstamo</td>
      <td width="10%" align="center" valign="middle">Fecha Devolución</td>
     </tr>   
<?php
while ($row=$d->get_row())  
{
	if ($row['ind_aceptada'] == 1) 
	{
	$cuenta = $cuenta + 1;	
	$libro_valido .= ','.$row['nro_registro_existe'];
?> 		<tr> 
    	  <td width="10%" align="center"  bgcolor="#6666CC"><?php echo $row['tipo_prestamo']; ?></td>
    	  <td width="10%" align="left"  bgcolor="#9999FF"><?php echo $row['titulo']; ?></td>
   		  <td width="10%" align="center"  bgcolor="#6666CC"><?php echo $row['formato']; ?></td>
      	  <td width="10%" align="center"  bgcolor="#9999FF"><?php echo $row['fecha_prestamo_real']; ?></td>
      	  <td width="10%" align="center" bgcolor="#6666CC"><?php echo $row['fecha_devolucion_real']; ?></td>
		  <input name="codigo_libro<?php echo $cuenta ?>" type="hidden" value="<?php echo $row['nro_registro_existe'] ;?>">
		  <input name="contador" type="hidden" value="<?php echo $cuenta; ?>">
	    </tr>
<?php
	}
  } // fin ciclo while
  $_SESSION['exis_concatenadas'] = $libro_valido;
?>
</table>
</form>
<form name="rechazados" action="../php/cta_prestamos.php">
<p></p>
<table border="0" width="100%" cellspacing="1" class="texto-tabla-small">
   <tr><td colspan='6' class="texto-bold"><b>Préstamos Rechazados</b></td></tr>
    <tr bgcolor="#003399">
      <td width="10%" align="center" valign="middle">Tipo Préstamo</td>
      <td width="50%" align="center" valign="middle">Título</td>
      <td width="10" align="center" valign="middle">Formato</td>
      <td width="10%" align="center" valign="middle">Fecha Préstamo</td>
      <td width="10%" align="center" valign="middle">Fecha Devolución</td>
	  <td width="10%" align="center" valign="middle">Motivo</td>
     </tr>   
<?php

$sql = " EXECUTE sp_WEB_VALIDA_RENOVACION_PRESTAMO  '$rut', '$libro'" ;
$d->execute($sql);

while ($row=$d->get_row())  
{
	if ($row['ind_aceptada'] == 0) 
	{	
?> 		<tr> 
    	  <td width="10%" align="center"  bgcolor="#6666CC"><?php echo $row['tipo_prestamo']; ?></td>
    	  <td width="10%" align="left"  bgcolor="#9999FF" ><?php echo $row['titulo']; ?></td>
   		  <td width="10%" align="center"  bgcolor="#6666CC"><?php echo $row['formato']; ?></td>
      	  <td width="10%" align="center"  bgcolor="#9999FF"><?php echo $row['fecha_prestamo_real']; ?></td>
      	  <td width="10%" align="center" bgcolor="#6666CC"><?php echo $row['fecha_devolucion_real']; ?></td>
		  <td width="10%" align="left" bgcolor="#9999FF"><?php echo $row['motivo']; ?></td>
	    </tr>
<?php
	}
  } //while
$d->close();
?>
</table>

<table width="100%" border="0">
  <tr>
  <?php if ($cuenta > 0) { ?> 
    <td width="85%" align="right"><input name="aceptar" type="button" value="Confirmar" onClick="javascript:cta_usuario()" class="botonenviar"></td>
	<? } ?>
    <td width="15%" align="center"><input name="volver" type="button" value="Volver" class="botonenviar" onClick="document.forms.rechazados.submit()"></td>
  </tr>
</table>
</form>
<SCRIPT type="text/javascript">
function cta_usuario()
{ 
<!--   myPopup = window.open(url,'popupWindow','width=450,height=150'); _new-->
window.open("../php/cta_autoriza_renovacion.php", "_new", "resizable=1,scrollbars=1 ,toolbar=0,location=1,menubar=0,status=1") 
} 
</script>
 </body>
</html>
