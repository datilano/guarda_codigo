<?php
if(isset($_POST["guardaConfig"])) {
	$escuela_int	= $_POST["escuela_int"];
	$fecha_insc_ini	= $_POST["fecha_insc_ini"];
	$fecha_insc_fin	= $_POST["fecha_insc_fin"];

	$qryCat = "UPDATE configuracion SET escuela_int=$escuela_int, fecha_insc_ini='$fecha_insc_ini', fecha_insc_fin='$fecha_insc_fin'";
	$resCat = mysql_query($qryCat);
	echo "<script>alert('Parametros guardados correctamente');</script>";
	include "variables_session.php";
}
?>
<form method="post">
	<?php
	DibujaComboBox("escuelas","id_esc","nombre_esc",0,1,"","1","escuela_int","Escuela","","Selecciona una escuela..",$arrConf["escuela_int"],$conexion,1,0);
	DibujaFecha("fecha_insc_ini","Fecha Inscripcion Inicia","",1,"",$arrConf["fecha_insc_ini"],0);
	DibujaFecha("fecha_insc_fin","Fecha Inscripcion Final","",1,"",$arrConf["fecha_insc_fin"],1);
	?>
	<div class="buttons">
	<input class="bluebutton" name="guardaConfig" type="submit" value="Guardar" />
	</div>
</form>
</body>
</html>