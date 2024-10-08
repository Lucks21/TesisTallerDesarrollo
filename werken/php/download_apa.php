<?php
require  "../include/session.php";
session_init();

unset($_SESSION['rut']);
unset($_SESSION['clave']);
unset($_SESSION['clave_base']);
?>
<html>
<head>
<TITLE>Consulta Cuenta Usuario.</TITLE>
<META HTTP-EQUIV="Expires" CONTENT="0">
<META HTTP-EQUIV="Pragma" CONTENT="No-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" link="#6666CC" vlink="#3333FF" alink="#6666CC" text="#003399">
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37008911-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script language="javascript" src="valida.js" > </script>
<blockquote>
      <form name="frm" action="show_apa.php" method="post" onSubmit="return valida_cuenta(this);">
      	  <input type="hidden" name="ini" value="1">
          <table width="372" border="0" bgcolor="#535353" cellpadding="0" cellspacing="0"  height="196">
            <tr bgcolor="#003399"> 
              <td colspan="2" height="13"> 
                <div align="left"><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><b><img src="../images/motivo1.gif" width="18" height="28"><img src="../images/t_cuenta.gif" width="292" height="28"></b></font></div>
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
                  <input maxlength="12" name="rut" size="12" onChange="checkRut(this.value);">
                  <br>
                  <br>
                  </font></div>
              </td>
            </tr>
            <tr> 
              <td width="234" bgcolor="#CCCCFF" height="16"> 
                <p align="left"><font face="Arial" size="2" color="#003399">&nbsp;&nbsp;</font><font face="Arial" color="#003399"><font face="Arial, Helvetica, sans-serif" size="2"><b>CLAVE:</b></font></font></p>
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
                  <input name=Aceptar type=submit value=Aceptar>
                  &nbsp;&nbsp;&nbsp;&nbsp; 
                  <input name=Limpiar type=reset value=Limpiar>
                  </font></div>
              </td>
            </tr>
          </table><br><br>
          <table width="372" border="0" >
            <tr class="texto_bold">
              <td><ul>
            <li><font face="Verdana, Arial, Helvetica, sans-serif">Para concocer 
              su clave de usuario, debe dirigirse a Biblioteca y consultar en 
              forma personal con su Credencial o C.I.</font></li>
                   </ul>
                  </td>
            </tr>
				</table>
        </div>
      </form>
</blockquote>      
</body>
</html>
