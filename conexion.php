<?php
//header('Content-Type: text/html; charset=iso-8859-1');
$dbhost = "localhost";
$dbusuario = "datilano_core";
$dbpassword = "do%0+r)M1;8w";
$db = "datilano_copa_ev_alp";
$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword); 
mysql_select_db($db, $conexion);
if (!$conexion){
	die('Could not connect: ' . mysql_error());
}
?>