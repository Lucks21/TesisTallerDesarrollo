<?php 

	require ('xss.php');

	$str_nb    = "HOLA</script><script>alert(150)</script>";
    
  	$str_nb    = RemoveXSS($str_nb);
    var_dump($str_nb);
?>
