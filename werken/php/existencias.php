<?php
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";


/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$numero = param("numero", ""); 

/*
*/ 
	$d = new Database;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
<title>Título Seleccionado</title>
</head>

<body>

 <table border="0" width="100%">
	<tr class="tr-color"><td colspan='2' class='titulo'>&nbsp; Título Seleccionado </td></tr>
	<tr><td colspan='2'>&nbsp;</td></tr>
	<tr><td width="70%" >&nbsp; </td>
       <td width="30%" align='right'>
       	<a href="resumen_total.php?numero=<?php echo $numero; ?>">
       	<img src="../images/e_mas_info3.jpg" alt="Información Bibliográfica" border='0'></a>
        </td>
   </tr>
 </table>

<table border="0" width="100%">
<?php
	$sql =	" EXECUTE sp_WEB_RESUMEN_MIN " . $numero ;
	$d->execute($sql);
	while ($row = $d->get_row()) { 
		echo "<tr>\n";
		if ( !$row['titulo_resumen'] ) {
			echo "\t<td width='35%' align='right' valign='top' class='texto-bold'>" . $row['titulo_resumen'] . "</td>\n";
		} else {
	      echo "\t<td width='35%' align='right' valign='top' class='texto-bold'>" . $row['titulo_resumen'] . " :</td>\n";
		}
		if ($row['ind_direccion'] == 0){ 
		echo "\t<td width='65%' valign='top' align='left' class='texto-normal'>". $row['nombre_resumen'] . "</td>\n";
		echo "</tr>\n";
		}else if ($row['ind_direccion'] == 1){ 
		echo "\t<td width='65%' valign='top' align='left' class='texto-normal'><a href=". $row['nombre_resumen'] . " target='_blank'>" . $row['nombre_resumen'] . "</td>\n";
		echo "</tr>\n";
		}
	}

?>
<tr><td colspan='2'>&nbsp;</td>
</table>
<?php 	
//$rc=mssql_next_result ($data);
$sql =	" EXECUTE sp_WEB_detalle_existencias " . $numero . ", 'nro_control'";
$d->execute($sql);
while ($row = $d->get_row()) { 
	if ($row['nro_control'] != $row['monografia_nro_control']) { ?>
	<table border="0" width="90%">
	<tr><td class="texto-small-hi">
   	<div class="texto-small">** Este título corresponde a un Capítulo de libro, las existencias que se desplegarán 
      corresponden al libro que contiene dicho capítulo</div>
    </td></tr>
    <tr><td>&nbsp;</td></tr>
    </table>
<?php 
	}
}

?>
 
<table border="0" cellpadding="4" width="100%" class="texto-tabla">
<tr bgcolor="#003399" class="texto-usmall"> 
	<td width="10%" height="39" align="center" bgcolor="#003399">Reserva en línea</td>
	<td width="40%" height="39" align="left">Ubicación</td>
	<td width="4%" height="39" align="center">Vol.</td>
	<td width="4%" height="39" align="center">Parte</td>
	<td width="4%" height="39" align="center">Supl.</td>
	<td width="4%" height="39" align="center">Días Préstamo</td>
	<td width="6%" height="39" align="center" bgcolor="#003399">Formato</td>
	<td width="10%" height="39" align="center" bgcolor="#003399">Estado</td>
	<td width="10%" height="39" align="center">Copias</td>
	<td width="6%" height="39" align="center">Pr&oacute;xima Devoluci&oacute;n</td>
</tr>

