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
$sql = " EXECUTE sp_WEB_reserva $numero, $volumen, $parte, $suplemento, $campus, '$formato', 'reserva'" ;
$d->execute($sql);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Reserva en Línea</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.texto-small tr td p {
	font-weight: bold;
	text-align: center;
}
.texto-small tr td p {
	font-weight: normal;
	text-align: right;
}
reserva {
	text-align: left;
}
.texto-small tr td p {
	text-align: left;
	font-weight: bold;
}
.texto-small tr td .texto-small tr .texto-bold p {
	text-align: right;
}
reserva {
	font-weight: bold;
}
reserva {
	font-weight: bold;
}
.reserva {
	font-weight: bold;
}
a:active {
	font-weight: bold;
}
-->
</style>
</head>
<body>
<script language="javascript" src="valida.js" type="text/javascript"></script>
<table border="0" width="100%" class="texto-small">
<tr class="tr-color"><td class="titulo">Reserva en Línea</td></tr>
<tr><td>&nbsp;</td></tr>
<tr>
  <td><p>Antes de realizar una reserva, debe considerar lo siguiente:</p>
    <p>- Si el libro reservado pertenece a la Biblioteca de su Campus y no es retirado en un plazo de 24 Hrs., su cuenta quedar&aacute; inhabilitada por 5 d&iacute;as.    </p>
  <tr><td>&nbsp;</td></tr>
<tr><td>
      <table border="0" class="texto-small" width="100%">
<?php 
while ($row = $d->get_row()) { 
		$num = $row['nro_registro_existe']; ?>    
      <tr class='tr-color-hi'><td colspan='2' class='texto-tabla'>Título a Reservar</td></tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Nro. de Pedido : </td>
        <td width="88%"><?php echo $row['nombre_busqueda']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Nro. de Registro : </td>
        <td width="88%"><?php echo $row['nro_registro_existe']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Formato : </td>
        <td width="88%"><?php echo $row['nombre_tb_format']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Pertenece a : </td>
        <td width="88%"><?php echo $row['nombre_tb_campus']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Autor : </td>
        <td width="88%"><?php echo $row['s_autor']; ?></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Título : </td>
        <td width="88%"><?php echo $row['s_titulo']; ?></td>
      </tr>
      <?php 
	  $sql = " EXECUTE sp_WEB_reserva $numero, $volumen, $parte, $suplemento, $campus, '$formato', 'accesorios'" ;
	  $d->execute($sql);
	  while ($row2 = $d->get_row()) {     ?>
	      <tr><td width='22%' align="right" class='texto-bold'>&nbsp; </td>
   	   <td width="88%">
   	   	<?php if ($row2['nro_registro_principal_existe'] && $row2['Total1'] >0 ) { ?>
  			<div class='texto-small-hi'>Este título posee accesorios tales como CD, Videos, Planos, etc.
			  Si usted desea puede <a href="item_lista_accesorios.php?numero=<?php echo $row2['nro_registro_principal_existe']; ?>">consultarlos 
			  o reservarlos por separado</a>. Si la obra incluye diskettes su pr&eacute;stamo es obligatorio.</div>
		<?php  } // if ?>
      </td></tr>	
	<?php  }   //while?>

      <tr><td colspan='2'>&nbsp;</td></tr>
<?php 
	}  //while
?>
    </table>

  <form name="frm" action="registra_reserva.php" method="POST" onSubmit="return validate(this);">
    <input type="hidden" name="numero" value="<?php echo $num; ?>">
    <input type="hidden" name="accion" value="reserva">
    <table border="0" class='texto-small' width='100%'>
      <tr class='tr-color-hi'><td colspan='2' class='texto-tabla'>Datos del Usuario</td></tr>
      <tr> 
        <td width="22%" align="right"  class='texto-bold'>Rut Usuario :</td>
        <td width="78%"><input type="text" name="rut" maxlength="12" size="12" onChange="checkRut(this.value);"></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Clave :</td>
        <td width="78%"><input type="password" name="clave" maxlength="12" size="12"></td>
      </tr>
      <tr> 
        <td width="22%" align="right" class='texto-bold'>Retirar en : </td>
        <td width="78%">        <select  name="campus" size="1">
          <option selected value="0">Seleccione el lugar de retiro</option>
<?php 
          $pat_list = explode(":", $conf['web']['excluye_campus']);
		
		$sql = " EXECUTE sp_WEB_reserva 0, 0, 0, 0, 0, '', 'campus'" ;
		$d->execute($sql);
        
		while ($row=$d->get_row()) { 
		foreach ($pat_list as $pattern) {
				$pattern = '/'.$pattern.'/';
	         	if (preg_match($pattern, $row['nombre_tb_campus'])) { continue 2; }
	        } ?>
      	    <option value="<?php echo $row['campus_tb_campus']; ?>"><?php echo $row['nombre_tb_campus']; ?> - <?php echo $row['ciudad_tb_campus']; ?></option>	  <?php } ?>
        </select></td>
      </tr>
      <tr><td colspan='2'>&nbsp;</td></tr>
      <tr>
        <td colspan='2'>	Si la biblioteca donde retirará el ejemplar 
        es distinta a la cual pertenece, deberá considerar que de no ser anulada 
        la reserva antes de una hora se generará el préstamo y se enviará el ejemplar 
        a la biblioteca seleccionada.
        <p align='right'><input type="submit" value="-= Reservar =-" name="reservar">  <input type="reset" value="Limpiar" name="limpiar"></p>
      </td></tr>
     </table>
  </form>
</td></tr>
</table>

<?php 
$d->close();
require "../include/html_validator.php"; ?>
</body>
</html>
