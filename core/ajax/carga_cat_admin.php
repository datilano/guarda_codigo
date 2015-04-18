<?php
include "../../conexion.php";
include "../functions.php";
$id_dep = $_POST["id_dep"];
$id_sex = $_POST["id_sex"];
$tipo_dep = getValue($conexion, "deportes", "id_tipo_dep", "id_dep='$id_dep'");

$qryCat = "SELECT * FROM vwcategorias WHERE id_deporte_cat = '$id_dep'";
if($id_sex!="") $qryCat .= "AND sexo_cat='$id_sex'";
$resCat = mysql_query($qryCat);
$auxCat = "<option value=''>Todas";
$arrSexo = array("H" => "Varonil", "M" => "Femenil");
while($rowCat = mysql_fetch_array($resCat)){
	if($tipo_dep==1){
		$auxCat .= "<option value='" . $rowCat["id_cat"] . "'>" . utf8_encode($arrSexo[$rowCat["sexo_cat"]] . " (" . $rowCat["nombre_cat"] . ")");
	}else{
		$auxCat .= "<option value='" . $rowCat["id_cat"] . "'>" . utf8_encode($arrSexo[$rowCat["sexo_cat"]] . " (" . $rowCat["anios_cat"] . ") " . $rowCat["nombre_cat"]);
	}
}

echo $auxCat;
?>