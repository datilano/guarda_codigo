<?php
include "../conexion.php";
include "functions.php";

$qryDep = "SELECT * FROM alumnos WHERE id_alum IN (SELECT id_alum FROM participaciones) OR id_alum IN (SELECT id_alum FROM inscripciones)";
$resDep = mysql_query($qryDep);
$auxDep .= "<option value=''>Todos";
while($rowDep = mysql_fetch_array($resDep)){

	$auxSrc = getValue($conexion, "vwfotos", "file_pic", "id_pic='".$rowDep["id_foto_alum"]."'");
if(!file_exists($auxSrc)) echo "<br>".$auxSrc;	
	//echo "<img id='blah' src='$auxSrc' alt='your image' width='113px' height='160px' style='border-width: 2px; border-style: dashed; border-color: gray;'/>";
}

?>