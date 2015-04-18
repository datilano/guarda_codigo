<?php
include "conexion.php";
include "core/functions.php";

$username	= $_POST["username"];
//$password	= Encripta($_POST["password"]);
$password	= $_POST["password"];

//Buscar el usuario y que coincida con la contraseña
$qryUsr = "SELECT * FROM users WHERE user_usr = '$username' AND pwd_usr = '$password'";
$resUsr = mysql_query($qryUsr);
if(mysql_num_rows($resUsr)>0){
	$rowUsr = mysql_fetch_array($resUsr);
	//Guardar datos del usuario en variables de session
	session_start();
	$_SESSION["id_usr"]		= $rowUsr["id_usr"];
	$_SESSION["nombre_usr"]	= $rowUsr["nombre_usr"];
	$_SESSION["id_tipo_usr"]= $rowUsr["id_tipo_usr"];
	$_SESSION["correo_usr"]	= $rowUsr["correo_usr"];
	$_SESSION["esc_id_usr"]	= $rowUsr["esc_id_usr"];

	//Variables de session que se requieren para el sistema
	$_SESSION["fld_include"] = "includes";

	//Redireccionar al menu principal y guardar en variable que la sesion este iniciada
	$_SESSION["aut_ini"] = true;
	header( 'Location:core' ) ;
}else{
	//Redireccionar al Login de nuevo
	header( 'Location:login.php?error=1' ) ;
}
include "cerrar_conexion.php";
?>