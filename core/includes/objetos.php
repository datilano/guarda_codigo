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
  </style>
</head>
<body>
<form method="post" action="guardaObjetos.php">
	<?php
	$id_proc = $_GET["id_reg"];
	DibujaComboBox("procesos","id_proc","nombre_proc",2,0,"id_tipo_proc","1","id_proc","Proceso","","Selecciona un proceso..",$id_proc,$conexion,1,1);
	$id_tabla = getValue($conexion, "procesos", "id_tabla_proc", "id_proc = '$id_proc'");
	$nombre_tabla = getValue($conexion, "tablas", "nombre_tabla", "id_tabla = '$id_tabla'");
	?> 
	<div id="accordion">
		<?php
		if($nombre_tabla != "") {
			$qryObj = "SHOW COLUMNS FROM $nombre_tabla";
			$resObj = mysql_query($qryObj);
			$i = 1;
			while($rowObj = mysql_fetch_array($resObj)) {
				//Obtener datos de las columnas, de la tabla seleccionada
				$field		= $rowObj["Field"];
				$type		= $rowObj["Type"];
				$null		= $rowObj["Null"];
				$key		= $rowObj["Key"];			
				$max_obj	= filter_var($type, FILTER_SANITIZE_NUMBER_INT);
				$min_obj	= 0;

				if($key!="PRI"){
					echo "<h3>" . $field . "</h3>";
					echo "<div><p>";
					if(strpos($type, "int")!== false){
						if($key=="MUL") {
							$id_tipo_obj = 4;
						}else{
							$id_tipo_obj = 5;
						}
					}elseif(strpos($type, "varchar")!== false){
						$id_tipo_obj = 1;
					}elseif(strpos($type, "tinyint")!== false){
						$id_tipo_obj = 6;
					}elseif(strpos($type, "longtext")!== false){
						$id_tipo_obj = 3;
					}
					if($rowObj["Null"]=="NO") $required_obj = 1;

					//Ver datos guardados en la tabla 'objetos'
					$qryVal = "SELECT * FROM objetos WHERE id_tabla_obj='$id_tabla' AND nombre_obj='$field'";
					$resVal = mysql_query($qryVal);
					if(mysql_num_rows($resVal)>0){
						$rowVal				= mysql_fetch_array($resVal);
						$titulo_obj			= $rowVal["titulo_obj"];
						$id_tipo_obj		= $rowVal["id_tipo_obj"];
						$placeholder_obj	= $rowVal["placeholder_obj"];
						$required_obj		= $rowVal["required_obj"];
						$max_obj			= $rowVal["max_obj"];
						$min_obj			= $rowVal["min_obj"];
						$tabla_obj			= $rowVal["tabla_obj"];
						$campo_obj			= $rowVal["campo_obj"];
						$mostrar_obj		= $rowVal["mostrar_obj"];
						$order_obj			= $rowVal["order_obj"];
					}

					//Forma para capturar los datos de los objetos
					DibujaTextBox("titulo_obj[\"" . $field . "\"]","Titulo","",1,"Escribe titulo..",$titulo_obj,0);
					DibujaComboBox("tipos_objetos","id_tipo_obj","tipo_obj","",1,"","","id_tipo_obj[\"" . $field . "\"]","Tipo","","Selecciona un tipo..",$id_tipo_obj,$conexion,0,0);
					DibujaTextBox("placeholder_obj[\"" . $field . "\"]","Placeholder","",0,"Escribe placeholder..",$placeholder_obj,0);				
					DibujaComboBox("opcion_sino","id_opcion","nombre_opcion","",1,"","","required_obj[\"" . $field . "\"]","Requerido","","",$required_obj,$conexion,0,0);
					DibujaNumber("max_obj[\"" . $field . "\"]","Maximo","",0,0,"",$max_obj,0);
					DibujaNumber("min_obj[\"" . $field . "\"]","Minimo","",0,0,"",$min_obj,0);

					//Si se detecta que el objeto es de tipo lista se deben capturar los siguientes datos
					if($id_tipo_obj==4) {
						$qryFK = "	SELECT 
										referenced_table_name,
										referenced_column_name
									FROM
										information_schema.key_column_usage
									WHERE
										referenced_table_name is not null
										AND table_schema='$db'
										AND table_name='$nombre_tabla'
										AND column_name='$field';";
						$resFK = mysql_query($qryFK);
						$rowFK = mysql_fetch_array($resFK);

						if($tabla_obj=="") $tabla_obj = $rowFK["referenced_table_name"];
						if($campo_obj=="") $campo_obj = $rowFK["referenced_column_name"];

						DibujaTextBox("tabla_obj[\"" . $field . "\"]","Tabla","",1,"Escriba la tabla..",$tabla_obj,0);
						DibujaTextBox("campo_obj[\"" . $field . "\"]","Campo","",1,"Escriba el campo..",$campo_obj,0);

						$qryTbl = "SHOW COLUMNS FROM " . $rowFK["referenced_table_name"];
						$resTbl = mysql_query($qryTbl);
						while($rowTbl = mysql_fetch_array($resTbl)){
							$arrFields[$rowTbl["Field"]] = $rowTbl["Field"];
						}
						DibujaComboBox("","","","",1,"","","mostrar_obj[\"" . $field . "\"]","Campo Muestra","required","Selecciona un campo..",$mostrar_obj,$conexion,0,0,$arrFields);
					}
					$auxOrder = $order_obj;
					if($auxOrder == "") $auxOrder = $i;
					DibujaNumber("order_obj[\"" . $field . "\"]","Orden","",0,0,"",$auxOrder,1);
					$i++;
					echo "</p></div>";
				}
			}
		}
		?>
	</div> 
	<div class="buttons">
	<input class="bluebutton" type="submit" value="Guardar" />
	</div>
</form>
</body>
</html>

<script>
	$( document ).ready(function() {
		$("#id_proc").change(function(){
			location.href = location.pathname + "?id_proc=7&id_reg=" + $(this).val();
		});
	});
</script>