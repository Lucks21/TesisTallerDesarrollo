<? 

$nombre = $_POST['nombre'];
$rut = $_POST['rut'];
//$dv = $_POST['dv'];
$carrera = $_POST ['carrera'];
$telefono = $_POST['telefono'];
$contacto = $_POST['contacto'];
$mensaje_covid = $_POST['mensaje_covid'];

$para = "Mónica Isabel Erazo Alcayala <merazo@ubiobio.cl>";
$para2 = "Marcela Paz Araya Leüpin <mparaya@ubiobio.cl>";  
$para3 = "Sergio Ernesto Carrasco Pincheira <scarrasc@ubiobio.cl>";
$para4 = $_POST['contacto'];

$asunto= "Contacto en contingencia de '$nombre'"; 

//$header="From: $contacto";
$header= "X-Mailer: PHP/";
$header= "Mime-Version: 1.0 \r\n";
$header= "Content-type: text/text charset=UTF-8 \r\n";

$mensaje= "Se ha recibido un mensaje directo en periodo de contingencia: 

Datos del remitente:

Nombre	 : $nombre
R.U.T.   : $rut
Teléfono : $telefono
email    : $contacto
Carrera o Departamento : $carrera

Mensaje  : $mensaje_covid

";
$mensaje .= "Enviado el " . date('d-m-Y H:i', time()). " utilizando el formulario de contacto en contingencia" ;

$okProceso=mail("$para,$para2,$para3,$para4",$asunto,$mensaje,$header); 

if($okProceso) 
{echo" Email enviado. ";} 
else 
{echo" Error al enviar. ";} 
?>
</body>
</html>