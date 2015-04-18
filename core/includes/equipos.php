<?php
if(isset($_GET["id_equip"])) {
	$id_equip = $_GET["id_equip"];
	$id_cat = getValue($conexion, "equipos", "id_cat_equip", "id_equip = '$id_equip'");
	$id_dep = getValue($conexion, "categorias", "id_deporte_cat", "id_cat = '$id_cat'");
	echo "	<script>
				$( document ).ready(function() {
					poneCategoria($id_cat, $id_equip);
				});
			</script>";
}

if(isset($_GET["id_cat"])) {
	$id_cat = $_GET["id_cat"];
	$id_dep = getValue($conexion, "categorias", "id_deporte_cat", "id_cat = '$id_cat'");
	echo "	<script>
				$( document ).ready(function() {
					poneCategoria($id_cat, null);
				});
			</script>";
}

if(isset($_POST["cambiaEquip"])){
	$id_insc	= $_POST["id_insc"];
	$id_equip	= $_POST["id_equip_chng"];
	$id_dep = getValue($conexion, "vwinscripcion_equipos", "id_deporte_cat", "id_insc = '$id_insc'");
	$id_cat = getValue($conexion, "inscripciones", "id_cat", "id_insc = '$id_insc'");
	$max_part = getValue($conexion, "deportes", "num_max_part_dep", "id_dep = '$id_dep'");
	$numInsc = getValue($conexion, "inscripciones", "COUNT(id_insc)", "id_equip = '$id_equip'");

	if($numInsc<$max_part){
		$qryIns = "UPDATE inscripciones SET id_equip = '$id_equip' WHERE id_insc = '$id_insc'";
		mysql_query($qryIns);
		echo "<script>
				alert('Se cambio al alumno al equipo previamente seleccionado, de forma correcta');
				$( document ).ready(function() {
					poneCategoria($id_cat, $id_equip);
				});
			</script>";
	}else{
		echo "<script>alert('El numero maximo de participantes permitidos es de: $max_part jugadores');</script>";
	}
}
?>
<form method="post" action="reportes/reporte_esc.php" target="_blank">
	<?php
	DibujaComboBox("deportes","id_dep","nombre_dep",1,1,"id_tipo_dep","2","id_dep","Deporte","required","Selecciona un deporte..",$id_dep,$conexion,1,0);
	DibujaComboBox("","","",0,1,"","1","id_cat","Categoria","required","Selecciona una categoria..",$id_cat,$conexion,1,0,$arrCat);
//	DibujaComboBox("","","",0,1,"","1","id_equip","Equipo","","Selecciona un equipo..","",$conexion,1,1);

	?> 
	<div class='input nobottomborder' id="divEquipos">
		<div class='inputtext'>Equipo: </div>
		<div class='inputcontent'>
			<select name='id_equip' id='id_equip' required>
				<option value='0' data-placeholder='true'>Todos</option>
				<?php echo $auxEq; ?>
			</select>
			<a href="#" id="add_equip"><img src="images/add.png" width="32" height="32" border="0" alt=""></a>
		</div>
	</div>
	<div id="listaInscritos"></div>
	<!-- <table width="100%">
		<tr>
			<td>ALUMNOS</TD>
			<td></td>
			<td>INSCRITOS</TD>
		</tr>
		<tr>
			<td style="width:45%">
				<select id="alumnos" style="width:100%" multiple size="10" class="login-inp"><?php echo $auxAlum; ?></select>
			</td>
			<td style="width:10%">
				<center><input type="button" onClick="add1(true);" style="width:33px" value =">>"/><br/>
				<input type="button" onClick="add1(false);" style="width:33px" value =">"/><br/>
				<input type="button" onClick="add2(false);" style="width:33px" value ="<"/><br/>
				<input type="button" onClick="add2(true);" style="width:33px" value ="<<"/><br/></center>
			</td>
			<td style="width:45%">
				<select id="alumnos_insc" style="width:100%" multiple size="10" class="login-inp"><?php echo $auxAlum2; ?></select>
			</td>
		</tr>
	</table> -->
	<div class="buttons">
	<input type="hidden" name="listInsc" id="listInsc" value="<?php echo $auxAlum3; ?>">
	<input class="bluebutton" name="guardaInsEquip" type="submit" value="Imprimir" />
	</div>
	</form>
	<div id="frmCambiaEquipo" class="fondo-negro">
		<br/>
		<form method="post" class="frmCambiaEquipo">
			<h3>Cambiar a otro equipo</h3>
			<p>Equipo: <select name='id_equip_chng' id='id_equip_chng' required>
						<option value='' data-placeholder='true'>Seleccione un equipo..</option>
					</select></p>
			<input type="hidden" name="id_insc" id="id_insc">
			<p><input class="bluebutton" name="cambiaEquip" type="submit" value="Guardar" />
			<input onClick="closeCambiaEquip()" class="greybutton" name="cerraCambiaEquip" type="button" value="Cancelar" /></p>
		</form>
	</div>
</body>
</html>

