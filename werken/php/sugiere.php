<?php
require "../include/session.php";
session_init();
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";
require "../include/debuglib.php";

include_once 'captcha_desarrollo.php';

/*
	
	if (($_SESSION['tmptxt']) != ($_POST['tmptxt'])) {
	echo "Intentalo nuevamente...";
		exit;
	} */
	

if ($conf['web']['showtime']) { $t['ini'] = getmicrotime(); }
/*
* recupera los parametros desde el web, utiliza la funcion 
* param(variable, default_value);
*/
$texto = param("texto", ""); 
$texto1='';

$tipo_busqueda = param("tipo_busqueda", "materia");
$pag = param("pag", "1");

/*
* valida si la consulta ya habia sido almacenada previamente en alguna session,
* de lo contrario se conecta a la BD para extraer los resultados.
* Para utilizar la informacion almacenada en una session, los datos de consulta
* (texto, tipo_busqueda) recibidos por el web deben ser identicos a los almacenados en
* la session, de lo contrario significa que el usuario esta buscando otra cosa y el contenido
* de la session debe ser refrescada.  
*/ 




  if (($_SESSION["texto_busqueda"] != $texto1) || ($_SESSION["tipo_busqueda"] != $tipo_busqueda )) {
	$done = ""; //(db)
	
	$d = new Database();	
	$sqlstring ="select * from biblioteca..tb_campus ";
	//echo $sqlstring;
	$d->execute($sqlstring);
	$numrows = $d->get_numrows();
	//echo $numrows.'---------------------------';
	$data_biblio = array();
	while ($row = $d->get_row()) {
		array_push ($data_biblio, $row ); 
	}
	
	
	$sqlstring ="select carrera_tb_carrera, nombre_tb_carrera from biblioteca..tb_carrera ";
	//echo $sqlstring;
	$d->execute($sqlstring);
	$numrows = $d->get_numrows();
	//echo $numrows.'---------------------------';
	$data_carrera = array();
	while ($row = $d->get_row()) {
		array_push ($data_carrera, $row ); 
	}
	

	
	
	
	if ($conf['web']['showtime']) { $t['session1_set'] = getmicrotime(); }	
	

	session_write_close();
	$d->close();
	$pag=1;
  } 
  $numrows = $_SESSION["busq_numrows"];

if ($conf['web']['showtime']) { $t['end'] = getmicrotime(); }	
?>

<html>

<head>
<title>Formulario Sugerencias</title>
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

        function validate(f) {
         if (f.nombre.value == "") {
                alert("\n\nPor favor ingrese su nombre.");
                return false;
         }
         if (f.correo.value == "") {
                alert("\n\nPor favor ingrese su correo electrónico.");
                return false;
         }
         if (f.observaciones.value == "") {
                alert("\n\nPor favor ingrese su mensaje.");
                return false;
         }
        if (f.validacion.value == "") {
                alert("\n\nPor favor ingrese el texto de la imagen.");
                return false;
        }  
		if (f.validado.value == "" ||  f.validado.value <1) {
                alert("\n\nEl texto no corresponde a la imagen.");
                return false;
        }
		 

         return true; 
        }

		
		
function checkRutField(rut)
{
	var tmpstr = "";
	for ( i=0; i < rut.length ; i++ )
		if ( rut.charAt(i) != ' ' && rut.charAt(i) != '.' && rut.charAt(i) != '-' )
			tmpstr = tmpstr + rut.charAt(i);
	rut = tmpstr;
	largo = rut.length;
// [VARM+]
	tmpstr = "";
	for ( i=0; rut.charAt(i) == '0' ; i++ );
		for (; i < rut.length ; i++ )
			tmpstr = tmpstr + rut.charAt(i);
	rut = tmpstr;
	largo = rut.length;
// [VARM-]
	if ( largo < 2 )
	{
		alert("Debe ingresar el rut completo.");
		document.form1.rut_aux.focus();
		document.form1.rut_aux.select();
		document.form1.rut_aux.value="";		
		return false;

	}
	for (i=0; i < largo ; i++ )
	{
		if ( rut.charAt(i) != "0" && rut.charAt(i) != "1" && rut.charAt(i) !="2" && rut.charAt(i) != "3" && rut.charAt(i) != "4" && rut.charAt(i) !="5" && rut.charAt(i) != "6" && rut.charAt(i) != "7" && rut.charAt(i) !="8" && rut.charAt(i) != "9" && rut.charAt(i) !="k" && rut.charAt(i) != "K" )
		{
			alert("El valor ingresado no corresponde a un R.U.T valido.");
			document.form1.rut_aux.focus();
			document.form1.rut_aux.select();
			document.form1.rut_aux.value="";			
			return false;
		}
	}
	var invertido = "";
	for ( i=(largo-1),j=0; i>=0; i--,j++ )
		invertido = invertido + rut.charAt(i);
	var drut = "";
	drut = drut + invertido.charAt(0);
	drut = drut + '-';
	cnt = 0;
	for ( i=1,j=2; i<largo; i++,j++ )
	{
		if ( cnt == 3 )
		{
			drut = drut + '.';
			j++;
			drut = drut + invertido.charAt(i);
			cnt = 1;
		}
		else
		{
			drut = drut + invertido.charAt(i);
			cnt++;
		}
	}
	invertido = "";
	for ( i=(drut.length-1),j=0; i>=0; i--,j++ )
		invertido = invertido + drut.charAt(i);
	document.form1.rut_aux.value = invertido;
	if ( checkDV(rut) )
		return true;
	return false;
}

