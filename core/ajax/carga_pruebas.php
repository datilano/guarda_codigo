<?php
session_start();
include "../../conexion.php";
include "../functions.php";
$id_cat = $_POST["id_cat"];
$id_dep = getValue($conexion, "categorias", "id_deporte_cat", "id_cat='$id_cat'");

$auxPru = "<table class='generic rounded'>
				<tr>";
$qryPru = "SELECT prueba FROM pruebas WHERE id_dep='$id_dep'";
$resPru = mysql_query($qryPru);
while($rowPru = mysql_fetch_array($resPru)){
	$auxPru .= "<th>" . utf8_encode($rowPru["prueba"]) . "</th>";
}

$auxPru .= "</tr><tr>";
$qryPru = "SELECT * FROM pruebas WHERE id_dep='$id_dep'";
$resPru = mysql_query($qryPru);
while($rowPru = mysql_fetch_array($resPru)){
	$aplica = getValue($conexion, "pruebas_categorias", "aplica", "id_cat='$id_cat' AND id_pru='" . $rowPru["id_pru"] . "'");
	$checked = "";
	if($aplica) $checked = "checked";
	$auxPru .= "<td><center><input type='checkbox' name='pruebas[" . $rowPru["id_pru"] . "]' $checked></center></td>";
}

$auxPru .= "</tr></table>";

echo $auxPru;
?>