<?php
require "../include/session.php";
session_init();
$_SESSION['ind_busqueda'] = 0;
?>
<html>
<head>
<title>busqueda@werken</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<form method="POST" action="busqueda.php" onSubmit="return url(this)">
  <input type="hidden" name="texto" value>
  <div align="center">
<div align="center">
      <div align="center"> 
        <table width="220" border="0" bgcolor="#535353" cellpadding="0" cellspacing="0" height="62" align="center">
          
          <tr bgcolor="#6666CC">
            <td width="287" height="14" valign="top">
              <div align="left"><small><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><b><font color="#FFFFFF">&nbsp;BUSCAR</font></b></font> 
                </small><font color="#FFFFFF">
<INPUT TYPE="CHECKBOX"  NAME="ind_busqueda" VALUE="1">
que comience con .</font>..</div>            </td>
          </tr>
          <tr> 
            <td width="287" height="7" valign="top" bgcolor="#6666CC">
              <p><font face="Arial, Helvetica, sans-serif" color="#CCCCCC" size="2"><b> 
                <select name="tipo_busqueda" size="1">
                  <option value="materia" selected>Materia</option>
                  <option value="autor">Autor</option>
                  <option value="titulo">Título</option>
                  <option value="serie">Serie</option>
                  <option value="editorial">Editorial</option>
                </select>
                </b></font></p>            </td>
          </tr>
          <tr bgcolor="#6666CC"> 
            <td height="20" valign="top"> 
              <div align="left"><font color="#6666CC"><small><font face=Arial><font face="Arial, Helvetica, sans-serif" size="2">&nbsp;<font color="#FFFFFF">ESCRIBE 
                AQU&Iacute; LO QUE </font></font><font color="#FFFFFF" size="2"><font face="Arial, Helvetica, sans-serif"> 
                BUSCAS</font></font></font></small></font></div>            </td>
          </tr>
          <tr> 
            <td width="287" height="19" valign="top" bgcolor="#6666CC"><input type="text" name="texto" size="29" value=""></font></td>
          </tr>
          <tr> 
            <td width="287" height="2" valign="top" bgcolor="#6666CC"><small><font face=Arial> 
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