function checkDV( crut )
{
	largo = crut.length;
	if ( largo < 2 )
	{
		alert("Debe ingresar el rut completo.");
		document.form1.rut_aux.focus();
		document.form1.rut_aux.select();
		document.form1.rut_aux.value="";		
		return false;
	}
	if ( largo > 2 )
		rut = crut.substring(0, largo - 1);
	else
		rut = crut.charAt(0);
	dv = crut.charAt(largo-1);
	checkCDV( dv );
	if ( rut == null || dv == null )
		return 0;
	var dvr = '0';
	suma = 0;
	mul = 2;
	for (i= rut.length -1 ; i >= 0; i--)
	{
		suma = suma + rut.charAt(i) * mul;
		if (mul == 7)
			mul = 2;
		else
			mul++;
	}
	res = suma % 11;
	if (res==1)
		dvr = 'k';
	else if (res==0)
		dvr = '0';
	else
	{
		dvi = 11-res;
		dvr = dvi + "";
	}
	if ( dvr != dv.toLowerCase() )
	{
		alert("EL rut es incorrecto.");
		document.form1.rut_aux.focus();
		document.form1.rut_aux.value="";
		return false;
	}
		
	return true;
}
function checkCDV( dvr )
{
	dv = dvr + "";
	if ( dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4' && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9' && dv != 'k'  && dv != 'K')
	{
		alert("Debe ingresar un digito verificador valido.");
		document.form1.rut_aux.focus();
		document.form1.rut_aux.select();
		document.form1.rut_aux.value="";
		return false;
	}
	return true;
}

function valida() {
	rut_val = document.form1.rut_aux.value;
	if ( rut_val.length == 0 ) 
	{
		alert( "Ingrese su R.U.T.");
		document.form1.rut_aux.focus();
		return;
	}
	if ( !checkRutField(document.form1.rut_aux.value) )
	{	
		return;
	}
	var tmpstr = "";	
	for ( i=0; i < rut_val.length ; i++ )
		if ( rut_val.charAt(i) != ' ' && rut_val.charAt(i) != '.' && rut_val.charAt(i) != '-' )
			tmpstr = tmpstr + rut_val.charAt(i);
	rut_val = tmpstr;
	rut_valor = rut_val.substring(0,rut.length);

	document.form1.rut.value = rut_val.substring(0,rut.length);	
	document.form1.dv.value = rut_val.substring(rut.length,rut.length+1);
	//document.form1.rut_aux.value = "";
	if(CheckFields(document.form1.clave.value))
	{document.form1.submit();}
	else
	{return;}
}
function nuevoAjax(){
    var xmlhttp=false;
	try{
	    xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e){
	    try{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); }
	return xmlhttp;
}
function cargaValidacion(){
    var validacion = document.forms.form1.validacion.value;
    if(validacion != ''){
        ajax=nuevoAjax();
		ajax.open("GET", "validar.php?texto="+validacion, true);
	}
    ajax.onreadystatechange=function(){
    if (ajax.readyState==1)
		document.forms.form1.validado.value = "";
	if (ajax.readyState==4)
		document.forms.form1.validado.value = ajax.responseText;
	}
	ajax.send(null);
}
		
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NPZHTP5RFE"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NPZHTP5RFE');
</script>

