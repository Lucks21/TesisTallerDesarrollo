<?php
require "../include/session.php";
session_init();

require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$rut = param("rut", ""); 
$clave_ant = param("clave_ant","xa");
$clave_nue = param("clave_nue","xb");
$clave_rep  = param("clave_rep","xc");


// procesa los datos enviados desde el formulario
if ($rut && $clave_ant && $clave_nue && $clave_rep) {
	if ($clave_nue == $clave_rep) {
		 $rut = format_rut($rut);
		 $d= new Database;
		 $sql = " EXECUTE sp_WEB_cambia_clave '$rut', '$clave_ant', '$clave_nue' "; 
		 $d->execute($sql);
		 $row=$d->get_row();
		 $error = $row['valor_retorno'];
	} else {
		$error = "1";
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Validación Reserva</title>
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<script language="javascript" src="valida.js" type="text/javascript"></script>
<blockquote>
<blockquote>
<?php 
if ($rut) { 
echo '<table width="300">';
if ($error == 1)  { ?>
<tr><td class="texto-small-hi"><b>Error en los datos del usuario</b><br>
    Verifique que su información sea correcta (RUT y clave). Si lo es,
    comuníquese con la Dirección de Bibliotecas.<p></td></tr>
<tr><td>&nbsp;</td></tr>
<?php 
} // if 

if ($error == 2) { ?>
<tr><td >El cambio de su clave se ha realizado en forma exitosa<p>
Entrar a la <a href="../php/cta_acceso.php">Cuenta Corriente</a>
</td></tr>
<tr><td>&nbsp;</td></tr>
<?php 
} //if

if ($error == 9 ) { ?>
<tr><td class="texto-small-hi"><b>Ocurrio un error al cambiar la clave</b><p>Por favor vuelva a intentarlo más tarde.</p></td></tr>
<tr><td>&nbsp;</td></tr>
<?php 
} 

echo '</table>';
if ($error ==2) {exit; }
}   //if rut
?>


      <form action="<?php echo $PHP_SELF; ?>" method="POST"  onSubmit="return valida_password(this)" name="frm">
          <table width="280" border="0" bgcolor="#003399" cellpadding="0" cellspacing="0" >
            <tr bgcolor="#003399"> 
              <td colspan="2" height="28"> 
                <div align="right"><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"></font></font><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><b><img src="../images/motivo1.gif" width="30" height="28"></b></font></font></font></font><font face="Impact" size="5" color="#000000"><img src="../images/t_nuevaclave.gif" width="253" height="28"></font></font></div>
              </td>
            </tr>
            <tr> 
              <td rowspan="11" align="left" height="150" valign="top" width="30" bgcolor="#003399"> 
                <div align="left"></div>
              </td>
              <td rowspan="4" height="30" width="257" valign="top" bgcolor="#6666cc">&nbsp;</td>
            </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> 
              <td width="257" height="14" bgcolor="#CCCCFF"> 
                <div align="left"><font size="2" color="#FFCC33"><font face="Arial, Helvetica, sans-serif" color="#003399">&nbsp;&nbsp;<b>RUT:</b></font></font></div>
              </td>
            </tr>
            <tr> 
              <td width="257" height="9" valign="top" bgcolor="#6666cc"> 
                <div align="left"><font face="Arial" size="2" color="#003399"> 
                  <input maxlength=12 name=rut size=12 value="<?php if ($rut != "") {echo $rut;} ?>" onChange="checkRut(this.value);">
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td width="257" bgcolor="#CCCCFF" height="14"> 
                <p align="left"><font face="Arial" size="2" color="#FFCC33">&nbsp;<font color="#003399">&nbsp;</font></font><font face="Arial" color="#003399"><font face="Arial, Helvetica, sans-serif" size="2"><b>CLAVE 
                  ANTERIOR:</b></font></font></p>
              </td>
            </tr>
            <tr> 
              <td width="257" height="12" valign="top" bgcolor="#6666cc"> 
                <div align="left"><font face="Arial" size="2" color="#FFCC33"> 
                  <input maxlength=10 name=clave_ant size=12 type=password>
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td width="257" height="2" bgcolor="#CCCCFF"> 
                <div align="left"><font face="Arial" size="2" color="#FFCC33">&nbsp;&nbsp;</font><font face="Arial" color="#FFCC33"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#003399">CLAVE 
                  NUEVA:</font></b></font></font></div>
              </td>
            </tr>
            <tr> 
              <td width="257" height="16" bgcolor="#6666cc"> 
                <div align="left"><font face="Arial" size="2" color="#FFCC33"> 
                  <input maxlength=10 name=clave_nue size=12 type=password>
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td width="257" height="2" bgcolor="#CCCCFF"> 
                <div align="left"><font face="Arial" size="2" color="#FFCC33"><b><font color="#003399">&nbsp;&nbsp;REPETIR 
                  </font></b></font><font face="Arial" color="#003399"><font face="Arial, Helvetica, sans-serif" size="2"><b>CLAVE 
                  NUEVA:</b></font></font></div>
              </td>
            </tr>
            <tr> 
              <td rowspan="2" align="left" height="32" valign="top" background="../images/motivo.gif" width="30" bgcolor="#003399">&nbsp;</td>
              <td width="257" height="16" bgcolor="#6666cc"> 
                <div align="left"><font face=Arial size=2> 
                  <input maxlength=10 name=clave_rep size=12 type=password>
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td width="257" height="2" bgcolor="#6666cc"> 
                <div align="right"><font face=Arial size=2> 
                  <input name=Aceptar type=submit value="Aceptar">&nbsp;&nbsp;
                  <input name=Limpiar type=reset value=Limpiar>
                  </font></div>
              </td>
            </tr>
          </table>
    </form>
		<font size="2" face="Arial, Helvetica, sans-serif">Importante: 
            Recuerde ingresar password sin <b><font color="#FF3333">&ntilde;, 
            ni acentos</font></b></font><br><br>
 
 	<a href="javascript:window.close()"><img src="../images/etiquetas/cerrar_ventana.gif" width="90" height="19" border="0"></a>
</blockquote>
</blockquote>
</body>
</html>
