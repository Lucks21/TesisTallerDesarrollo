<?php
require  "../include/session.php";
session_init_nocache();

require "../include/database.php";
require "../include/functions.php";

/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/

$rut = ($_SESSION['rut']) ? $_SESSION['rut'] : param("rut",""); 
$nombre = ($_SESSION['nombre_usuario']) ? ($_SESSION['nombre_usuario']) : param("nombre","");
$titulo = param("titulo",""); 
$autor_editor = param("autor_editor",""); 
$editorial = param("editorial",""); 
$isbn_issn = param("isbn_issn","");
$fecha_publicacion = param("fecha_publicacion", "");
$cantidad = param("cantidad", "");
$tipo_material = param ("tipo_material","M");
$observacion = param("observacion", "");
$urgencia = param("urgencia","0");
$reparticion = (param("reparticion_usuario","")) ? param("reparticion_usuario","") : $_SESSION['reparticion_usuario'];
$reparticion = rtrim($reparticion);
$accion= param("accion","");
$id=param("id","");
$sid = session_id();
$nro_items = param("nitems",0);
$error =0;
$msg = "";
$rut=format_rut($rut);

$d = new Database;
if ( $accion == "inicio" ) {	
	$sql = " SELECT reparticion_tb_repar, nombre_tb_repar, ";
	$sql .= " rep_usuario = (SELECT reparticion_tb_repar FROM usuario WHERE rut_usuario = '$rut') ";
	$sql .= " FROM tb_reparticion ORDER BY nombre_tb_repar";
	$d->execute($sql);
	$data_reparticion = array();
	while ($row = $d->get_row()) { 
			$tmp_array = array( $row['reparticion_tb_repar'], $row['nombre_tb_repar'] );
			array_push ($data_reparticion, $tmp_array); 
			$reparticion = $row['rep_usuario'];
	}

	//almacena los datos recuperados desde la BD en la session 
	$_SESSION["reparticiones"] 	= $data_reparticion;
	$_SESSION["reparticion_usuario"] = $reparticion;
	$_SESSION["num_reparticiones"] = $d->get_numrows();
	$_SESSION["nombre_usuario"] = $nombre;
	$_SESSION["rut"] = $rut;
		
	$sql = " SELECT count(*) as total from carro_solicitud_material where csm_rut = '$rut' and csm_session= '$sid' ";
	$d->execute($sql);
	$row=$d->get_row();
	$nro_items = ($row['total']) ? $row['total'] : 0;
	
}

if ( $accion == "mas" ) {   // el usuario desea agregar mas items a la solicitud.
	$sql = " SELECT count(*) as total from carro_solicitud_material where csm_rut = '$rut' and csm_session= '$sid' ";
	$d->execute($sql);
	$row=$d->get_row();
	$nro_items = ($row['total']) ? $row['total'] : 0;
}
	
if ( $accion == "agregar" ) {	// el usuario presion'o el "boton agregar (enviar al carro)"
	if ($cantidad && $rut && $sid  && ($titulo || $autor_editor || $editorial || $isbn_issn ) ) {  // campos obligatorios
		$rut = format_rut($rut);

		if ($fecha_publicacion =='') $fecha_publicacion = 'null';
		
		$sql = " EXEC sp_WEB_carro_solicitud_material 'agregar', '$rut', '$sid', '$titulo', '$autor_editor', '$editorial', ";
		$sql .= " '$isbn_issn', $fecha_publicacion, $cantidad, '$tipo_material', '$observacion', $urgencia, $reparticion, null " ;
		$d->execute($sql);

		$row = $d->get_row();
		$error = $row['valor_retorno'];
		$nro_items = $row['nro_items'];
		$en_carro = 1;
		$titulo = $autor_editor = $editorial = $isbn_issn = $fecha_publicacion = $cantidad = $observacion =  "";
		$reparticion = $_SESSION['reparticion_usuario'];
		$urgencia = 0;
	} 
}

if ($accion == "editar") {	// el usuario desea editar este item.
	$sql = " SELECT * FROM carro_solicitud_material where csm_correlativo=$id and csm_session='$sid' and csm_rut='$rut' ";
	$d->execute($sql);
	$row = $d->get_row();
	$titulo = $row['csm_titulo'];
	$autor_editor = $row['csm_autor_editor'];
	$editorial = $row['csm_editorial'];
	$isbn_issn = $row['csm_isbn_issn'];
	$fecha_publicacion = $row['csm_fecha_publicacion'];
	$cantidad = $row['csm_cantidad'];
	$tipo_material = $row['csm_tipo_material'];
	$observacion = $row['csm_observacion'];
	$urgencia = $row['csm_prioridad'];
	$reparticion = $row['csm_reparticion'];
}

