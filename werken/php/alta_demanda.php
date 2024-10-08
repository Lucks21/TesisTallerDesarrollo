<?php
require "../include/session.php";
session_init();
require "../conf/config.php";
require "../include/database.php";
require "../include/functions.php";

?>
<html>
<head>
<title>Alta Demanda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../conf/estilo.css" rel="stylesheet" type="text/css">

</head>
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
<body bgcolor="#CCCCFF" leftmargin="7" topmargin="7" marginwidth="0" marginheight="0" text="#003366" link="#6666CC" vlink="#3333FF" alink="#6666CC">
<table width="100%" border="0" align="center">
  <tr>
    <td align="center" class="titulo">Libros en Alta Demanda</td>
  </tr>
</table>
<form name="fmr1" method="POST" action="alta_demanda.php" >
  <input type="hidden" name="texto" value>

        <table bgcolor="#535353"  align="center" >
           <tr>
		              <br>
					
       
            <td bgcolor="#6666CC"> 
           
                <select name="biblio" size="1" onchange="	document.forms.fmr1.submit();">
				    <option value="null" selected>Todas Bibliotecas</option>
<?php

	$d= new Database;
	// valida las existencias a renovar 	
	$sql1 = "Select * from biblioteca..tb_campus" ;
	$d->execute($sql1);
	while ($row=$d->get_row())  
	{	//print_r($row);
	
		if($row['campus_tb_campus'] == $_POST['biblio']){
			 echo '<option value="'.$row['campus_tb_campus'].'"  SELECTED>'.$row['nombre_tb_campus'].'</option>';
		}else
		echo '<option value="'.$row['campus_tb_campus'].'">'.$row['nombre_tb_campus'].'</option>';
	  } //while
	     echo " </select>";
?>
 	
        </table>
		
		
 
</form>
<?php

	if (isset($_POST['biblio'])){
	$bi =  $_POST['biblio'];
	$sql1 = "execute sp_web_consulta_altas_demandas $bi " ;
	$d->execute($sql1);
	$numrows = $d->get_numrows();

?>	



<table border="0" width="100%" cellspacing="1" class="texto-tabla-small">
 
    <tr bgcolor="#003399">
      <td width="20%" align="center" valign="middle" >Biblioteca</td>
      <td width="40%" align="center" valign="middle">T&iacute;tulo</td>
      <td width="15%" align="center" valign="middle">Autor</td>
      <td width="15%" align="center" valign="middle">N&uacute;mero de Clasificaci&oacute;n</td>
      <td width="10%" align="center" valign="middle"> Fecha Fin</td>
     </tr>  	
<?php
while ($row=$d->get_row())  
{
//print_r($row);

?> 		<tr> 
    	  <td width="20%" align="center"  bgcolor="#6666CC"><?php echo $row['biblioteca']; ?></td>
    	  <td width="40%" align="left"  bgcolor="#9999FF"><?php echo $row['titulo']; ?></td>
   		  <td width="15%" align="center"  bgcolor="#6666CC"><?php echo $row['autor']; ?></td>
      	  <td width="15%" align="center"  bgcolor="#9999FF"><?php echo $row['clasificacion']; ?></td>
      	  <td width="10%" align="center" bgcolor="#6666CC"><?php echo $row['fecha_termino']; ?></td>
		  
	    </tr>
<?php

  } // fin ciclo while


$d->close();
?>
</table>
<table border="0" align="right">
<tr>
  <td align="center" ><?php echo $numrows; ?> aciertos.</td>
</tr>
</table>
<?php

 }
?>


<p>&nbsp; </p>
</body>
</html>
