<?php
require "../include/session.php";
session_init();
$_SESSION['ind_busqueda'] = 0;
?>
<html>
<head>
<title>busqueda@werken</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<script language="JavaScript">

function url(f) {
	if (f.tipo_busqueda.value == "titulo") {
		f.action="busqueda_titulo.php";
		return true;
	}
	return true;
}

function ayud_avanzada()
{ 
window.open("../ayuda2/Index.htm", "_new", "resizable=1,scrollbars=1 ,toolbar=0,location=0,menubar=0,status=0,width=800,height=500") 
} 
</script>
</head>

<body bgcolor="#FFFFFF" leftmargin="7" topmargin="7" marginwidth="0" marginheight="0" text="#003366" link="#6666CC" vlink="#3333FF" alink="#6666CC">
<form method="POST" action="busqueda_desarrollo.php" onSubmit="return url(this)">
  <input type="hidden" name="texto" value>
  <div align="center">
<div align="center">
      <div align="center"> 
        <table width="370" border="0" bgcolor="#535353" cellpadding="0" cellspacing="0" height="204" align="center">
           <tr><td height="15" bgcolor="#003399" width="18" colspan='2'>&nbsp;</td></tr>

          <tr> 
            <td height="15" bgcolor="#003399" width="18"><img src="../images/motivo1.gif" width="18" height="28"></small></td>
            <td height="15" bgcolor="#003399"><div align="left"><img src="../images/t_avanzada.gif" height="28"><a href="javascript:ayud_avanzada()"><img name="ayuda" border="0" src="../images/b_ayuda.gif" width="73" height="28"></a></div>
            </td>
          </tr>
          <tr valign="top"> 
            <td rowspan="3" width="109" height="74" valign="top" bgcolor="#330066"> 
              <div align="left"><img src="../images/foto_avan.gif" width="108" height="81"></div>
            </td>
            <td width="500" height="47" valign="top" bgcolor="#6666CC">&nbsp;</td>
          </tr>
          <tr> 
            <td width="500" height="14" bgcolor="#CCCCFF" valign="top">
              <div align="left"><small><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><b><font color="#003366">&nbsp;BUSCAR</font></b></font><font face="Arial" color="#003366"> 
                </font></small><INPUT TYPE="CHECKBOX"  NAME="ind_busqueda" VALUE="1"> que comience con ...</div>
            </td>
          </tr>
          <tr> 
            <td width="500" height="7" valign="top" bgcolor="#6666CC">
              <p><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><b> 
                <select name="tipo_busqueda" size="1">
                  <option value="materia" selected>Materia</option>
                  <option value="autor">Autor</option>
                  <option value="titulo">Título</option>
                  <option value="serie">Serie</option>
                  <option value="editorial">Editorial</option>
                </select>
                </b></font></p>
            </td>
          </tr>
          <tr> 
            <td height="20" bgcolor="#330066">&nbsp;</td>
            <td height="20" bgcolor="#CCCCFF" valign="top"> 
              <div align="left"><small><font face=Arial><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><b><font color="#003366">&nbsp;ESCRIBE 
                AQU&Iacute; LO QUE </font></b></font><font size="2" color="#003366"><b><font face="Arial, Helvetica, sans-serif"> 
                BUSCAS</font></b></font></font></small></div>
            </td>
          </tr>
          <tr> 
            <td rowspan="2" height="4" valign="bottom" width="111" background="../images/motivo.gif" bgcolor="#330066">&nbsp;</td>
            <td width="356" height="19" valign="top" bgcolor="#6666CC"><input type="text" name="texto" size="29" value=""></font></td>
          </tr>
          <tr> 
            <td width="356" height="2" valign="top" bgcolor="#6666CC"><small><font face=Arial> 
              <input name=Buscar type=submit value=Buscar>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <input name=B1 type=reset value=Limpiar>
              </font></small></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</form>
<p>&nbsp; </p>
</body>
</html>