if ($accion=="editar_guardar") {	// el usuario modific'o el item... ahora se guarda.
	$rut = format_rut($rut);
	
	$sql = "sp_WEB_carro_solicitud_material 'editar', '$rut', '$sid', '$titulo', '$autor_editor', '$editorial', ";
	$sql .= " '$isbn_issn', $fecha_publicacion, $cantidad, '$tipo_material', '$observacion', $urgencia, $reparticion, $id " ;
	$d->execute($sql);
	$row = $d->get_row();	
	$error = $row['valor_retorno'];
			
	if ($error == 0) {	
		$titulo = $autor_editor = $editorial = $isbn_issn = $fecha_publicacion = $cantidad = $observacion =  "";
		$urgencia = 0;
		$reparticion = $_SESSION['reparticion_usuario'];
		$accion = "";
	}
}

$d->close();
?>
<html>
<head>
<TITLE>Solicitud de Material</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#CCCCFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" link="#6666CC" vlink="#3333FF" alink="#6666CC" text="#003399">
<script language="javascript" src="valida_solicitud_material.js" type="text/javascript"></script>
<blockquote>  

<?php 
if ($error == 1) { ?>
	<blockquote>
    <table width="620" border="0" cellspacing="0" cellpadding="0">
      <tr><td class="url-hi"><strong>El material solicitado no pudo ser ingresado en la base, inténtelo nuevamente</strong></td></tr>	
	  </table>
	</blockquote>	  
<?php } ?>
<blockquote> 
  <form name="frm" action="<?php echo $PHP_SELF; ?>" method="POST" onSubmit="return valida_solicitud_material(this);">
    <table width="620" border="0" cellspacing="0" cellpadding="4">
      <tr> 
        <td bgcolor="#000099" width="412"><font face="Arial, Helvetica, sans-serif" size="2"><font face="Arial, Helvetica, sans-serif" size="2">
        <img src="../images/motivo1.gif" width="18" height="28" align="absmiddle"></font><font color="#FFFFFF"><b>SOLICITUD DE MATERIAL</b></font></font></td>
        <td bgcolor="#000099" width="82">&nbsp;</td>
        <td bgcolor="#000099" width="126" ><a href="carro.php"><img src="../images/carro.gif" border="0" alt="Ver el Carro con las =Solicitudes de Material="></a>
        	<div class="texto-tabla">Nro. de Items : <b><?php echo $nro_items; ?></b></div>
        </td>
      </tr>
      <tr><td height="26" colspan="3"> 
      <div align="right"><a href="cta_prestamos.php"><img border=0 src="../images/volver.jpg" ALT="volver a mi cuenta personal"></a>&nbsp;&nbsp;<a href="javascript:window.close()"><img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>	</div>
	  </td>
