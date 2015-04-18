<?php
include "../seguridad.php";
include "../../conexion.php";
include "../functions.php";
include "../variables_session.php";

$id_cat = $_POST["id_cat"];
$arr_cat = getValues($conexion, "vwcategorias", "*", "id_cat = '$id_cat'");
$auxAdd = true;
//Validar que si se abre un equipo nuevo, no pase el numero de equipos permitidos internos
if($arrConf["escuela_int"] == $_SESSION["esc_id_usr"]){
	$numEquip = getValue($conexion, "equipos", "COUNT(id_equip)", "id_esc_equip = '".$arrConf["escuela_int"]."' AND id_cat_equip='".$id_cat."'");
	if($numEquip >= $arr_cat["max_equipos_int"]) $auxAdd = false;
}else{
	$numEquip = getValue($conexion, "equipos", "COUNT(id_equip)", "id_esc_equip <> '".$arrConf["escuela_int"]."' AND id_cat_equip='".$id_cat."'");
	if($numEquip >= $arr_cat["max_equipos_ext"]) $auxAdd = false;
	$numEquip = getValue($conexion, "equipos", "COUNT(id_equip)", "id_esc_equip = '".$_SESSION["esc_id_usr"]."' AND id_cat_equip='".$id_cat."'");
	if($numEquip > 0) $auxAdd = false;
}

if($auxAdd){
	$numEquip = getValue($conexion, "equipos", "MAX(num_equip)", "id_cat_equip = '$id_cat' AND id_esc_equip = '".$_SESSION["esc_id_usr"]."'")+1;
	$qry = "INSERT INTO equipos VALUES(null, $id_cat, $_SESSION[esc_id_usr], $numEquip)";
	mysql_query($qry);
	$mens = "Equipo agregado correctamente";
}else{
	$mens = "Ya se alcanzo el numero maximo de equipos permitidos a inscribir";
}

echo $mens;
?>