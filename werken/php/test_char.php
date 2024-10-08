<?php
$a = explode('&', $_SERVER['QUERY_STRING']);
$i = 0;

while ($i < count($a)) {

   $b = preg_split('/=/', $a[$i]);

   $st0  =  '(raw)='. $b[1]. " || ";
   $st0 .=  '(urldecode)='. urldecode($b[1]). " || ";
   $st0 .=  '(htmlentities)='. htmlentities($b[1]). " || ";
   $st0 .=  '(htmlentities_decode)='. html_entity_decode($b[1]). " || ";
   $st0 .=  'urldecode(htmlentities_decode)='. urldecode(html_entity_decode($b[1])). " || ";
   $st0 .=  'my_urldecode='. my_urldecode($b[1]). " || ";

	$char = "F1";
    $ascii = base_convert($char, 16,10);
	$char = chr($ascii);
	

   error_log($st0. $ascii);

   $i++;
}
print time();

function my_urldecode($string){

  $array = preg_split ("/%/",$string);

  if (is_array($array)){
   while (list ($k,$v) = each ($array)){
       $ascii = base_convert ($v,16,10);
       $ret .= chr ($ascii);
   }
 }
 return ("$ret");
}

?> 
