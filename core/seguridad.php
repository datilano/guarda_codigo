<?php
//Reiniciamos la session
session_start();

//Validamos si existe realmente una sesi�n activa o no
if($_SESSION["aut_ini"] != true){
	//Si no hay sesi�n activa, lo direccionamos al index.php (inicio de sesi�n)
	header("Location: ../login.php?error=2");
	exit();
}
?>