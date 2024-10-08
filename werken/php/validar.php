<?php
require "../include/session.php";	
session_init();
if ($_SESSION['codigo_captcha'] == $_GET['texto']) {
	echo 1;
} 
else{
	echo 0;
}
?>