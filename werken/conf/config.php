<?php

/*
 * Datos de configuración de acceso a la base de datos
 * en MS SQLServer debe existir la cuenta (tipo: "SQL Standard")
 * con los privilegios necesarios para acceder a la base.
 *
 */
$conf['server']['base_path'] = "/var/www/werken";
$conf['server']['host'] = "sistemasnodo3.dci.ubiobio.cl,10433";
$conf['server']['user'] = "user";
$conf['server']['pswd'] = "password";
$conf['server']['default_db'] = "biblioteca";
$conf['server']['conexion_persistente'] = true;


$conf['web']['session_path'] = "/tmp";

// Parametros varios que afectan la presentacion en el browser 
$conf['web']['max_records'] = 100;
$conf['web']['reg_X_pagina_busqueda'] = 25;
$conf['web']['reg_X_pagina_detalle'] = 15;
$conf['web']['pag_ayuda'] = "ayuda/avanzada.html";
$conf['web']['NAV_index'] = true;

$conf['web']['hilight'] = true;
$conf['web']['showtime'] = false;

// activa los botones de validacion de codigo
// HTML/CSS para revisar en "http://validator.w3.org"
$conf['web']['html_validator'] = false;

$conf['web']['itemlist'] = true;
$conf['web']['itemlist_type_o'] = "<ol>";
$conf['web']['itemlist_type_c'] = "</ol>";

// Define el titulo de la pagina de resultados 
$conf['web']['autor'] = "Autores";
$conf['web']['materia'] = "Materias";
$conf['web']['titulo'] = "Títulos";
$conf['web']['editorial'] = "Editoriales";
$conf['web']['serie'] = "Series";

// los campus se pueden excluir del listado de reserva
// separando los elementos de la variable 'excluye_campus' por ":"
// ejm  "Laurencia:Los Angeles:etc1:etc2", pueden ser "n" campus
$conf['web']['excluye_campus'] = "Laurencia";

// Define el indice para la busqueda (WERKEN)
$conf['db']['autor'] = "1";
$conf['db']['materia'] = "2";
$conf['db']['titulo'] = "3";
$conf['db']['editorial'] = "4";
$conf['db']['serie'] = "5";
$conf['db']['dewey'] = "6";

$conf['criteriosBusqueda'] = array (
    'autor' => 1,
    'materia' => 2,
    'titulo' => 3,
    'editorial' => 4,
    'serie' => 5,
    'dewey' => 6
);

/*
 * define las constantes para los indices de recuperacion de datos desde
 * la session
 */
define("nombre_busqueda", 0);
define("nro_control", 1);
define("tipo", 2);
define("autor", 3);
define("publicacion", 4);
define("dewey", 5);
define("titulo", 5);
define("mes", 6);
define("ano", 7);
?>
