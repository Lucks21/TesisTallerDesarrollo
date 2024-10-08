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
$rut = $_SESSION['rut']; 
$rut = format_rut($rut);

$d = new Database;

$sql = " EXECUTE sp_WEB_usuario '$rut'" ;
$d->execute($sql);
$nrows = $d->get_numrows();

?>
<html>
<head>
<title>Consulta Cuenta Corriente Usuario</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>

<body >

  <table border="0" width="100%">
    <tr bgcolor="#003399"><td class="titulo" >SUSPENSIONES - cuenta personal usuario</td></tr>
    <tr><td height="26" background="../images/motivo.gif">&nbsp;</td></tr>
    <tr><td height="26"> 
        <div align="right">
        	<a href="javascript:window.close()">
        		<img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>
        	</div>
      </td>
    </tr>
  </table>
<?php 

if ($nrows < 1) {
  	echo "<h4>Usted no está autorizado a utilizar esta opción.</h4>";
	echo "Verifique que su información sea correcta (RUT y clave). <br>";
	echo "Si lo es, comuníquese con la Dirección de Bibliotecas. <br>";
	echo "</body></html>";
	exit;
}

while ($row=$d->get_row())  { 
?>
<table border="0" width="100%" cellspacing="1" cellpadding="0" class="texto-tabla">
    <tr bgcolor="#003399"> 
      <td width="169" align="right"><b>Nombre :&nbsp;</b></td>
      <td width="459">&nbsp;<?php echo $row['apepat_usuario']; ?> <?php echo $row['apemat_usuario']; ?> <?php echo $row['nombre_usuario']; ?> </td>
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
		 <form method="POST" action="cta_prestamos.php">
		 <input type="submit" value="     Préstamos    " name="boton_prestamos">
		 </form>
 	  </td>
	  <td align="center" width="150">
	     <form method="POST" action="cta_reservas.php">
	     <input type="hidden" name="rut" value="<?php echo $row['rut_usuario']; ?>">
	     <input type="submit" value="     Reservas     " name="reservas"></form>
      </td>
<?php if ($row['solicitud_web_usuario'] == 1) {?>
      <td width="150" align="center"><form method="post" action="solicitud_material.php">
      <input type="hidden" name="rut" value="<?php echo rtrim($row['rut_usuario']); ?>">
      <input type="hidden" name="nombre" value="<?php echo $nombre; ?>">
      <input type="hidden" name="accion" value="inicio">
      <input type="submit" value="Solicitar Material" name="sol_mat"></form></td>
	  <td width="150" align="center"><form method="post" action="solicitud_material_consulta.php">
      <input type="hidden" name="rut" value="<?php echo rtrim($row['rut_usuario']); ?>">
      <input type="submit" value="  Ver Solicitudes " name="boton_consulta"></form>
	  </td>
<?php } else  { ?>
      <td width="70%">&nbsp;</td>
<?php } ?>      
    </tr>
</table>
<?php }
?>
<br><br>
<table border="0" width="100%" cellspacing="1" class="texto-tabla-small">
   <tr><td colspan='5' class="texto-bold"><b>Suspensiones por Atraso</b></td></tr>
    <tr bgcolor="#003399">
      <td width="5%" align="center" valign="middle" bgcolor="#003399"><b>Registro</b></td>
      <td width="35%" align="center" valign="middle" bgcolor="#003399"><b>Título</b></td>
      <td width="12%" align="center" valign="middle" bgcolor="#003399"><b>Préstamo</b></td>
      <td width="12%" align="center" valign="middle" bgcolor="#003399"><b>Inicio</b></td>
      <td width="12%" align="center" valign="middle" bgcolor="#003399"><b>Fin</b></td>
      <td width="12%" align="center" valign="middle" bgcolor="#003399"><b>Fin Real</b></td>
   </tr>

<?php

$sql = " EXECUTE sp_web_suspensiones '$rut'";
$d->execute($sql);
$nrows = $d->get_numrows();

if ($nrows > 0 ) {
 while ($row=$d->get_row()) { ?>   
    <tr> 
      <td width="5%" bgcolor="#6666cc"><p align="center"><?php echo $row['nro_registro_existe']; ?></td>
      <td width="40%" bgcolor="#9999FF"><p align="left"><?php echo $row['nombre_busqueda']; ?><br>
					          <?php if ($row['representa']) { echo $row['representa']; } ?>
      </td>
      <td width="12%" height="1%" bgcolor="#6666cc"><p align="center"><?php echo $row['fecha_prestamo']; ?></td>
      <td width="12%" height="1%" bgcolor="#9999FF"><p align="center"><?php echo $row['inicio_suspension']; ?></td>
      <td width="12%" height="1%" bgcolor="#6666cc"><p align="center"><?php echo $row['fin_suspension']; ?></td>
      <?php if (! $row['fin_real_suspension']) { ?>
      <td width="10%" height="1%" bgcolor="#9999FF"><p align="center"><b>Vigente</b></td>
      <?php } else { ?>
      <td width="12%" height="1%" bgcolor="#9999FF"><p align="center"><?php echo $row['fin_real_suspension']; ?></td>
      <?php } ?>
     </tr>
<?php 
	}  //while
} else  { ?>
    <td colspan=6"  bgcolor="#6666cc"><b>Usuario no registra suspensiones por atraso... </b></td>
<?php } ?>
</table>
<br><br>
<table border="0" width="100%" cellspacing="1" class="texto-tabla-small">
   <tr><td colspan='5' class="texto-bold"><b>Suspensiones por Faltas </b></td></tr>
    <tr bgcolor="#003399">
      <td width="45%" bgcolor="#003399" align="center"><b>Motivo</b></td>
      <td width="15%" bgcolor="#003399" align="center"><b>Inicio</b></td>
      <td width="15%" bgcolor="#003399" align="center"><b>Fin</b></td>
      <td width="15%" bgcolor="#003399" align="center"><b>Fin Real</b></td>
    </tr>
<?php 

$sql = " EXECUTE sp_web_suspensiones_x_falta '$rut'";

$d->execute($sql);
$nrows = $d->get_numrows();

if ($nrows > 0) { 
  while ($row=$d->get_row()) { ?>
    <tr> 
      <td width="45%" bgcolor="#6666cc"><?php echo $row['nombre_tb_falta'];?></td>
      <td width="15%" bgcolor="#9999FF"><?php echo $row['inicio_falta']; ?></td>
      <td width="15%" bgcolor="#6666cc"><?php echo $row['fin_falta'];?></td>
      <?php if (!$row['fin_real_falta']) { ?>
      <td width="15%" bgcolor="#9999FF"><b>Vigente</b></td>
	  <?php } else { ?>
      <td width="15%" bgcolor="#9999FF"><?php echo $row['fin_real_falta'];?></td>
      <?php } ?>
    </tr>
<?php 
  }  //while 
} else { ?>
    <td colspan="4" bgcolor="#6666cc"><b>Usuario no registra Suspensiones por Falta...</b></td>
<?php } ?>
  </table>

<?php  $d->close();  ?>
</body>
</html>
