<?php
include "../conexion.php";
include "functions.php";
$arrCampos = $_POST["muestraCampo"];
$arrTitulo = $_POST["titulo"];
$arrPorcentaje = $_POST["porcentaje"];
$arrTipo = $_POST["tipo"];
$arrOrden = $_POST["orden"];

$id_tabla = $_POST["id_tabla"];

$qryUpd = "UPDATE columnas_tabla SET show_column=0 WHERE id_table='$id_tabla'";
mysql_query($qryUpd);

foreach($arrCampos as $key => $value){
	$titulo = $arrTitulo[$key];
	$porcentaje = $arrPorcentaje[$key];
	$tipo = $arrTipo[$key];
	$orden = $arrOrden[$key];

	$id_column = getValue($conexion, "columnas_tabla", "id_column", "id_table = '" . $id_tabla . "' AND nombre_column = '" . $key . "'");
	
	if($id_column == ""){
		$qry = "INSERT INTO columnas_tabla VALUES(null, '$id_tabla', '$key', '$titulo', '$porcentaje', '$tipo', '$orden', 1)";
	}else{
		$qry = "UPDATE columnas_tabla SET titulo_column='$titulo', porc_column='$porcentaje', id_tipo_column='$tipo', order_column='$orden', show_column=1 WHERE id_table='$id_tabla' AND nombre_column='$key'";
	}
	mysql_query($qry);
}
echo "<script>location.href='index.php?id_reg=$id_tabla'</script>";
include "../cerrar_conexion.php";
?>