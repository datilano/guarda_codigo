<style>
.input {
	overflow: inherit;
}
input[type=checkbox] {
	display: block;
}
tr.non:hover{ cursor:auto;}
tr.par:hover{ cursor:auto;}
</style>

<form method="post" action="guardaColumnas.php">
	<?php
	$id_tabla = $_GET["id_reg"];
	DibujaComboBox("tablas","id_tabla","vista_tabla","",1,"","","id_tabla","Vista","","Selecciona una vista..",$id_tabla,$conexion,0,0);
	?>
	<table class="generic rounded">
	<thead>
		<tr>
			<th></th>
			<th>Nombre</th>
			<th>Titulo</th>
			<th>Tam(%)</th>
			<th>Tipo</th>
			<th>Orden</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$arrTabla = getValues($conexion, "tablas", "*", "id_tabla = '$id_tabla'");
		$vista_tabla = $arrTabla["vista_tabla"];
		$pk_tabla = $arrTabla["pk_tabla"];

		if($vista_tabla != "") {
			$qryRows = "SHOW COLUMNS FROM $vista_tabla";
			$resRows = mysql_query($qryRows);
			$non = true;
			while($rowRows = mysql_fetch_array($resRows)){
				$arrColumnas = getValues($conexion, "columnas_tabla", "*", "id_table = '$id_tabla' AND nombre_column = '" .$rowRows["Field"] . "'");
				if($non){
					$auxClass = "non";
				}else{
					$auxClass = "par";
				}
				$non = !$non;
				echo "<tr class='$auxClass'>";
				$checked = "";
				if($arrColumnas["show_column"]) { $checked = "checked"; }
				echo "<td><input type='checkbox' name='muestraCampo[" . $rowRows["Field"] . "]' $checked></td>";
				echo "<td>" . $rowRows["Field"] . "</td>";
				echo "<td><input type='text' name='titulo[" . $rowRows["Field"] . "]' value='" . $arrColumnas["titulo_column"] . "'></td>";
				echo "<td><input type='number' name='porcentaje[" . $rowRows["Field"] . "]' min=0 max=100 value='" . $arrColumnas["porc_column"] . "'></td>";
				echo "<td><select name='tipo[" . $rowRows["Field"] . "]'>
					<option value='' selected>";
					$qryTipo = "SELECT * FROM tipo_columnas";
					$resTipo = mysql_query($qryTipo);
					while($rowTipo = mysql_fetch_array($resTipo)) {
						$selected = "";
						if($arrColumnas["id_tipo_column"] == $rowTipo["id_tipo_column"] ) { $selected = "selected"; }
						echo "<option value=" . $rowTipo["id_tipo_column"] . " $selected>" . $rowTipo["tipo_column"];
					}
				echo "</select></td>";
				echo "<td><input type='number' name='orden[" . $rowRows["Field"] . "]' min=0 max=20 value='" . $arrColumnas["order_column"] . "'></td>";
				
				echo "</tr>";
			}
		}
		?>
	</tbody>
	</table>
	<div class="buttons">
		<input class="bluebutton" type="submit" value="Guardar" />
	</div>
</form>

<script>
	$( document ).ready(function() {
		$("#id_tabla").change(function(){
			location.href = location.pathname + "?id_proc=5&id_reg=" + $(this).val();
		});
	});
</script>