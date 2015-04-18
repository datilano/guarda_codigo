<?php
session_start();
include "../../conexion.php";
include "../functions.php";
$id_insc = $_POST["id_insc"];
$id_equip = getValue($conexion, "inscripciones", "id_equip", "id_insc='$id_insc'");
$arrEquip = getValues($conexion, "vwequipos", "id_cat_equip, id_esc_equip", "id_equip='$id_equip'");

$qryEquip = "SELECT * FROM vwequipos 
				WHERE (id_cat_equip = '" . $arrEquip["id_cat_equip"] . "' AND 
						id_esc_equip = '" . $arrEquip["id_esc_equip"] . "') AND
						id_equip <> $id_equip";
$resEquip = mysql_query($qryEquip);
$auxEquip = "<option value=''>Seleccione un equipo..";
while($rowEquip = mysql_fetch_array($resEquip)){
	$auxEquip .= "<option value='" . $rowEquip["id_equip"] . " '>" . $rowEquip["nombre_equip"];
}
echo $auxEquip;
?>