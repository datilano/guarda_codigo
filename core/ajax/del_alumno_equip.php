<?php
session_start();
include "../../conexion.php";
$id_insc = $_POST["id_insc"];

$qry = "DELETE FROM inscripciones WHERE id_insc = '$id_insc'";
mysql_query($qry);
?>