</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" text="#003399" link="#6666CC" vlink="#3333FF" alink="#6666CC">
<blockquote>
      <form name="form1" action="../php/sugerencias.php" method="GET" onSubmit="return validate(this)">
	  <input type="hidden" name="validado" id="validado">
      <br>
      <div align="left">
          <table width="450" border="0" bgcolor="#535353" cellpadding="0"     cellspacing="0" >
            <tr> 
              <td colspan="2" height="15" bgcolor="#003399"><img src="../images/motivo1.gif" width="18" height="28"><img
          src="../images/t_sugiere.gif" width="292" height="28"></td>
        </tr>
        <tr valign="top">
              <td rowspan="16" width="151" align="left" bgcolor="#003366"> 
                <div align="left"><img src="../images/foto_suger.gif" width="150" height="113"></div></td>
              <td height="30" width="255" bgcolor="#6666CC"></td>
        </tr>
        <tr>
              
        <td width="255" height="14" bgcolor="#CCCCFF"><font size="2"><small><font face="Arial" color="#003399"><font face="Arial, Helvetica, sans-serif" size="2"><b>Nombre Completo
          Remitente :</b></font><font
          face="Arial, Helvetica, sans-serif"> </font></font></small></font></td>
        </tr>
        <tr>
              <td width="255" height="16" bgcolor="#6666CC"> <font face="Arial" size="2"><input name="nombre" size="40" maxlength="200">
                 <br><br>
                </font></td>
        </tr>
		 <tr>
              
        <td width="255" height="14" bgcolor="#CCCCFF"><font size="2"><small><font face="Arial" color="#003399"><font face="Arial, Helvetica, sans-serif" size="2"><b>Rut
          Remitente :</b></font><font
          face="Arial, Helvetica, sans-serif"> </font></font></small></font></td>
        </tr>
        <tr>
              <td width="255" height="16" bgcolor="#6666CC"> <font face="Arial" size="2">
			  <input name="rut_aux" size="14" maxlength="14" onChange="valida();">
			    <input type="hidden" name="rut" size="8" maxlength="8">
                <input name="dv" type="hidden" value="1" size="1" maxlength="1">
                 <br><br>
                </font></td>
        </tr>
        <tr>
              
        <td width="255" bgcolor="#CCCCFF" height="2"><small><font face="Arial" size="2"><b>Email 
          Remitente</b></font><b><font face="Arial" size="2" color="#003399">:</font></b></small></td>
        </tr>
        <tr>
              <td width="255" bgcolor="#6666CC"><font face="Arial" size="2"> 
                <input name="correo" size="40" type="text" maxlength="200">
          <br>
          <br>
                </font></td>
        </tr>
    		
		<tr>
              
        <td width="255" bgcolor="#CCCCFF" height="2"><small><font face="Arial" size="2"><b>Biblioteca Referencia 
          </b></font><b><font face="Arial" size="2" color="#003399">:</font></b></small></td>
        </tr>
        <tr>
              <td width="255" bgcolor="#6666CC"><font face="Arial" size="2"> 
				<select name="biblio_ref" > 
				<?php
				for ($i=0; $i<count($data_biblio); $i++) {
				echo "<option value='".$data_biblio[$i]['campus_tb_campus']."'>".$data_biblio[$i]['nombre_tb_campus']."</option>";
				}
				
				?>
				</select>
				
          <br>
          <br>
                </font></td>
        </tr>
		

		<tr>
              
        <td width="255" bgcolor="#CCCCFF" height="2"><small><font face="Arial" size="2"><b>Carrera Referencia 
          </b></font><b><font face="Arial" size="2" color="#003399">:</font></b></small></td>
        </tr>
        <tr>
              <td width="255" bgcolor="#6666CC"><font face="Arial" size="2"> 
				<select name="carrera_ref" > 
				<?php
				for ($i=0; $i<count($data_carrera); $i++) {
				echo "<option value='".$data_carrera[$i]['carrera_tb_carrera']."'>".$data_carrera[$i]['carrera_tb_carrera'].'-'.$data_carrera[$i]['nombre_tb_carrera']."</option>";
				}
				
				?>
				</select>
				
          <br>
          <br>
                </font></td>
        </tr>		
		
        <tr>
              
        <td width="255" height="16" bgcolor="#CCCCFF"><font face="Arial" size="2" color="#003399"><b> 
          Su Mensaje :</b></font></td>
        </tr>
        <tr>
              <td width="255" height="16" bgcolor="#6666CC"> 
                <textarea name="observaciones" cols="40" rows="12"></textarea>
                 <br><br>
              </td>
        </tr>

		<tr>
			<td bgcolor="#6666CC"><img src="captcha.php" width="100" height="30" vspace="3"></td>
		</tr>
		<tr>
			<td width="255" height="16" bgcolor="#CCCCFF"><font face="Arial" size="2" color="#003399"><b> 
			  Ingresar Texto Imagen:</b></font></td>
			</tr>
        <tr>
			<td width="255" height="16" bgcolor="#6666CC"> 
			<input type="text" name="validacion" id="validacion" maxlength="5" onChange="cargaValidacion();"></input><br><br>
			</td>
        </tr>
        
		<tr>
              <td background="../images/motivo.gif" width="151" align="left" bgcolor="#003366"></td>
              <td width="255" height="2" bgcolor="#6666CC"><small><font face="Arial" size="2"> 
			  
                <input name="Aceptar" type="submit" value="Enviar"> &nbsp;&nbsp;&nbsp;&nbsp; 
                <input name="Limpiar" type="reset"  value="Limpiar">
                </font></small></td>
        </tr>
      </table>
    </form>
</blockquote>    
</body>
</html>
