<?php

//    error_reporting(E_ALL);
//    ini_set("error_reporting", E_ALL);
//    ini_set("display_errors", "1");
//    ini_set("display_startup_errors", "1");

require "../include/session.php";
session_init();

require_once "../conf/config.php";
require_once "../include/database.php";
require_once "../include/functions.php";
require_once "../include/Pager.php";

/*
 * Inicializa variables
 * w_params_hash se utiliza para determinar si hay cambios en los parámetros
 * claves de busqueda, si permanecen igual entonces se recupera la data almacenada
 * en la sesión, de lo contrario se galtilla una nueva consulta a la BD.
 */
if (!isset($_SESSION['w_params_hash'])) {
    $_SESSION['w_params_hash'] = '';
}
preg_match('/.*=(.*)/', SID, $sid_tmp);

/*
 * recupera los parametros desde el web, utiliza la funcion 
 * param(variable, default_value);
 */
$w_textoBuscado = param("texto", "");
$w_ComienzaCon = param("ComienzaCon", 0);
$w_tipo_busqueda = param("TipoBusqueda", "titulo");
$w_params_hash = sha1($w_textoBuscado . $w_ComienzaCon . $w_tipo_busqueda);

$w_CurrentPage = param("CurrentPage", 1);
$w_ResultsPerPage = param("ResultsPerPage", 20);
$w_LinksPerPage = param("LinksPerPage", 5);

$w_debug = param("debug", 0);

$paging = new PagedResults();
$paging->ResultsPerPage = $w_ResultsPerPage;
$paging->LinksPerPage = $w_LinksPerPage;
$paging->TotalResults = 0;

$InfoArray = $paging->InfoArray();

$PagedData = array();
$VeaseData = array();
/*
 * valida si la consulta ya habia sido almacenada previamente en alguna session,
 * de lo contrario se conecta a la BD para extraer los resultados.
 * Para utilizar la informacion almacenada en una session, los datos de consulta
 * (texto, tipo_busqueda) recibidos por el web deben ser identicos a los almacenados en
 * la session, de lo contrario significa que el usuario esta buscando otra cosa y el contenido
 * de la session debe ser refrescada.  
 */
if ($w_textoBuscado != "") {
    if ($_SESSION['w_params_hash'] != $w_params_hash) {
        // es una consulta con nuevos criterios de búsqueda y se debe
        // recuperar la información de la base de datos.
        $data_busqueda = busqueda_x_Tipo($w_textoBuscado, $w_ComienzaCon, $w_tipo_busqueda, $conf['criteriosBusqueda']);
        if ($w_tipo_busqueda != 'titulo') {
            $VeaseData = busqueda_Vease_x_Tipo($w_textoBuscado, $w_ComienzaCon, $w_tipo_busqueda, $conf['criteriosBusqueda']);
        }
        $paging->TotalResults = count($data_busqueda);
        $InfoArray = $paging->InfoArray();
        $_SESSION["TotalResults"] = $paging->TotalResults;
        $_SESSION['w_params_hash'] = $w_params_hash;
        $_SESSION["Data"] = $data_busqueda;
        $_SESSION['VeaseAdemas'] = $VeaseData;
        $PagedData = getPagedData($data_busqueda, $InfoArray['START_OFFSET'], $InfoArray['END_OFFSET']);
        $src = '...datos extraidos desde la DB';
    } else {
        // se recicla la información almacenada en la sesión puesto que no
        // han cambiado los criterios de busqueda (sólo se está paginando 
        // entre resultados.
        $paging->TotalResults = $_SESSION["TotalResults"];
        $paging->setCurrentPage($w_CurrentPage);
        $InfoArray = $paging->InfoArray();
        $PagedData = getPagedData($_SESSION["Data"], $InfoArray['START_OFFSET'], $InfoArray['END_OFFSET']);
        if ($w_tipo_busqueda != 'titulo') {
            $VeaseData = $_SESSION['VeaseAdemas'];
        }
        $src = '...datos extraidos desde la SESSION';
    }
} else {

    $src = "Se busca un string vacio!!!";
}

/**
 * Prepara la información para convertirla a JSON y emitir la salida
 */
$toJSON = array(
    "SID" => $sid_tmp[1],
    "TipoBusqueda" => $w_tipo_busqueda,
    "TextoBuscado" => $w_textoBuscado,
    "ComienzaCon" => $w_ComienzaCon,
    "TotalResults" => $InfoArray['TOTAL_RESULTS'],
    "PrevPage" => $InfoArray['PREV_PAGE'],
    "CurrentPage" => $InfoArray['CURRENT_PAGE'],
    "NextPage" => $InfoArray['NEXT_PAGE'],
    "TotalPages" => $InfoArray['TOTAL_PAGES'],
    "StartOffset" => $InfoArray['START_OFFSET'],
    "EndOffset" => $InfoArray['END_OFFSET'],
    "PageNumbers" => $InfoArray['PAGE_NUMBERS'],
    "ExtractedFrom" => $src,
    "PagedResults" => $PagedData,
    "VeaseAdemas" => $VeaseData
);



if ($w_debug) {
    header('X-Powered-By: Werken/0.1b');
    header('Content-Type: text/html; charset=utf-8;');
    echo '<pre>';
    print_r($toJSON);
    echo '</pre>';
} else {
    header('X-Powered-By: Werken/0.1b');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-Type: application/json; charset=utf-8;');
    echo json_encode($toJSON);
}
?>