<?php 
$row = "";
$sql =	" EXECUTE sp_WEB_detalle_existencias " . $numero . ", 'con_reserva'";
$d->execute($sql);
while ($row = $d->get_row()) {
echo "<tr>\n";

if ($row['categoria_tb_catego'] == 3 ) {
	if ( !$row['fecha_final_alta_existe'] ) {   // titulo no es alta demanda (se puede reservar)
		if ( $row['tipo_tb_tipo_existe'] == 1 ) {  // titulo es principal
			echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC' >";
			$url = "item_reserva_principal.php";
			$url .= "?numero=" . $row['nro_control'] . "&amp;volumen=" . $row['nro_volumen_existe'];
         $url .= "&amp;parte=" . $row['nro_parte_existe'] . "&amp;suplemento=" . $row['nro_suplemento_existe'];			
			$url .= "&amp;campus=" . $row['campus_tb_campus'] . "&amp;formato=" . $row['formato_tb_format'];
			echo "<a href='" . $url . "'><font class='url-hi'><b>Reservar</b></font></a></td>\n";
		} else {	// titulo es un accesorio
			echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC' >";
			$url = "item_reserva_accesorio.php";
			$url .= "?numero=" . $row['nro_control'] . "&amp;volumen=" . $row['nro_volumen_existe'];
			$url .= "&amp;parte=" . $row['nro_parte_existe'] . "&amp;suplemento=" . $row['nro_suplemento_existe'];
			$url .= "&amp;campus=" . $row['campus_tb_campus'] . "&amp;formato=" . $row['formato_tb_format'];
			echo "<a href='" . $url . "'><font class='url-hi'><b>Reservar</b></font></a></td>\n";
		}	
	} else {
		if ( $row['fecha_final_alta_existe'] == 1) {  // este titulo es de alta demanda
			if ( $row['tipo_tb_tipo_existe'] == 1 ) {   // Titulo principal 
				echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC'>";
				$url = "item_reserva_principal.php";
				$url .= "?numero=" . $row['nro_control'] . "&amp;volumen=" . $row['nro_volumen_existe'];
				$url .= "&amp;parte=" . $row['nro_parte_existe'] . "&amp;suplemento=" . $row['nro_suplemento_existe'];
				$url .= "&amp;campus=" . $row['campus_tb_campus'] . "&amp;formato=" . $row['formato_tb_format'];
				echo "<a href='" . $url . "'><font class='url-hi'><b>Reservar</b></font></a></td>\n";
			} else {   // titulo es accesorio
				echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC'>";
				$url = "item_reserva_accesorio.php";
				$url .= "?numero=" . $row['nro_control'] . "&amp;volumen=" . $row['nro_volumen_existe'];
				$url .= "&amp;parte=" . $row['nro_parte_existe'] . "&amp;suplemento=" . $row['nro_suplemento_existe'];
				$url .= "&amp;campus=" . $row['campus_tb_campus'] . "&amp;formato=" . $row['formato_tb_format'];
				echo "<a href='" . $url . "'><font class='url-hi'><b>Reservar</b></font></a></td>\n";		
			}
		} else {
			echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC'>&nbsp;&nbsp;</td>";
		}
	}
} else {
	echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
}

// cambio solicitado por los temporales
//echo "\t<td width='10%' height='39' align='center' bgcolor='#6666CC'>&nbsp;&nbsp;</td>";
//echo "<tr align='center' height='39'>";
echo "\t<td width='40%' align='left' bgcolor='#9999FF'>" . $row['nombre_tb_campus'] . "</td>\n";
echo "\t<td width='4%' align='center' bgcolor='#6666CC'>" . $row['nro_volumen_existe'] . "</td>\n";
echo "\t<td width='4%' align='center' bgcolor='#9999FF'>" . $row['nro_parte_existe'] . "</td>\n";
echo "\t<td width='4%' align='center' bgcolor='#6666CC'>" . $row['nro_suplemento_existe'] . "</td>\n";

// prueba de sergio

          if ( $row['dias'] != 0) {
               echo "\t<td width='4%' align='center' bgcolor='#9999FF'>" . $row['dias'] . "&nbsp;". "</td>\n";
               }
          else {
               echo "\t<td width='4%' align='center' bgcolor='#9999FF'>" . "&nbsp;". "</td>\n";
               }
 // fins de prueba de sergio
 
echo "\t<td width='8%' align='center' bgcolor='#6666CC'>" . $row['nombre_tb_format'] . "</td>\n";
echo "\t<td width='10%'align='left'  bgcolor='#9999FF'><b>" . $row['nombre_tb_estado'] . "</b></td>\n";

// prueba de sergio (cambio campo total)

if ( $row['Total'] != 0) {
echo "\t<td width='10%' align='center' bgcolor='#6666CC'>" . $row['Total'] . "</td>\n";
}
else {
echo "\t<td width='4%' align='center' bgcolor='#9999FF'>" . "&nbsp;". "</td>\n";
}

// fin del cambio de sergio (campo total)

echo "\t<td width='6%' align='center' bgcolor='#6666CC'>" . $row['fecha_dev'] ."&nbsp;". "</td>\n";
echo "</tr>\n";
}

?>
              
<?php

// se imprimem los registros recuperados que no tienen posibilidad de reserva

$sql =	" EXECUTE sp_WEB_detalle_existencias " . $numero . ", 'sin_reserva'";
$d->execute($sql);
while ($row = $d->get_row()) {   
//print_r($row);
?>
<tr height='39'> 
	<td width='10%' align='center' bgcolor='#6666CC'>&nbsp;&nbsp;</td>
	<td width='40%' align='left' bgcolor='#9999FF'><?php echo $row['nombre_tb_campus']; ?></td>
	<td width='4%'  align='center' bgcolor='#6666CC'><?php echo $row['nro_volumen_existe']; ?></td>
	<td width='4%'  align='center' bgcolor='#9999FF'><?php echo $row['nro_parte_existe']; ?></td>
	<td width='4%'  align='center' bgcolor='#6666CC'><?php echo $row['nro_suplemento_existe']; ?></td>
	<td width='4%'  align='left' bgcolor='#9999FF'>&nbsp;&nbsp;</td>
	<td width='8%' align='center' bgcolor='#6666CC'><?php echo $row['nombre_tb_format']; ?></td>
	<td width='10%' align='left' bgcolor='#9999FF'><?php echo $row['estado']; ?></td>
<!--	<td width='10%' align='left' bgcolor='#9999FF'>No Disponible</td>-->
	<td width='10%' align='center' bgcolor='#6666CC'><?php echo $row['Total']; ?></td>
	<td width='6%' align='center' bgcolor='#6666CC'><?php echo $row['fecha_dev']; ?>&nbsp;</td>
	
</tr>
<?php } ?>
<tr bgcolor="#003399"> 
	<td width="10%">&nbsp;</td>
	<td width="40%">&nbsp;</td>
	<td width="5%">&nbsp;</td>
	<td width="5%">&nbsp;</td>
	<td width="5%">&nbsp;</td>
	<td width="5%">&nbsp;</td>
	<td width="10%">&nbsp;</td>
	<td width="10%">&nbsp;</td>
	<td width="10%">&nbsp;</td>
	<td width="10%">&nbsp;</td>
</tr>
</table>
<?php require "../include/html_validator.php"; ?>
</body>
</html>
