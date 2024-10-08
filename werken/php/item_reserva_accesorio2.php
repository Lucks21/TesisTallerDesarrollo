<?php
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$numero = param("numero", ""); 
$volumen = param("volumen","0");
$parte = param("parte","0");
$suplemento = param("suplemento","0");
$campus = param("campus","");
$formato = param("formato","");


$d = new Database;
$sql = " EXECUTE sp_WEB_reserva_accesorio $numero, $volumen, $parte, $suplemento, '$campus', '$formato', 'formulario_accesorios' " ;
$d->execute($sql);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Reserva en L�nea</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<script language="JavaScript" src="valida.js" type="text/javascript"></script>

<table border="0" width="100%" class="texto-small">
<tr class="tr-color"><td class="titulo">Reserva en L�nea</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>ADVERTENCIA: Antes de realizar una reserva, debe considerar que de no retirar el libro en un
		  <strong><font color="red">plazo de 24 horas</font></strong>., el sistema lo <strong>
		  <font color="red">suspender� por cinco d�as h�biles</font></strong>.</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<table border="0" class="texto-small" width="100%">
<?php 
	$total = 0;
	while ($row = $d->get_row()) { 
		$total++; ?>    
      <tr class='tr-color-hi'><td colspan='2' class='texto-tabla'>T�tulo del accesorio a Reservar</td></tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Nro. de Pedido : </td>
        <td width="88%"><?php echo $row['nombre_busqueda']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Nro. de Registro : </td>
        <td width="88%"><?php echo $row['nro_registro_existe']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Pertenece a : </td>
        <td width="88%"><?php echo $row['nombre_tb_campus']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'><p align="right">Autor : </td>
        <td width="88%"><?php echo $row['s_autor']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>T�tulo : </td>
        <td width="88%"><?php echo $row['s_titulo']; ?></td>
      </tr>     
	</table>

	<form name="frm" action="registra_reserva.php" method="POST" onSubmit="return validate(this)">
	  <input type="hidden" name="numero" value="<?php echo $row['nro_registro_existe']; ?>">
	<input type="hidden" name="accion" value="reserva">
	<table border="0" class='texto-small' width='100%'>
      <tr class='tr-color-hi'><td colspan='2' class='texto-tabla'>Datos del Usuario</td></tr>
      <tr> 
        <td width="22%" align="right"  class='texto-bold'>Rut Usuario : </td>
        <td width="78%"><input type="text" name="rut" maxlength="12" size="12" onChange="checkRut(this.value);"></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Clave : </td>
        <td width="78%"><input type="password" name="clave" maxlength="12" size="12"></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Retirar en : </td>
        <td width="78%">        <select  name="campus" size="1">
          <option selected value="0">Seleccione el lugar de retiro</option>
		<?php
			$pat_list = explode(":", $conf['web']['excluye_campus']);
			$sql = " EXECUTE sp_WEB_reserva 0, 0, 0, 0, '', '', 'campus' " ;
			$d->execute($sql);
	        while ($row=$d->get_row()) { 
				   foreach ($pat_list as $pattern) {
						$pattern = '/'.$pattern.'/';
	        	 	    if (preg_match($pattern, $row['nombre_tb_campus'])) { continue 2; }
	        	   }   ?>
      	        <option value="<?php echo $row['campus_tb_campus']; ?>"><?php echo $row['nombre_tb_campus']; ?> - <?php echo $row['ciudad_tb_campus']; ?></option>  <?php } ?>
        </select></td>
      </tr>  
      <tr><td colspan='2'>&nbsp;</td></tr>
      <tr><td colspan='2'>Si la biblioteca donde retirar� el ejemplar 
        es distinta a la cual pertenece, deber� considerar que de no ser anulada 
        la reserva antes de una hora se generar� el pr�stamo y se enviar� el ejemplar 
        a la biblioteca seleccionada por usted.
      <p align='right'><input type="submit" value="-= Reservar =-" name="reservar">  <input type="reset" value="Limpiar" name="limpiar"></p>
      </td></tr>
     </table>
</form>
<?php 
}  //while
if ($total == 0  ) { ?>
<tr><td colspan='2'>&nbsp;</td></tr>
</table>
<table border="0" class="texto-small" width="100%">
<tr class='tr-color-hi'><td colspan='2' class='texto-tabla'>Existencia principal no se encuentra disponible para reserva</td></tr>
</table>
<?php 
}

$d->close;
?>

<?php require "../include/html_validator.php"; ?>
</body>
</html>
