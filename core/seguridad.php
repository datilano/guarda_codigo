<?php
//Reiniciamos la session
session_start();

//Validamos si existe realmente una sesión activa o no
if($_SESSION["aut_ini"] != true){
	//Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión)
	header("Location: ../login.php?error=2");
	exit();
}
?>