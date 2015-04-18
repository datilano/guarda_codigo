<form method="post" action="reportes/reporte_esc.php" target="_blank">
	<?php
	DibujaComboBox("deportes","id_dep","nombre_dep",0,1,"","","id_dep","Deporte","required","Seleccione una opcion..",$id_dep,$conexion,1,0);
	$arrSexo = array("H" => "Varonil", "M" => "Femenil");
	DibujaComboBox("","","","",1,"","","sexo","Rama","","Todas",$sexo,$conexion,0,0,$arrSexo);
	DibujaComboBox("","","",0,1,"","1","id_cat","Categoria","","Todas",$id_cat,$conexion,1,0,$arrCat);
//	DibujaComboBox("","","",0,1,"","1","id_equip","Equipo","","Selecciona un equipo..","",$conexion,1,1);

	?> 
	<div class='input nobottomborder'>
		<div class='inputtext'>Equipo: </div>
		<div class='inputcontent'>
			<select name='id_equip' id='id_equip'>
				<option value='' data-placeholder='true'>Todos</option>
				<?php echo $auxEq; ?>
			</select>
		</div>
	</div>
	<div class="buttons">
		<input class="bluebutton" name="guardaInsEquip" type="submit" value="Generar" />
	</div>
</form>
</body>
</html>

<script>
	$( document ).ready(function() {
		$("#id_dep").change(function(){
			poneCategoria();
		});

		$("#sexo").change(function(){
			poneCategoria();
		});

		$("#id_cat").change(function(){
			poneEquipos();
		});
	});

	function poneCategoria() {
		$.ajax({
			type:"POST",
			url:"ajax/carga_cat_all.php",
			data: { id_dep: $("#id_dep").val(), id_sex: $("#sexo").val() },
			beforeSend : function() {
				$("#fondo-negro").show();
			}, 
			complete: function(html) {
				$("#fondo-negro").hide();
				var res = html.responseText;
				$("#id_cat").html(res);
				poneEquipos();
			}
		});
	}

	function poneEquipos(){
		$.ajax({
				type:"POST",
				url:"ajax/carga_equip_all.php",
				data: { id_cat: $("#id_cat").val() },
				beforeSend : function() {
					$("#fondo-negro").show();
				}, 
				complete: function(html) {
					$("#fondo-negro").hide();
					var res = html.responseText;
					$("#id_equip").html(res);
				}
			});
	}
</script>