<script>			
	$(document).ready(function(){
		$('#fondo-negro').show();
		location.href='index.php';
	});
</script>
<?php
$id_reg = $_GET["id_reg"];
$qryDel = "DELETE FROM $nombre_tabla WHERE $pk_tabla = '$id_reg'";
$resDel = mysql_query($qryDel);
if(!$resDel){
	echo "<script>alert('No se pudo eliminar registro. Error: " . mysql_error() . "');</script>";
}
?>