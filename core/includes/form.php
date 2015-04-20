<style>
textarea {
  background-color: #F8F8F8;
  border: 0;
  display: block;
  font-family: Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, monospace, serif;
  font-size: 12px;
  height: 300px;
  line-height: 21px;
  margin-bottom: 0;
  width: 98%;
  padding: 1%;
}

.form2 {
  width: 100%;
}

.inputtextbox {
  height: 100%;
}

</style>
<?php
//No permitir modificar alumnos de otras escuelas
if(isset($_GET["id_reg"]) && $id_proc==8) {
	$esc_id_alum = getValue($conexion, "vwalumnos", "esc_id_alum", "id_alum = '" . $_GET["id_reg"] . "'");
	if($esc_id_alum != $_SESSION["esc_id_usr"]) {
		echo "<script>alert('El alumno que deseas consultar no es de tu escuela, intenta de nuevo'); location.href='?id_proc=8';</script>";
	}
}
?>
<form enctype="multipart/form-data" method="post" class="form2" id="frmRegistro" action="guardaRegistro.php">

	<div class="formtitle"><?php echo $titleForm . " " . $nombre_aux_tabla; ?></div>

	<?php			
	$maxOrder = getValue($conexion, "objetos", "MAX(order_obj)", "id_tabla_obj = '$id_tabla_obj'");
	$lastRec = getValue($conexion, "objetos", "MAX(id_obj)", "id_tabla_obj = '$id_tabla_obj' AND order_obj='$maxOrder'");
	$qryObj = "SELECT * FROM objetos WHERE id_tabla_obj = $id_tabla_obj ORDER BY order_obj";
	$resObj = mysql_query($qryObj);
	
	while($rowObj = mysql_fetch_array($resObj)) {
		$id_obj				= $rowObj["id_obj"];
		$nombre_obj			= $rowObj["nombre_obj"];
		$titulo_obj			= $rowObj["titulo_obj"];
		$id_tipo_obj		= $rowObj["id_tipo_obj"];
		$size_obj			= $rowObj["size_obj"];
		$placeholder_obj	= $rowObj["placeholder_obj"];
		$required_obj		= $rowObj["required_obj"];
		$max_obj			= $rowObj["max_obj"];
		$min_obj			= $rowObj["min_obj"];
		$tabla_obj			= $rowObj["tabla_obj"];
		$campo_obj			= $rowObj["campo_obj"];
		$mostrar_obj		= $rowObj["mostrar_obj"];
		$order_obj			= $rowObj["order_obj"];
		
		$closeForm = ($lastRec==$id_obj);
		$oValor = getValue($conexion, $nombre_tabla, $nombre_obj, "$pk_tabla = '$id_reg'");
		
		switch($id_tipo_obj){
			//Text
			case 1:
				DibujaTextBox($nombre_obj,$titulo_obj,$max_obj,$required_obj,$placeholder_obj,$oValor,$closeForm);
				break;
			//Password
			case 2:
				if($a == "add")
					DibujaPassword($nombre_obj,$titulo_obj,$max_obj,$required_obj,$placeholder_obj,$oValor,$closeForm);
				break;
			//TextArea
			case 3:
				DibujaTextArea($nombre_obj,$titulo_obj,$required_obj,$placeholder_obj,$oValor,$closeForm);
				break;
			//ListBox
			case 4:
				DibujaComboBox($tabla_obj,$campo_obj,$mostrar_obj,"",1,"","",$nombre_obj,$titulo_obj,"",$placeholder_obj,$oValor,$conexion,0,$closeForm);
				break;
			//Number
			case 5:
				DibujaNumber($nombre_obj,$titulo_obj,$max_obj,$min_obj,$required_obj,$placeholder_obj,$oValor,$closeForm);
				break;
			//Hidden
			case 8:
				if($oValor == ""){
					switch($id_proc){
						case 20:
							$oValor = $_SESSION["id_usr"];
						break;
					}
				}
				DibujaHidden($nombre_obj,$oValor);
				break;
			//Date
			case 9:
				DibujaFecha($nombre_obj,$titulo_obj,$max_obj,$required_obj,$placeholder_obj,$oValor,$closeForm);
				break;
			case 10:
				$auxSrc = getValue($conexion, "vwfotos", "file_pic", "id_pic='$oValor'");
				
				if($auxSrc == "") $auxSrc = "images/user.jpg";
				$required = "";
				if($a=="add" or !file_exists($auxSrc)) $required = "required";
				$auxSrc = utf8_decode($auxSrc);
				//if($a=="add") $required = "required";

				echo "<div><input name='$nombre_obj' type='file' onchange='readURL(this);' accept='image/x-png, image/gif, image/jpeg' $required/><br>Max. 2MB<br><img id='blah' src='$auxSrc' alt='your image' width='113px' height='160px' style='border-width: 2px; border-style: dashed; border-color: gray;'/></div>";
				break;			
		}
	}

	switch($id_proc){
		case 20:
			DibujaComboBox("opcion_sino","id_opcion","nombre_opcion","",1,"","","compartir","Compartir","","","",$conexion,0,false);
			DibujaTextBox("correos_compartir","Correos para compartir (Separar con ,)","","","","",true);
		break;
	}

	?>
	<div class="buttons">
		<input type="hidden" name="a" value="<?php echo $_GET["a"]; ?>">
		<input type="hidden" name="id_reg" value="<?php echo $_GET["id_reg"]; ?>">
		<?php if($id_proc==8){ ?>
			<input class="bluebutton" type="submit" value="Continuar" />
		<?php }else{ ?>
			<input class="bluebutton" type="submit" value="Guardar" />
		<?php } ?>
	</div>
</form>
<script>
	$( document ).ready(function() {
		$("#div_correos_compartir").hide();
		$("#compartir").change(function() {
			$("#div_correos_compartir").show();
		});
	});
</script>
<?php
$script = getValue($conexion, "scripts", "script", "id_proc = '$id_proc'");
echo $script;
?>