</tr>	  
    </table>
    <table width="620" border="0" cellpadding="4" cellspacing="2">
      <tr background="../images/motivo.gif"> 
        <td align="left" colspan="5" valign="top" height="5">&nbsp; </td>
      </tr>
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"> 
            <b>Nombre solicitante :</b></font></div>
        </td>
        <td bgcolor="#CCCCFF" colspan="3"><font face="Arial, Helvetica, sans-serif" size="2"><?php echo $_SESSION["nombre_usuario"]; ?>
        </font></td>
      </tr>
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"> 
            <b>Unidad solicitante :</b></font></div>
        </td>
        <td bgcolor="#CCCCFF" colspan="3"> 
          <select name="reparticion_usuario" size="0" class="texto-dropdown">
            <option value="0">Seleccione su Unidad</option>
            <?php 
            	for ($i=0; $i < $_SESSION['num_reparticiones'] ; $i++ ) {
            		if ( $reparticion == $_SESSION["reparticiones"][$i][0])  {
     		            	echo "<option value=\"" . $_SESSION["reparticiones"][$i][0] . "\" selected >" . substr( $_SESSION["reparticiones"][$i][1],0,70) . "</option>\n";
     		         } else {
		            		echo "<option value=\"" . $_SESSION["reparticiones"][$i][0] . "\">" . substr($_SESSION["reparticiones"][$i][1] ,0,70) . "</option>\n";
		            }
            	}  // for 
            ?>
          </select>
        </td>
      </tr>
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#003399"><b>T&iacute;tulo 
            :</b></font></font></div>
        </td>
        <td bgcolor="#CCCCFF" colspan="3"> <font face="Arial, Helvetica, sans-serif" size="2"> 
          <input type="text" name="titulo" maxlength="300" size="60" value="<?php echo $titulo; ?>">
          </font></td>
      </tr>
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#003399"><b>Autor 
            / Editor :</b></font></font></div>
        </td>
        <td bgcolor="#CCCCFF" colspan="3"> <font face="Arial, Helvetica, sans-serif" size="2"> 
          <input type="text" name="autor_editor" maxlength="300" size="60" value="<?php echo $autor_editor; ?>">
          </font></td>
      </tr>
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#003399"><b>Editorial 
            :</b></font></font></div>
        </td>
        <td bgcolor="#CCCCFF" colspan="3"> <font face="Arial, Helvetica, sans-serif" size="2"> 
          <input type="text" name="editorial" maxlength="300" size="60" value="<?php echo $editorial; ?>">
          </font></td>
      </tr>
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b>Isbn 
            / Issn :</b></font></div>
        </td>
        <td width="82" bgcolor="#CCCCFF"> <font face="Arial, Helvetica, sans-serif" size="2"> 
          <input type="text" name="isbn_issn" maxlength="20" size="14" value="<?php echo $isbn_issn; ?>">
          </font></td>
        <td width="119" bgcolor="#CCCCFF"> 
          <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b>A&ntilde;o<br>
            Publicaci&oacute;n</b></font></div>
        </td>
        <td width="206" bgcolor="#CCCCFF"> <font face="Arial, Helvetica, sans-serif" size="2"> 
          <input type="text" name="fecha_publicacion" maxlength="4" size="14" value="<?php echo $fecha_publicacion; ?>">
          </font></td>
      </tr>
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#003399"><b>Cantidad 
            requerida :</b></font></font></div>
        </td>
        <td width="82" bgcolor="#CCCCFF"> <font face="Arial, Helvetica, sans-serif" size="2"> 
          <input type="text" name="cantidad" maxlength="4" size="14" value="<?php echo $cantidad; ?>">
          </font></td>
        <td bgcolor="#CCCCFF" width="119"> 
          <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"> 
            <b>Tipo de<br>
            Material</b></font></div>
        </td>
        <td bgcolor="#CCCCFF" width="206"><font face="Arial, Helvetica, sans-serif" size="2"> 
			<?php if ($tipo_material == "M") { ?>
          		<input type="radio" name="tipo_material" value="M" checked>
			    <b>Libro</b></font> <font face="Arial, Helvetica, sans-serif" size="2"> 
          		<input type="radio" name="tipo_material" value="S">
          		<b>Revista</b></font></td>
			<?php } elseif ($tipo_material == "S") { ?>
          		<input type="radio" name="tipo_material" value="M" >
			    <b>Libro</b></font> <font face="Arial, Helvetica, sans-serif" size="2"> 
          		<input type="radio" name="tipo_material" value="S" checked>
          		<b>Revista</b></font></td>
			<?php } ?>
      </tr>
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#003399"><b>Observación :</b></font></font></div>
        </td>
        <td bgcolor="#CCCCFF" colspan="3"> <font face="Arial, Helvetica, sans-serif" size="2"> 
          <input type="text" name="observacion" maxlength="512" size="60" value="<?php echo $observacion; ?>">
          </font></td>
      </tr>      
      <tr valign="middle"> 
        <td width="151" bgcolor="#CCCCFF"> 
          <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#003399"><b>Material Urgente ? :</b></font></font></div>
        </td>
        <td bgcolor="#CCCCFF" colspan="3"> <font face="Arial, Helvetica, sans-serif" size="2"> 
			<?php if ($urgencia == 1) { ?>
          		<input type="checkbox" name="urgencia" value="1" checked >
			<?php } else { ?>
          		<input type="checkbox" name="urgencia" value="0">
			<?php } ?>				
          </font></td>
      </tr>      
      <tr> 
        <td bgcolor="#6666CC" colspan="4"> 
          <div align="right"> <font face="Arial, Helvetica, sans-serif" size="2"> 
            <input name="boton_agregar" type="submit" value="Enviar al carro">
            <input name="boton_limpiar" type="reset" value="Limpiar">
            </font></div>
        </td>
      </tr>
    </table>
    <font face="Arial, Helvetica, sans-serif" size="2"> 
	<?php if ($accion == "editar") { ?>
		<input type="hidden" name="accion" value="editar_guardar">
	<?php } else { ?>
		<input type="hidden" name="accion" value="agregar">
	<?php } ?>
    <br>
    </font> 
  </form>
</blockquote>      
</body>
</html>
