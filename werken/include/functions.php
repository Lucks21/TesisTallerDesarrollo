<?php

function param($param, $def_val) {
	require_once "xssProtect/xss.php";

    /*
     * El valor recuperado desde POST tiene prioridad por sobre GET
     * en la eventualidad que la misma variable sea recibida a traves
     * de ambos metodos
     */
    if (isset($_POST["$param"])) {
        $data = $_POST["$param"];
        $data = RemoveXSS($data);
        return $data;
    }

    if (isset($_GET["$param"])) {
        $data = $_GET["$param"];
        $data = RemoveXSS($data);
        return $data;
    }
    return $def_val;
}

function hilight($text, $to_find) {
    /*
     * destaca el patron buscado en el set de resultados de
     * la base de datos. Para modificar el estilo de resaltado
     * se debe modificar la plantilla de estilos, clase "hilight"
     */
	$nohilight =  array("de");
	 
    $mark_ini = "[[[";
    $mark_end = "]]]";
    $tmp_str = "";
    foreach (preg_split('/ /', $to_find) as $token) {
        // verifica los caracteres de acentuacion 
        $tmp_find = check_vocals($token);
	//	echo $tmp_find.'<br>' ;
		if( !in_array( $token ,$nohilight ) ){
			$pattern = array("/(.*)($tmp_find)(.*)/i");
			$replace = array("\\1$mark_ini\\2$mark_end\\3");
			$text = preg_replace($pattern, $replace, $text);
		}
    }
    $hi_ini = '<font class="hilight">';
    $hi_end = '</font>';
    $pattern = array("/\[\[\[/", '/\]\]\]/');
    $replace = array("$hi_ini", "$hi_end");
    return preg_replace($pattern, $replace, $text);
}

function check_vocals($str) {
    /*
     * busca una vocal para reemplazarlo por la clase "[]" de caracteres acentuados
     * equivalentes a la vocal analizada.
     */
    $tmp_str = "";
    for ($i = 0; $i < strlen($str); $i++) {
        $char = substr($str, $i, 1);
        if (preg_match('/[aeiounñáéíóú]/', $char)) {
            if (preg_match('/[aAáàÁÀäâ]/', $char)) {
                $tmp_str .= "[aAáàÁÀäâ]";
            }
            if (preg_match('/[eEéèÉÈëê]/', $char)) {
                $tmp_str .= "[eEéèÉÈëê]";
            }
            if (preg_match('/[iIíìÍÌïî]/', $char)) {
                $tmp_str .= "[iIíìÍÌïî]";
            }
            if (preg_match('/[oOóòÓÒöô]/', $char)) {
                $tmp_str .= "[oOóòÓÒöô]";
            }
            if (preg_match('/[uUúÚùÙüû]/', $char)) {
                $tmp_str .= "[uUúÚùÙüû]";
            }
            if (preg_match('/[nNñÑ]/', $char)) {
                $tmp_str .= "[nNñÑ]";
            }
        } else {
            $tmp_str .= $char;
        }
    }
    return $tmp_str;
}

function gen_NAV_index($total_rows, $npag, $detalle_busq) {
    /*
     * genera el indice [ 1 - 2 - 3 ... ] de paginacion
     * para facilitar la navegacion entre los resultados
     */
    global $conf;
    if (!$conf['web']['NAV_index']) {
        return;
    }

    $max_x_pagina = ($detalle_busq == 1) ? $conf['web']['reg_X_pagina_detalle'] : $conf['web']['reg_X_pagina_busqueda'];
    $max_index = ($total_rows / $max_x_pagina);

    $url = $_SERVER['PHP_SELF'] . "?tipo_busqueda=" . param("tipo_busqueda", "autor") . "&amp;texto=" . param("texto", "");


    if ($max_index < 1) {
        return "";
    }

    if (($total_rows % $max_x_pagina) > 0) {
        $max_index++;
    }
    $str_indice = "[ -";
    for ($i = 1; $i <= $max_index; $i++) {
        if ($i == $npag) {
            $str_indice = $str_indice . "<font class='texto-NAV-index-actual'>$i</font>";
        } else {
            $str_indice = $str_indice . " <a href='$url&amp;pag=$i' onClick='valida_url(this)'>$i</a> ";
        }
        $str_indice = $str_indice . " - ";
    }
    return ($max_index > 1 ) ? "<font class='texto-NAV-index'>" . $str_indice . " ] </font>" : "";
}

function pager($what, $numrows, $pag, $detalle_busq) {
    /*
     * calcula los valores correctos de inicio y termino de los subindices
     * del vector de $_SESSION que contiene los datos a presentar al usuario dependiendo
     * de la pagina que ha seleccionado desde el indice de paginas.
     */
    global $conf;
    $max_x_pagina = ($detalle_busq == 1) ? $conf['web']['reg_X_pagina_detalle'] : $conf['web']['reg_X_pagina_busqueda'];
    if ($what == "ini") {
        return ($max_x_pagina * $pag) - $max_x_pagina;
    }

    if ($what == "max") {
        $max_pag = intval($numrows / $max_x_pagina);
        $resto = $numrows % $max_x_pagina;
        if (($resto) > 0) {
            $max_pag++;
        }

        if ($numrows < $max_x_pagina) { // solo hay una pagina de resultados
            return ($numrows - 1);
        } elseif ($max_pag == $pag) {   //estamos en la ultima pagina 
            if ($resto == 0) {
                return (($max_x_pagina * $pag) - 1);
            } else {
                return (($max_x_pagina * $pag) - $max_x_pagina ) + ($resto - 1);
            }
        } else { //estamos en una pagina intermedia
            return (($max_x_pagina * $pag) - 1);
        }
    }
}

