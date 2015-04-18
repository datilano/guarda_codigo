<?php
include "../../conexion.php";
$id_rep = $_POST["id_rep"];

switch($id_rep){
	case "1":
		$aux_tipo_dep = 2;
		break;
	case "2":
		$aux_tipo_dep = 1;
		break;
	default:
		$aux_tipo_dep = '%';
		break;
}



$qryDep = "SELECT * FROM deportes WHERE id_tipo_dep = '$aux_tipo_dep'";
$resDep = mysql_query($qryDep);
$auxDep .= "<option value=''>Todos";
while($rowDep = mysql_fetch_array($resDep)){
	$auxDep .= "<option value='" . $rowDep["id_dep"] . "'>" . utf8_encode($rowDep["nombre_dep"]);
}

echo $auxDep;
?>