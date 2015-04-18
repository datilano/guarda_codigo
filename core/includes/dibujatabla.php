<?php
	$id_tabla = getValue($conexion, "procesos", "id_tabla_proc", "id_proc = '$id_proc'");
	$arrTabla = getValues($conexion, "tablas", "*", "id_tabla = '$id_tabla'");
	$vista_tabla = $arrTabla["vista_tabla"];
	$titulo_tabla = $arrTabla["titulo_tabla"];
	$pk_tabla = $arrTabla["pk_tabla"];
	$order_by_tabla = $arrTabla["order_by_tabla"];
?>
<table class="generic rounded">
<caption><?php echo $titulo_tabla?><div class="add"><?php if(dentroInscripcion($arrConf["fecha_insc_ini"], $arrConf["fecha_insc_fin"])) echo "<a href='".$_SERVER['PHP_SELF'] . "?a=add'><img src='images/add.png' width=32 height=32></a>"; ?></div></caption>
<thead>
	<tr>
		<?php 
		//$arrColumns = getValues($conexion, "columnas_tabla", "*", "id_table = '$id_tabla'");
		$qryColumns = "SELECT * FROM columnas_tabla WHERE id_table = '$id_tabla' AND show_column = 1 ORDER BY order_column";
		$resColumns = mysql_query($qryColumns);
		$i = 0;
		while($rowColumns = mysql_fetch_array($resColumns)){
			$auxColumns .= $coma . $rowColumns["nombre_column"];
			$arrColumns[$i] = $rowColumns["nombre_column"];
			$coma = ",";
			$i++;
			echo "<th width='" . $rowColumns["porc_column"] . "%'>" . $rowColumns["titulo_column"] . "</th>";
		}
		echo "<th></th>";
		//if($id_proc==8) echo "<th></th>";
		?>
	</tr>
</thead>
<tbody>
	<?php
	$where = "";
	switch($id_proc) {
		case 8:
			$where = " WHERE esc_id_alum = " . $_SESSION["esc_id_usr"];
		break;
	}
	if(isset($_GET["searchAlumn"])) {
		$searchAlumn = $_GET["searchAlumn"];
		$qryRows = "SELECT * FROM $vista_tabla WHERE esc_id_alum = " . $_SESSION["esc_id_usr"] . " AND (nombre_completo_alum LIKE '%$searchAlumn%' OR curp_alum LIKE '%$searchAlumn%') ORDER BY $order_by_tabla";
	}else{
		$qryRows = "SELECT * FROM $vista_tabla $where ORDER BY $order_by_tabla";
	}
	$resRows = mysql_query($qryRows);
	$non = true;
	while($rowRows = mysql_fetch_array($resRows)){
		if($non){
			$auxClass = "non";
		}else{
			$auxClass = "par";
		}
		$non = !$non;
		echo "<tr class='$auxClass'>";
		for($i=0; $i<sizeof($arrColumns); $i++){
			/*if(!dentroInscripcion($arrConf["fecha_insc_ini"], $arrConf["fecha_insc_fin"])){
				echo "<td>". $rowRows[$arrColumns[$i]] . "</td>";
			}else{*/
				echo "<td onclick='frmEdit(". $rowRows[$pk_tabla] . ")'>". $rowRows[$arrColumns[$i]] . "</td>";
			//}
		}
		if(dentroInscripcion($arrConf["fecha_insc_ini"], $arrConf["fecha_insc_fin"])){
			echo "<td><a href='javascript:confirmaBorraRegistro(" . $rowRows[$pk_tabla] . ")'><img src='images/delete.png' width='16' height='16' border='0' alt=''></a></td>";
		}else{
			//echo "<td><a href='javascript:alert(&quot;El sistema ha sido bloqueado, ya no puedes hacer movimientos&quot;)'><img src='images/delete.png' width='16' height='16' border='0' alt=''></a></td>";
			echo "<td></td>";
		}
		/*if($id_proc==8 && dentroInscripcion($arrConf["fecha_insc_ini"], $arrConf["fecha_insc_fin"])) {
			echo "<td><a href='?id_mod=2&id_proc=12&id_alumno=" . $rowRows[$pk_tabla] . "'><img src='images/inscripcion.png' width='24' height='24' border='0' alt=''></a></td>";
		}elseif($id_proc==8){
			echo "<td><a href='javascript:alert(&quot;El sistema ha sido bloqueado, ya no puedes hacer movimientos&quot;)'><img src='images/edit_alumno.png' width='24' height='16' border='0' alt=''></a></td>";
		}*/
		echo "</tr>";
	}
	?>
</tbody>
</table>