<?php
include "../../conexion.php";
$nombre_tabla = $_POST["nombre_tabla"];
$qryTbl = "SHOW COLUMNS FROM $nombre_tabla";
$resTbl = mysql_query($qryTbl);
if($resTbl){
	$auxFields = "";
	$coma = "";
	while($rowTbl = mysql_fetch_array($resTbl)){
		$Field = $rowTbl["Field"];
		$Key = $rowTbl["Key"];
		if($Key == "PRI") {
			$pk_tbl = $Field;
		}
		$auxFields .= $coma.$Field;
		$coma = ",";
	}
	echo $pk_tbl."|".$auxFields;
}
?>