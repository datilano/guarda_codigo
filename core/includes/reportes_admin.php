<form method="post" action="reportes/reporte_admin.php" target="_blank">
	<?php
	DibujaComboBox("reportes","id_rep","nombre_rep",0,1,"","","id_rep","Reporte","required","Selecciona uno..","",$conexion,1,0);
	DibujaComboBox("deportes","id_dep","nombre_dep",0,1,"","","id_dep","Deporte","","Todos","",$conexion,1,0);
	DibujaComboBox("escuelas","id_esc","nombre_esc",0,1,"","","id_esc","Escuela","","Todas","",$conexion,1,0);	
	$arrSexo = array("H" => "Varonil", "M" => "Femenil");
	DibujaComboBox("","","","",1,"","","sexo","Rama","","Todos","",$conexion,0,0,$arrSexo);
	DibujaComboBox("","","",0,1,"","1","id_cat","Categoria","","Todas","",$conexion,1,0,"");
	?> 
	<div class="buttons">
		<input class="bluebutton" name="generaRepAdmin" type="submit" value="Generar" />
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
		
		$("#id_rep").change(function(){
			poneDeportes($(this).val());
		});
	});

	function poneDeportes(id_rep) {
		$.ajax({
			type:"POST",
			url:"ajax/carga_dep_admin.php",
			data: { id_rep: id_rep },
			beforeSend : function() {
				$("#fondo-negro").show();
			}, 
			complete: function(html) {
				$("#fondo-negro").hide();
				var res = html.responseText;
				$("#id_dep").html(res);
				poneCategoria();
			}
		});
	}
	
	function poneCategoria() {
		$.ajax({
			type:"POST",
			url:"ajax/carga_cat_admin.php",
			data: { id_dep: $("#id_dep").val(), id_sex: $("#sexo").val() },
			beforeSend : function() {
				$("#fondo-negro").show();
			}, 
			complete: function(html) {
				$("#fondo-negro").hide();
				var res = html.responseText;
				$("#id_cat").html(res);				
			}
		});
	}
</script>