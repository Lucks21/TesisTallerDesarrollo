<? 

$nombre = $_POST['nombre'];
$rut = $_POST['rut'];
$carrera = $_POST ['carrera'];
$telefono = $_POST['telefono'];
$contacto = $_POST['contacto'];
$autor = $_POST['autor'];
$titulo = $_POST['titulo'];
$pedido = $_POST['pedido'];

$universidad = $_POST['universidad'];
$biblioteca = $_POST['biblioteca'];
$para = "Carol De La Jara Rice <cjara@ubiobio.cl>";
$para2 = "Marcela Paz Araya Leüpin <mparaya@ubiobio.cl>";  
$para3 = $_POST['contacto'];

$asunto="P.I.B. Alumno '$nombre'"; 

//$header="From: $contacto";
$header="X-Mailer: PHP/";
$header="Mime-Version: 1.0 \r\n";
$header="Content-type: text/text charset=iso-8859-1 \r\n";

$mensaje="Se ha solicitado el siguiente préstamo interbibliotecario en su biblioteca: 

Datos del Material Bibliográfico Solicitado:

Título	        : $titulo
Autor(es)       : $autor
Nº Pedido       : $pedido
Universidad	: $universidad
Perteneciente a	: $biblioteca

Datos del Alumno

Nombre	 : $nombre
Rut	 : $rut
Carrera	 : $carrera
Teléfono : $telefono
e-mail   : $contacto

";

$mensaje .= "Enviado el " . date('d/m/Y', time());

$okProceso=mail("$para,$para2,$para3",$asunto,$mensaje,$header); 

if($okProceso) 
{echo" Email enviado. ";} 
else 
{echo" Error al enviar. ";} 
?>
</body>
</html>
