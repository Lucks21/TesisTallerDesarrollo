<?php 
if ($conf['web']['html_validator']) {
	$url_val= "http://" . $_SERVER['SERVER_NAME'] . $PHP_SELF . "?" . $_SERVER['QUERY_STRING']; 
?>
<!--  HTML VALIDATOR -->
<br>
<blockquote>
<table width="90%" border="0">
<tr><td>
  <div align="right">
		<a href="http://validator.w3.org/check?uri=<?php echo $url_val ?>">
		<img border="0" src="../images/html_validator/valid-html401"	alt="Valid HTML 4.01!" height="31" width="88"></a>
		<a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo $url_val; ?>">
		<img style="border:0;width:88px;height:31px" src="../images/html_validator/vcss" alt="Valid CSS!"></a>
	</div>
</td></tr>	
</table>
</blockquote>
<!-- HTML VALIDATOR -->
<?php 
} //if 
?>