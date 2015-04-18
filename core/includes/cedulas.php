<a href="reportes/formato_ced.php" target="_blank">Imprimir Formato</a>
<form method="post" action="reportes/reporte_ced.php" target="_blank">
	<?php
	DibujaComboBox("escuelas","id_esc","nombre_esc",0,1,"","","id_esc","Escuela","required","Selecciona una..",$id_dep,$conexion,1,0);
	DibujaComboBox("deportes","id_dep","nombre_dep",0,1,"","","id_dep","Deporte","required","Selecciona uno..",$id_dep,$conexion,1,0);
	$arrSexo = array("H" => "Varonil", "M" => "Femenil");
	DibujaComboBox("","","","",1,"","","sexo","Rama","required","Selecciona una..",$sexo,$conexion,0,0,$arrSexo);
	DibujaComboBox("","","",0,1,"","1","id_cat","Categoria","required","Selecciona una..",$id_cat,$conexion,1,0,$arrCat);
//	DibujaComboBox("","","",0,1,"","1","id_equip","Equipo","","Selecciona un equipo..","",$conexion,1,1);

	?> 
	<div class='input nobottomborder' id='div-equip'>
		<div class='inputtext'>Equipo: </div>
		<div class='inputcontent'>
			<select name='id_equip' id='id_equip' required>
				<option value='' data-placeholder='true'>Selecciona uno..</option>
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
		$("#id_esc").change(function(){
			poneEquipos();
		});

		$("#id_dep").change(function(){
			if($(this).val() == 6 || $(this).val() == 7){
				$("#div_id_cat").hide();
				$("#div_sexo").hide();
				$("#div-equip").hide();

				$('#sexo').prop('required',false);
				$('#id_cat').prop('required',false);
				$('#id_equip').prop('required',false);
			}else{
				$("#div_id_cat").show();
				$("#div_sexo").show();
				$("#div-equip").show();

				$('#sexo').prop('required',true);
				$('#id_cat').prop('required',true);
				$('#id_equip').prop('required',true);

				poneCategoria();
			}
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
				var res = html.responseText;
				$("#id_cat").html(res);
				$("#fondo-negro").hide();
				poneEquipos();
			}
		});
	}

	function poneEquipos(){
		$.ajax({
				type:"POST",
				url:"ajax/carga_equip_ced.php",
				data: { id_cat: $("#id_cat").val(), id_esc: $("#id_esc").val() },
				beforeSend : function() {
					$("#fondo-negro").show();
				}, 
				complete: function(html) {
					var res = html.responseText;
					$("#id_equip").html(res);
					$("#fondo-negro").hide();
					poneAlumnos();
				}
			});
	}
</script>