function show_array($var) {
    print("<br>\n");
    echo "<ul>";
    while (list($c, $v) = each($var))
        echo "<li> $c = $v\n";
    echo "</ul>";
    print("<br>\n");
    return "";
}

function show_time($t, $precision=4) {
    echo "<table border=1 class='texto_usmall'>\n";
    echo "<tr><td></td>\n";
    foreach (array_keys($t) as $h) {
        echo "<td>$h</td>";
    }
    echo "</tr>\n";
    foreach (array_keys($t) as $k1) {
        echo "<tr><td>$k1</td>\n";
        foreach (array_keys($t) as $k2) {
            echo "<td>";
            $diff = $t[$k2] - $t[$k1];
            echo abs(round($diff, $precision));
            echo "</td>";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
}

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

function format_rut($rut) {
    $tmp_1 = preg_replace('/[.-]/', "", $rut);
    $tmp_2 = $tmp_1;
    for ($i = 0; (((9 - strlen($tmp_2)) - $i) > 0); $i++) {
        $tmp_1 = "0" . $tmp_1;
    }
    return $tmp_1;
}

function get_secret($tmp_c) {
    return md5(crypt($tmp_c, "La SAL es bUEna pero nUnca TANto..."));
}

if (!function_exists('json_decode')) {

    function json_decode($content, $assoc=false) {
        require_once 'json/JSON.php';
        if ($assoc) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
            $json = new Services_JSON;
        }
        return $json->decode($content);
    }

}

if (!function_exists('json_encode')) {

    function json_encode($content) {
        require_once 'json/JSON.php';
        $json = new Services_JSON;
        return $json->encode($content);
    }

}

/**
 * Busca una cadena de texto por título
 * 
 * @param type $texto
 * @param type $ComienzaCon
 * @param type $tipoBusqueda
 */
function busqueda_x_Tipo($texto, $ComienzaCon, $tipoBusqueda, $criterios) {
    $d = new Database;
    if ($ComienzaCon == FALSE) $texto = "%" . $texto;
    $sqlstring ="execute sp_WEB_busqueda_palabras_claves '$texto','" . $criterios[$tipoBusqueda]. "'";
    $d->execute($sqlstring);
    $data_busqueda = array();
    switch ($tipoBusqueda) {
        case 'titulo':
            while ($row = $d->get_row()) {
                $tmp_array = array(
                    'nombre_busqueda' => utf8_encode($row['nombre_busqueda']),
                    'numero_control' => utf8_encode($row['nro_control']),
                    'tipo' => utf8_encode($row['tipo']),
                    'autor' => utf8_encode($row['autor']),
                    'publicacion' => utf8_encode($row['publicacion']),
                    'dewey' => utf8_encode($row['dewey'])
                );
                array_push($data_busqueda, $tmp_array);
            }
            break;
        case 'materia':
        case 'autor':
        case 'editorial':
        case 'serie':
            while ($row = $d->get_row()) {
                $tmp_array = array(
                    'nombre_busqueda' => utf8_encode(htmlentities($row['nombre_busqueda']))
                );
                array_push($data_busqueda, $tmp_array);
            }
    }
    unset($tmp_array);
    $d->close();
    return $data_busqueda;
}
/**
 * Busca una cadena de texto por título
 * 
 * @param type $texto
 * @param type $ComienzaCon
 * @param type $tipoBusqueda
 */
function busqueda_Vease_x_Tipo($texto, $ComienzaCon, $tipoBusqueda, $criterios) {
    $d = new Database;
    if ($ComienzaCon == FALSE) $texto = "%" . $texto;
    $sqlstring  = "execute sp_WEB_vease '$texto', '" .  $criterios[$tipoBusqueda]. "'";
    $d->execute($sqlstring);
    $data_busqueda = array();
    switch ($tipoBusqueda) {
        case 'titulo':
            // "titulo" no posee busqueda auxiliar "vease"
            break;
        case 'materia':
        case 'autor':
        case 'editorial':
        case 'serie':
            while ($row = $d->get_row()) {
                $tmp_array = array(
                    	'nombre_busqueda' => utf8_encode($row['nombre_busqueda']), 
			'vease' => utf8_encode($row['nombre_vease'])
                );
                array_push($data_busqueda, $tmp_array);
            }
    }
    unset($tmp_array);
    $d->close();
    return $data_busqueda;
}



/**
 * Recupera un segmento de los datos indicado por los offset. Basado en el 
 * sistema de paginación
 * 
 * @param mixed $data Datos de la busqueda
 * @param int $start_offset offset dentro del arreglo $data para desplegar la pagina correcta
 * @param int $end_offset offset dentro del arreglo $data para desplegar la pagina correcta
 */
function getPagedData($data, $start_offset, $end_offset) {
    $tmp_data = array();
    for ($i = $start_offset; $i < $end_offset; $i++) {
        array_push($tmp_data, $data[$i]);
    }
    return $tmp_data;
}
?> 

