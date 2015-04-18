<?php
session_start();
include "../../conexion.php";
$id_cat = $_POST["id_cat"];

$qryEq = "SELECT * FROM vwequipos WHERE id_cat_equip = '$id_cat' AND id_esc_equip = " . $_SESSION["esc_id_usr"];
$resEq = mysql_query($qryEq);

$auxEq = "<option value=''>Selecciona un equipo..";
while($rowEq = mysql_fetch_array($resEq)){
	$auxEq .= "<option value='" . $rowEq["id_equip"] . "'>" . $rowEq["nombre_equip"];
}

echo $auxEq;
?>