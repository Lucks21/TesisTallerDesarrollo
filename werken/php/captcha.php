<?php
require "../include/session.php";
session_init();
function randomText($length) {
	$key='';
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    for($i=0;$i<$length;$i++) {
      $key .= $pattern{rand(0,35)};
    }
    return $key;
}

$_SESSION['codigo_captcha'] = randomText(5);
$captcha = imagecreatefromgif("bgcaptcha.gif");
$colText = imagecolorallocate($captcha, 0, 0, 0);
imagestring($captcha, 5, 16, 7, $_SESSION['codigo_captcha'], $colText);

header("Content-type: image/gif");
imagegif($captcha);
//Clean-up memory
ImageDestroy($captcha);
?>