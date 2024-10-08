<script language="javascript" type="text/javascript">
function url(f) {
	if (f.tipo_busqueda.value == "titulo") {
		f.action="busqueda_titulo.php";
	} else {
		f.action="busqueda.php";
	}
	if (f.texto.value== "") { return false; }		
	return true;
}

function clear_texto () {
	document.forms[0].texto.value="";
}
</script>
<form action="<?php echo $PHP_SELF; ?>" method="POST" onSubmit="return url(this)" id="buscar">
<select name="tipo_busqueda" onChange="return clear_texto();">
<?php 
	$opc = array ( "materia" => " Materia ", 	"autor" => " Autor ", 	"editorial" => " Editorial ",
				"titulo" => " Título ",  "serie" => " Serie " );
				
	foreach (array_keys($opc) as $clave) {
		if ($tipo_busqueda == $clave) {
			echo "<option value=\"" . $clave . "\" selected>" . $opc[$clave] . "</option>\n";
		} else {
			echo "<option value=\"" . $clave . "\">" . $opc[$clave] . "</option>\n";
		}
	}
?>
</select>&nbsp;
<input type="text" name="texto" size="30" maxlength="512" value="<?php echo stripslashes($texto); ?>">&nbsp;
<input type="submit" value="buscar !">
  <small>
	<b>
		<font color="#FFFFFF">
			<INPUT TYPE="CHECKBOX"   <?php if($_SESSION['ind_busqueda'] == 1) echo "checked"; ?> NAME="ind_busqueda" VALUE="1"> que comience con ... 
		</font>
	</b>
</small>
</form>
