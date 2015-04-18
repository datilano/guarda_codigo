<?php
include "../conexion.php";
include "functions.php";

$arrTitulo_obj		= $_POST["titulo_obj"];
$arrId_tipo_obj		= $_POST["id_tipo_obj"];
$arrPlaceholder_obj	= $_POST["placeholder_obj"];
$arrRequired_obj	= $_POST["required_obj"];
$arrMax_obj			= $_POST["max_obj"];
$arrMin_obj			= $_POST["min_obj"];
$arrTabla_obj		= $_POST["tabla_obj"];
$arrCampo_obj		= $_POST["campo_obj"];
$arrMostrar_obj		= $_POST["mostrar_obj"];
$arrOrder_obj		= $_POST["order_obj"];

$id_proc = $_POST["id_proc"];
$id_tabla = getValue($conexion, "procesos", "id_tabla_proc", "id_proc = '$id_proc'");
$nombre_tabla = getValue($conexion, "tablas", "nombre_tabla", "id_tabla = '$id_tabla'");

foreach($arrTitulo_obj as $key => $value) {
	$field = $key;
	$id_obj = getValue($conexion, "objetos", "id_obj", "id_tabla_obj = '$id_tabla' AND nombre_obj=$field");

	$titulo_obj			= $arrTitulo_obj[$field];
	$id_tipo_obj		= $arrId_tipo_obj[$field];
	$placeholder_obj	= $arrPlaceholder_obj[$field];
	$required_obj		= $arrRequired_obj[$field];
	$max_obj			= $arrMax_obj[$field];
	$min_obj			= $arrMin_obj[$field];
	$tabla_obj			= $arrTabla_obj[$field];
	$campo_obj			= $arrCampo_obj[$field];
	$mostrar_obj		= $arrMostrar_obj[$field];
	$order_obj			= $arrOrder_obj[$field];

	if(strval($id_obj!="")) {
		//Hacer Update
		$qry = "	UPDATE objetos 
						SET titulo_obj = '$titulo_obj',
							id_tipo_obj = '$id_tipo_obj',
							placeholder_obj = '$placeholder_obj',
							required_obj = '$required_obj',
							max_obj = '$max_obj',
							min_obj = '$min_obj',
							tabla_obj = '$tabla_obj',
							campo_obj = '$campo_obj',
							mostrar_obj = '$mostrar_obj',
							order_obj = '$order_obj'
					WHERE id_obj = '$id_obj'";
	}else{
		//Hacer Insert
		$qry = "INSERT INTO objetos(id_tabla_obj,nombre_obj,titulo_obj,
					id_tipo_obj,placeholder_obj,required_obj,
					max_obj,min_obj,tabla_obj,
					campo_obj,mostrar_obj,order_obj)
				VALUES('$id_tabla', $field, '$titulo_obj',
					'$id_tipo_obj','$placeholder_obj','$required_obj',
					'$max_obj','$min_obj','$tabla_obj',
					'$campo_obj','$mostrar_obj','$order_obj')";
	}
mysql_query($qry);
}
echo "<script>location.href='index.php?id_reg=$id_proc'</script>";
include "../cerrar_conexion.php";
?>