<script>
	$( document ).ready(function() {
		$("#id_dep").change(function(){
			poneCategoria();
		});

		$("#id_equip").change(function(){
			poneAlumnos();
		});

		$("#id_cat").change(function(){
			poneEquipos();
		});

		$( "#add_equip" ).click(function() {
			if($("#id_cat").val()=="") {
				alert("Debes seleccionar una categoría");
				return false;
			}

			if (confirm("Estas seguro que quieres dar de Alta otro equipo?")) {
				$.ajax({
					type:"POST",
					url:"ajax/add_equip.php",
					data: { id_cat: $("#id_cat").val() },
					beforeSend : function() {
						$("#fondo-negro").show();
					}, 
					complete: function(html) {
						var res = html.responseText;
						alert(res);
						$("#fondo-negro").hide();
						poneCategoria($("#id_cat").val());
					}
				});
			}
			return false;
		});

		/*$("#alumnos").bind("dblclick", function(){
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
				if(arrRet["tipo_dep"]==2){
					poneEquipos(valEquip);
					$("#divEquipos").show();
				}else{
					poneAlumnos();
					$("#divEquipos").hide();
				}
			}
		});
	}

	function poneEquipos(valEquip){
		$.ajax({
				type:"POST",
				url:"ajax/carga_equip.php",
				data: { id_cat: $("#id_cat").val() },
				beforeSend : function() {
					$("#fondo-negro").show();
				}, 
				complete: function(html) {
					var res = html.responseText;
					$("#id_equip").html(res);
					$("#id_equip").val(valEquip);
					$("#fondo-negro").hide();
					poneAlumnos();
				}
			});
	}

	function poneAlumnos(){
		$.ajax({
			type:"POST",
			url:"ajax/carga_alumnos.php",
			data: { id_equip: $("#id_equip").val(), id_cat: $("#id_cat").val()},
			beforeSend : function() {
				$("#fondo-negro").show();
			}, 
			complete: function(html) {
				$("#listaInscritos").html(html.responseText)
				$("#fondo-negro").hide();
				//console.log(html.responseText);
				/*var res = JSON.parse(html.responseText);
				$("#alumnos").html(res.alumnos);
				$("#alumnos_insc").html(res.inscritos);
				$("#listInsc").val(res.listInsc);*/
			}
		});
	}

	/*function add1(all){
		obj=Objeto('alumnos');
//		if (obj.selectedIndex==-1){ return;}
		for (i=0; opt=obj.options[i]; i++){
			if (opt.selected || all) {
				valor=opt.value; // almacenar value
				txt=obj.options[i].text; // almacenar el texto
				obj.options[i]=null; // borrar el item si está seleccionado
				obj2=Objeto('alumnos_insc');
				opc = new Option(txt,valor);
				eval(obj2.options[obj2.options.length]=opc);
				opc2 = new Option(txt,valor);
				i--;
			}
		}
		poneAlumnosInsc();
	}

	function add2(all){
		obj=Objeto('alumnos_insc');
//		if (obj.selectedIndex==-1){ return;}
		for (i=0; opt=obj.options[i]; i++){
			if (opt.selected || all) {
				valor=opt.value; // almacenar value
				txt=obj.options[i].text; // almacenar el texto
				obj.options[i]=null; // borrar el item si está seleccionado
				obj2=Objeto('alumnos');
				opc = new Option(txt,valor);
				eval(obj2.options[obj2.options.length]=opc);
				i--;
			}
		}
		poneAlumnosInsc();
	}

	function poneAlumnosInsc(){
		obj=Objeto('alumnos_insc');
		valores="";
		sep="";
		for (i=0; opt=obj.options[i]; i++){
			valores=valores+sep+opt.value;
			sep=",";
		}
		Objeto('listInsc').value=valores;
	}*/
	function confirmaBorraInscripcion(id_insc){
		if(confirm("Estas seguro de sacar al alumno del equipo?")){
			$.ajax({
				type:"POST",
				url:"ajax/del_alumno_equip.php",
				data: { id_insc: id_insc },
				beforeSend : function() {
					$("#fondo-negro").show();
				}, 
				complete: function(html) {
					//$("#listaInscritos").html(html.responseText)
					poneAlumnos();
					$("#fondo-negro").hide();
					//console.log(html.responseText);
					/*var res = JSON.parse(html.responseText);
					$("#alumnos").html(res.alumnos);
					$("#alumnos_insc").html(res.inscritos);
					$("#listInsc").val(res.listInsc);*/
				}
			});
		}
	}
	
	function editarEquipo(id_insc){
		$.ajax({
			type:"POST",
			url:"ajax/carga_equip_chng.php",
			data: { id_insc: id_insc },
			beforeSend : function() {
				$("#frmCambiaEquipo").show();
			}, 
			complete: function(html) {
				$("#id_insc").val(id_insc);
				$("#id_equip_chng").html(html.responseText)
			}
		});
	}
	
	function closeCambiaEquip(){
		$("#frmCambiaEquipo").hide();
	}

	function verInscAlum(idAlum){
		location.href = 'index.php?id_proc=12&id_alumno=' + idAlum;
	}
	
</script>