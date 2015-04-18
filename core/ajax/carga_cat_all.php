<?php
include "../../conexion.php";
$id_dep = $_POST["id_dep"];
$id_sex = $_POST["id_sex"];

$qryCat = "SELECT * FROM categorias WHERE id_deporte_cat = '$id_dep' AND sexo_cat='$id_sex'";
$resCat = mysql_query($qryCat);

$auxCat = "<option value=''>Todas";
while($rowCat = mysql_fetch_array($resCat)){
	$auxCat .= "<option value='" . $rowCat["id_cat"] . "'>" . $rowCat["nombre_cat"] . " (" . $rowCat["anio_max_cat"] . "-" . $rowCat["anio_min_cat"] . ")";
}

echo $auxCat;
?>