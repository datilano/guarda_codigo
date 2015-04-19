<?php
//header('Content-Type: text/html; charset=iso-8859-1');
$dbhost = "localhost";
$dbusuario = "root";
$dbpassword = "123";
$db = "datilano_guarda_codigo";
$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword); 
mysql_select_db($db, $conexion);
if (!$conexion){
	die('Could not connect: ' . mysql_error());
}
?>