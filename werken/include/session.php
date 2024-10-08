<?php
include "../conf/config.php";

function session_init() {   
	global $conf;

	preg_match('/\/([0-9a-z]{32})/', $_SERVER["REQUEST_URI"], $regs);
	$sid = $regs[1];
	
	if (!isset ($sid) || empty($sid)) {
		srand((double) microtime()*1000000);
		$sid = md5(uniqid(rand()));	
		$destination = "http://" . $_SERVER["HTTP_HOST"] . "/$sid" . $_SERVER["REQUEST_URI"];
		if ($_SERVER['QUERY_STRING']) $destination .= "?".$_SERVER['QUERY_STRING'];

		session_cache_limiter('private_no_expire');
		session_save_path($conf['web']['session_path']);
		session_id($sid);
		session_start();	

		while (list($clave, $valor) = each($_POST) ) {
			$_SESSION[$clave]  = $valor;
		}
		header("Location: $destination");

	}	 else {
		session_cache_limiter('private_no_expire');
		session_id($sid);
		session_save_path($conf['web']['session_path']);
		session_start();	
	}

}	

function session_init_nocache() {   
	global $conf;

	preg_match('/\/([0-9a-z]{32})/', $_SERVER["REQUEST_URI"], $regs);
	$sid = $regs[1];
	
	if (!isset ($sid) || empty($sid)) {
		srand((double) microtime()*1000000);
		$sid = md5(uniqid(rand()));	
		$destination = "http://" . $_SERVER["HTTP_HOST"] . "/$sid" . $_SERVER["REQUEST_URI"];
		if ($_SERVER['QUERY_STRING']) $destination .= "?".$_SERVER['QUERY_STRING'];

		session_cache_limiter('nocache');
		session_save_path($conf['web']['session_path']);
		session_id($sid);
		session_start();	

		while (list($clave, $valor) = each($_POST) ) {
			$_SESSION[$clave]  = $valor;
		}
		header("Location: $destination");

	}	 else {
		session_cache_limiter('nocache');
		session_id($sid);
		session_save_path($conf['web']['session_path']);
		session_start();	
	}
}		
?>
