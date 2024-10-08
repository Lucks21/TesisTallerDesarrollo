<?php
require  "../include/session.php";
session_init_nocache();
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

/* se recuperan los datos de la sesion */
$rut 	= $_SESSION['rut'];
$k 		= $_SESSION['clave'];
$cuenta = $_POST['contador'];
$libros = $_SESSION['exis_concatenadas'];
?>
<html>
<head>
<TITLE>Autorización Renovación</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" link="#6666CC" vlink="#3333FF" alink="#6666CC" text="#003399">
<script language="javascript" src="valida.js"></script>
<blockquote>
      <form name="frm" action="registra_renovacion_prestamo.php" method="post" onSubmit="return valida_cuenta(this);">
          <table width="372" border="0" bgcolor="#535353" cellpadding="0" cellspacing="0"  height="196">
            <tr bgcolor="#003399"> 
              <td colspan="2" height="13"> 
                <div align="left"><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><b><img src="../images/motivo1.gif" width="18" height="28"><font class="titulo">Confirmación Renovación</font></b></font></div>
              </td>
            </tr>
            <tr> 
              <td rowspan="9" align="left" height="145" valign="top" background="../images/motivo.gif" bgcolor="#003366"> 
                <div align="left"><img src="../images/foto_cuenta.gif" width="150" height="113"></div>
              </td>
              <td rowspan="4" height="30" width="234" valign="top" bgcolor="#6666CC">&nbsp;</td>
            </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> 
              <td width="234" height="14" bgcolor="#CCCCFF"> 
                <div align="left"><font size="2"><font face="Arial, Helvetica, sans-serif" color="#003399">&nbsp;&nbsp;<b>RUT:</b></font></font></div>
              </td>
            </tr>
            <tr> 
              <td width="234" height="16" valign="top" bgcolor="#6666CC"> 
                <div align="left"><font face=Arial size=2> 
                  <input maxlength="12" name="rut" size="12" onChange="checkRut(this.value);" value="<?php echo $rut; ?>">
                  <br>
                  <br>
                  </font>
				  </div>
              </td>
            </tr>
            <tr> 
              <td width="234" bgcolor="#CCCCFF" height="16"> 
                <p align="left"><font face="Arial" size="2" color="#003399">&nbsp;&nbsp;</font>
				<font face="Arial" color="#003399"><font face="Arial, Helvetica, sans-serif" size="2"><b>CLAVE:</b></font></font></p>
              </td>
            </tr>
            <tr> 
              <td width="234" height="37" valign="top" bgcolor="#6666CC"> 
                <div align="left"><font face=Arial> </font><font face=Arial size=2> 
                  <input maxlength="12" name="clave" size="12" type="password">
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td width="234" height="2" bgcolor="#6666CC"> 
                <div align="left"><font face=Arial size=2> 
                  <input name=Aceptar type=submit value=Aceptar class="botonenviar">
                  </font></div>
              </td>
            </tr>
          </table>
        </div>
		  <input type="hidden" name="contador" value="<?php echo $cuenta ;?>">
 		  <input type="hidden" name="libros" value="<?php echo $libros ;?>">
      </form>
	  <form name="boton" action="cta_prestamos.php" >
          <table width="372" border="0" >
               <tr><td align="right">
			   <input name="volver" type="button" value="Volver" class="botonenviar" onClick="document.forms.boton.submit()">
			   </td></tr>				
          </table>
	  </form>
</blockquote>      
</body>
</html>
