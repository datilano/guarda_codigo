<?php
$id_alumno = $_GET["id_alumno"];
$arr_alum = getValues($conexion, "vwalumnos", "*", "id_alum = '$id_alumno'");
$anio_nac_alum = $arr_alum["anio_nac_alum"];
$sexo_alum = $arr_alum["sexo_alum"];
$name_alum = $arr_alum["nombre_completo_alum"];
$esc_id_alum = $arr_alum["esc_id_alum"];
if($esc_id_alum != $_SESSION["esc_id_usr"]) {
	echo "<script>alert('El alumno que deseas consultar no es de tu escuela, intenta de nuevo'); location.href='?id_proc=8';</script>";
	exit();
}

//Guardamos las inscripciones a los deportes en conjuntos ó a las pruebas de los deportes individuales
if(isset($_POST["guardaCat"])) {
	$validar = true;
	
	//Dar de baja a todas las pruebas que pudo haber estado inscrito el alumno
	$qry_insc = "UPDATE participaciones SET participa=0 WHERE id_alum = '$id_alumno'";
	mysql_query($qry_insc);
	
	//Guardarmos las pruebas de los deportes individuales
	$arrPru = $_POST["pruebas"];
	if(sizeof($arrPru)>0){
		foreach($arrPru as $key => $value) {
			$id_pru = $key;

			//Validar que no supere el numero de participantes en esa prueba por escuela
			$id_cat = getValue($conexion, "pruebas_categorias", "id_cat", "id_pru_cat = '$id_pru'");
			$num_max_part_dep = getValue($conexion, "vwcategorias", "num_max_part_dep", "id_cat = '$id_cat'");
			$numPart = getValue($conexion, "vwparticipaciones", "COUNT(id_part)", "esc_id_alum = '$esc_id_alum' AND id_pru_cat = '$id_pru' AND participa=1");
			$chkPart = false;
		
			if($numPart<$num_max_part_dep){
				$chkPart = true;
			}
			
//			if($chkPart){
				$id_part = getValue($conexion, "participaciones", "id_part", "id_alum = '$id_alumno' AND id_pru_cat = '$id_pru'");
				if($id_part==""){
					$qryPart = "INSERT INTO participaciones(id_alum, id_pru_cat, participa) VALUE('$id_alumno', '$id_pru', 1)";
				}else{
					$qryPart = "UPDATE participaciones SET participa=1 WHERE id_part='$id_part'";
				}
				$res = mysql_query($qryPart);
				$auxVal = false;
				if($res) $auxVal = true;
				$validar = ($validar && $auxVal);
//			}
		}
	}

	//Eliminamos todas las incripciones del alumno de deportes en conjunto
	$qry_insc = "UPDATE inscripciones SET confirm=0, id_equip=0 WHERE id_alum = '$id_alumno'";
	mysql_query($qry_insc);

	//Guardamos las categorias a las que participara el alumno de los deportes en conjunto
	$arrCat = $_POST["categ"];
	if(sizeof($arrCat)>0){
		foreach($arrCat as $key => $value) {
			//Validar si se puede meter en algun equipo
			$qryInsc = "SELECT * FROM equipos WHERE id_esc_equip=$esc_id_alum AND id_cat_equip = '$key'";
			$resInsc = mysql_query($qryInsc);
			$chkOpenEquip = true;
			while($rowInsc = mysql_fetch_array($resInsc)){
				$numInsc = getValue($conexion, "inscripciones", "COUNT(id_insc)", "id_equip = '". $rowInsc["id_equip"] ."' AND confirm=1");
				$num_max_part_dep = getValue($conexion, "vwcategorias", "num_max_part_dep", "id_cat = '".$key."'");
				if($numInsc<$num_max_part_dep && $chkOpenEquip==true){
					$equipInsc = $rowInsc["id_equip"];
					$chkOpenEquip = false;
				}
			}
			
			//Crear equipo
			if($chkOpenEquip){
				$numMax = getValue($conexion, "equipos", "MAX(num_equip)", "id_cat_equip = '". $key ."' AND id_esc_equip = '". $esc_id_alum ."'");
				$numMax++;
				$qryEquip = "INSERT INTO equipos(id_cat_equip, id_esc_equip, num_equip) VALUES($key, $esc_id_alum, $numMax)";
				mysql_query($qryEquip);
				$equipInsc = mysql_insert_id();
			}

			$id_insc = getValue($conexion, "inscripciones", "id_insc", "id_alum = '$id_alumno' AND id_cat = '$key'");
			if($id_insc=="") {
				$qry = "INSERT INTO inscripciones(id_alum, id_cat, id_equip) VALUES($id_alumno, $key, $equipInsc)";
			}else{
				$qry = "UPDATE inscripciones SET confirm=1, id_equip=$equipInsc WHERE id_insc='$id_insc'";
			}
			$res = mysql_query($qry);
			$auxVal = false;
			if($res) $auxVal = true;
			$validar = ($validar && $auxVal);
		}	
	}

	if($validar){
		echo "<script>alert('Alumno inscrito correctamente');</script>";
		if($equipInsc==""){
			echo "<script>location.href='index.php?id_proc=13&id_cat=$id_cat'</script>";
		}else{
			echo "<script>location.href='index.php?id_proc=13&id_equip=$equipInsc'</script>";
		}
		
	}
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
  <script>
  $(function() {
    $( "#accordion" ).accordion();
  });
  </script>
  <style>
	/*input[type=checkbox].pruebas {
		display: inline;
		width: 16px;
		height: 16px;
	}

	tr.non:hover {
		background: #ccc;
	}*/
  </style>
</head>
<body>
<form method="post">
	<h3><a href="?id_proc=8&a=edit&id_reg=<?php echo $id_alumno;?>"><?php echo $name_alum;?></a></h3>
	<div id="accordion">
		<h3>Deportes en Equipo</h3>
		<div><p>
		<?php
		//$qryDep = "SELECT DISTINCT id_deporte_cat, nombre_dep, sexo_cat FROM vwcategorias WHERE id_cat NOT IN(24,25,26,27,16,17,18,19,20,21,23,29,8) AND sexo_cat = '$sexo_alum' AND id_tipo_dep=2 AND ((anio_max_cat <= $anio_nac_alum AND anio_min_cat+2 >= $anio_nac_alum) OR (refuerzo_cat>0 AND anio_refuerzo_cat=$anio_nac_alum))";
		$qryDep = "SELECT DISTINCT id_deporte_cat, nombre_dep, sexo_cat FROM vwcategorias WHERE sexo_cat = '$sexo_alum' AND id_tipo_dep=2 AND ((anio_max_cat <= $anio_nac_alum AND anio_min_cat+2 >= $anio_nac_alum) OR (refuerzo_cat>0 AND anio_refuerzo_cat=$anio_nac_alum))";
		$resDep = mysql_query($qryDep);
		while($rowDep = mysql_fetch_array($resDep)) {
			switch($rowDep["sexo_cat"]) {
				case "H":
					$sexo = "Varonil";
					break;
				case "M":
					$sexo = "Femenil";
					break;
			}
			$qryCat = "SELECT * FROM vwcategorias WHERE id_deporte_cat=" . $rowDep["id_deporte_cat"] . " AND sexo_cat = '$sexo_alum' AND id_tipo_dep=2 AND ((anio_max_cat <= $anio_nac_alum AND anio_min_cat+2 >= $anio_nac_alum) OR (refuerzo_cat>0 AND anio_refuerzo_cat=$anio_nac_alum))";
			$resCat = mysql_query($qryCat);
			$auxCompetencias = "";
			while($rowCat = mysql_fetch_array($resCat)) {
				//Validar si ya esta inscrito en un equipo
				$arrInsc = getValues($conexion, "inscripciones", "id_insc, id_equip", "id_alum = '$id_alumno' AND id_cat = '".$rowCat["id_cat"]."' AND confirm=1");
				$id_insc = $arrInsc["id_insc"];
				$id_equip = $arrInsc["id_equip"];
				$checked = "";
				$chkInsc = false;
				if($id_insc){
					$checked = "checked";
					$chkInsc = true;
				}

				//Validar si se puede meter en algun equipo
				if(!$chkInsc){
					$qryInsc = "SELECT * FROM equipos WHERE id_esc_equip=$esc_id_alum AND id_cat_equip = '".$rowCat["id_cat"]."'";
					$resInsc = mysql_query($qryInsc);
					$chkOpenEquip = true;
					while($rowInsc = mysql_fetch_array($resInsc)){
						$numInsc = getValue($conexion, "inscripciones", "COUNT(id_insc)", "id_equip = '". $rowInsc["id_equip"] ."'");
						if($numInsc < $rowCat["num_max_part_dep"]){
							$chkOpenEquip = false;
						}
					}
				}

				//Validar que si se abre un equipo nuevo, no pase el numero de equipos permitidos internos
				$display = true;
				if($chkOpenEquip && $arrConf["escuela_int"] == $esc_id_alum){
					$numEquip = getValue($conexion, "equipos", "COUNT(id_equip)", "id_esc_equip = '".$arrConf["escuela_int"]."' AND id_cat_equip='".$rowCat["id_cat"]."'");
					if($numEquip >= $rowCat["max_equipos_int"]) $display = false;
				}
				
				//Validar que si se abre un equipo nuevo, no pase el numero de equipos permitidos de otras escuelas
				if($chkOpenEquip && $arrConf["escuela_int"] != $esc_id_alum){
					$numEquip = getValue($conexion, "equipos", "COUNT(id_equip)", "id_esc_equip <> '".$arrConf["escuela_int"]."' AND id_cat_equip='".$rowCat["id_cat"]."'");
					$disabled = "";
					if($numEquip >= $rowCat["max_equipos_ext"]) $display = false;
				}				
				
				if($display){ 
					$auxCompetencias .= "<br><input type='checkbox' name='categ[".$rowCat["id_cat"]."]' id='cat_".$rowCat["id_cat"]."' class='dep_".$rowDep["id_deporte_cat"]."' $checked><label for='cat_".$rowCat["id_cat"]."'>".$rowCat["nombre_cat"]." (".$rowCat["anios_cat"].")</label>";
				}
			}
			if ($auxCompetencias != ""){
				$auxCompetencias = $salto."<b>".$rowDep["nombre_dep"]." - $sexo</b>" . $auxCompetencias;
				echo $auxCompetencias;
				$salto = "<br>";
			}
		}
		?>
		</p></div>

		<h3>Deportes Individuales</h3>
		<div><p>
		<?php
		$qryDep = "SELECT DISTINCT id_deporte_cat, nombre_dep, sexo_cat FROM vwcategorias WHERE sexo_cat = '$sexo_alum' AND id_tipo_dep=1 AND ((anio_max_cat <= $anio_nac_alum AND anio_min_cat >= $anio_nac_alum) OR (refuerzo_cat>0 AND anio_refuerzo_cat=$anio_nac_alum))";
		$resDep = mysql_query($qryDep);
		while($rowDep = mysql_fetch_array($resDep)) {
			switch($rowDep["sexo_cat"]) {
				case "H":
					$sexo = "Varonil";
					break;
				case "M":
					$sexo = "Femenil";
					break;
			}
			echo $salto."<b>".$rowDep["nombre_dep"]." - $sexo</b>";
			$qryCat = "SELECT id_cat, nombre_cat, anios_cat FROM vwcategorias WHERE id_deporte_cat=" . $rowDep["id_deporte_cat"] . " AND sexo_cat = '$sexo_alum' AND id_tipo_dep=1 AND ((anio_max_cat <= $anio_nac_alum AND anio_min_cat >= $anio_nac_alum) OR (refuerzo_cat>0 AND anio_refuerzo_cat=$anio_nac_alum))";
			$resCat = mysql_query($qryCat);
			while($rowCat = mysql_fetch_array($resCat)) {
				$qryPru = "SELECT * FROM vwpruebas WHERE id_cat='".$rowCat["id_cat"]."'";
				$resPru = mysql_query($qryPru);
				echo "<br/>".$rowCat["nombre_cat"]."<br/>";
				while($rowPru = mysql_fetch_array($resPru)){
					$participa = getValue($conexion, "participaciones", "participa", "id_alum = '$id_alumno' AND id_pru_cat = '" . $rowPru["id_pru_cat"] . "' AND participa=1");					
					//Prender o apagar la prueba
					$checked = "";
					$display = true;
					if($participa==1){
						$checked = "checked";
					}else{
						//Validar si aun puede participar en la prueba
						$num_max_part_dep = getValue($conexion, "vwcategorias", "num_max_part_dep", "id_cat = '".$rowCat["id_cat"]."'");
						$numPart = getValue($conexion, "vwparticipaciones", "COUNT(id_part)", "esc_id_alum = '$esc_id_alum' AND id_pru_cat = '" . 
											$rowPru["id_pru_cat"] . "' AND participa=1");
						if($numPart>=$num_max_part_dep and $arrConf["escuela_int"] != $esc_id_alum) $display = false;
					}
					if($display) echo "<input type='checkbox' name='pruebas[" . $rowPru["id_pru_cat"] . "]' id='pru_" . $rowPru["id_pru_cat"] . "' $checked>
									<label for='pru_" . $rowPru["id_pru_cat"] . "'>" . $rowPru["prueba"] . "</label>
							";
				}
			}
			$salto = "<br>";
		}
		?>
		</p>
		</div>
	</div> 
	<div class="buttons">
	<input class="bluebutton" type="submit" name="guardaCat" value="Guardar" />
	</div>
</form>
</body>
</html>

<script>
	$( document ).ready(function() {
		$("#id_proc").change(function(){
			location.href = location.pathname + "?id_proc=7&id_reg=" + $(this).val();
		});

		$("input:checkbox").on('click', function() {
			// in the handler, 'this' refers to the box clicked on
			var $box = $(this);
			if ($box.is(":checked")) {
				// the name of the box is retrieved using the .attr() method
				// as it is assumed and expected to be immutable
				var group = "input:checkbox[class='" + $box.attr("class") + "']";
				// the checked state of the group/box on the other hand will change
				// and the current value is retrieved using .prop() method
				$(group).prop("checked", false);
				$box.prop("checked", true);
			} else {
				$box.prop("checked", false);
			}
		});
	});

</script>