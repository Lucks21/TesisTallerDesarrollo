<? 

$nombre = $_POST['nombre'];
$rut = $_POST['rut'];
$carrera = $_POST['carrera'];
$telefono = $_POST['telefono'];
$contacto = $_POST['contacto'];
$autor = $_POST['autor'];
$titulo = $_POST['titulo'];
$editorial = $_POST['editorial'];
$isbn = $_POST['isbn'];
$motivo = $_POST['motivo'];

$para = "mescalon@ubiobio.cl"; // Dirección de Correo Principal
$para2 = "mparaya@ubiobio.cl";
$para3 = $_POST['contacto']; // Dirección de Correo del Contacto

$asunto="A.P.E. Alumno '$nombre'"; 

//$header="From: $contacto";
$header="X-Mailer: PHP/";
$header="Mime-Version: 1.0 \r\n";
$header="Content-type: text/text charset=iso-8859-1 \r\n";

$mensaje="Se ha solicitado el siguiente material bibliográfico acogiéndose al Programa de Atención Preferencial al Estudiante (APE) para su biblioteca: 

Datos del material bibliográfico solicitado:

Título	  : $titulo
Autor(es) : $autor
Editorial : $editorial
ISBN	  : $isbn

Datos del Alumno

Nombre	  : $nombre
Rut	  : $rut
Carrera	  : $carrera
Teléfono  : $telefono
e-mail    : $contacto
Motivo de la Solicitud: $motivo

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
