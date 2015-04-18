<style type="text/css">
	input[type=checkbox] {
		display: block;
	}
</style>
<?php
if(isset($_POST["guardaPruebas"])){
	$id_cat	= $_POST["id_cat"];
	
	//Poner que no aplica las pruebas de la categoria seleccionada
	$qryDesaplica = "UPDATE pruebas_categorias SET aplica = 0 WHERE id_cat='$id_cat'";
	mysql_query($qryDesaplica);
	
	$arrPru = $_POST["pruebas"];
	if(sizeof($arrPru)>0){
		foreach($arrPru as $key => $value) {
			$id_pru = $key;
			$id_pru_cat = getValue($conexion, "pruebas_categorias", "id_pru_cat", "id_cat='$id_cat' AND id_pru='$id_pru'");
			if($id_pru_cat==""){
				$qry = "INSERT INTO pruebas_categorias(id_pru, id_cat) VALUES($id_pru, $id_cat)";
			}else{
				$qry = "UPDATE pruebas_categorias SET aplica = 1 WHERE id_pru_cat='$id_pru_cat'";
			}
			$res = mysql_query($qry);
		}
	}
	echo "<scipt>alert('Registro guardado correctamente');</script>";
}
?>
<form method="post">
	<?php
	DibujaComboBox("deportes","id_dep","nombre_dep",1,0,"id_tipo_dep","1","id_dep","Deporte","","Selecciona un deporte..",$id_dep,$conexion,1,0);
	DibujaComboBox("","","",0,1,"","1","id_cat","Categoria","","Selecciona una categoria..",$id_cat,$conexion,1,0,$arrCat);

	?> 
	<div id="listaInscritos"></div>
	<div class="buttons">
	<input class="bluebutton" name="guardaPruebas" type="submit" value="Guardar" />
	</div>
	</form>
</body>
</html>

<script>
	$( document ).ready(function() {
		$("#id_dep").change(function(){
			poneCategoria();
		});


		$("#id_cat").change(function(){
			ponePruebas();
		});

		/*$( "#add_equip" ).click(function() {
			if($("#id_cat").val()=="") {
				alert("Debes seleccionar una categoría");
				return false;
			}

			var name_equip = prompt("Escribe nombre del equipo","");
			if (name_equip!="" && name_equip!=null) {
				$.ajax({
					type:"POST",
					url:"ajax/add_equip.php",
					data: { name_equip: name_equip, id_cat: $("#id_cat").val() },
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
			return false;
		});

		$("#alumnos").bind("dblclick", function(){
			add1(false);
		});
		$("#alumnos_insc").bind("dblclick", function(){
			add2(false);
		});*/

	});

	function poneCategoria(valCat, valEquip) {
		$.ajax({
			type:"POST",
			url:"ajax/carga_cat.php",
			data: { id_dep: $("#id_dep").val() },
			beforeSend : function() {
				$("#fondo-negro").show();
			}, 
			complete: function(html) {
				$("#fondo-negro").hide();
				var res = html.responseText;
				var arrRet = JSON.parse(res);
				$("#id_cat").html(arrRet["listaCategorias"]);
				$("#id_cat").val(valCat);
				ponePruebas();
				$("#divEquipos").hide();
			}
		});
	}

	function ponePruebas(){
		$.ajax({
				type:"POST",
				url:"ajax/carga_pruebas.php",
				data: { id_cat: $("#id_cat").val() },
				beforeSend : function() {
					$("#fondo-negro").show();
				}, 
				complete: function(html) {
					$("#fondo-negro").hide();
					var res = html.responseText;
					$("#listaInscritos").html(res);
				}
			});
	}
	
</script>