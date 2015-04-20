<?php
session_start();
include "../conexion.php";
include "functions.php";
include "variables_session.php";

$id_proc = $_SESSION["id_proc"];
$id_tabla = getValue($conexion, "procesos", "id_tabla_proc", "id_proc = '" . $id_proc . "'");
$arrTabla = getValues($conexion, "tablas", "nombre_tabla, pk_tabla", "id_tabla = '$id_tabla'");
$nombre_tabla = $arrTabla["nombre_tabla"];
$pk_tabla = $arrTabla["pk_tabla"];
$id_reg = $_POST["id_reg"];

$qryReg = "SELECT * FROM objetos WHERE id_tabla_obj = '" . $id_tabla . "'";
$resReg = mysql_query($qryReg);
$auxTipoTabla = false;
while ($rowReg = mysql_fetch_array($resReg)) {
	$a = $_POST["a"];
	switch($a) {
		case "add":
			$auxColumns .= $coma . $rowReg["nombre_obj"];
			if($rowReg["id_tipo_obj"] == 5 and $_POST[$rowReg["nombre_obj"]] == ""){
				$auxValues .= $coma . "0";
			}elseif($rowReg["id_tipo_obj"] == 4 and $_POST[$rowReg["nombre_obj"]] == ""){
				$auxValues .= $coma . "null";
			}elseif($rowReg["id_tipo_obj"] == 2){
				$auxValues .= $coma . "'" . Encripta($_POST[$rowReg["nombre_obj"]]) . "'";
			}elseif($rowReg["id_tipo_obj"] == 10){
				if(is_uploaded_file($_FILES[$rowReg["nombre_obj"]]['tmp_name'])) {
					//Si ya se habia subido una foto, eliminar foto
					$id_foto = getValue($conexion, "alumnos", "id_foto_alum", "id_alum='$id_reg'");
					if($id_foto != ""){ //si existe, traerse datos para eliminar foto
						$arrFoto = getValues($conexion, "fotos", "path_pic, nombre_pic", "id_pic='$id_foto'");
						//Eliminar foto anterior
						unlink($arrFoto["path_pic"] . "/" . $arrFoto["nombre_pic"]);
					}

					$nombre_esc = getValue($conexion, "escuelas", "nombre_esc", "id_esc=" . $_SESSION["esc_id_usr"]);
					//guardar todas las fotos de la escuela en la misma carpeta
					$upload_folder = "files/fotos_alumnos/" . utf8_encode($nombre_esc);
					//Generar un nombre aleatorio
					$upload_name = RandomString(5) . "_" . utf8_encode($_FILES[$rowReg["nombre_obj"]]['name']);
					$upload_file = $upload_folder . "/". $upload_name;

					//Crear folder si no existe
					if (!file_exists($upload_folder)) {
						mkdir($upload_folder, 0777, true);
					}

					if (move_uploaded_file($_FILES[$rowReg["nombre_obj"]]['tmp_name'], $upload_file)) { // falta probar si con esta instruccion, reemplaza el archiv
						if($id_foto == ""){//Agregar o editar datos de la foto
							$qryFile = "INSERT INTO fotos(nombre_pic, original_name_pic, 
														path_pic, size_pic, type_pic)
										VALUES('$upload_name', '" . utf8_encode($_FILES[$rowReg["nombre_obj"]]['name']) . "',
										'$upload_folder', '" . $_FILES[$rowReg["nombre_obj"]]['size'] . "',
										'" . $_FILES[$rowReg["nombre_obj"]]['type'] . "')";
							//$id_foto = mysql_insert_id();
						}else{
							$qryFile = "UPDATE fotos SET 
											nombre_pic = '$upload_name', 
											original_name_pic = '" . utf8_encode($_FILES[$rowReg["nombre_obj"]]['name']) . "', 
											path_pic = '$upload_folder', 
											size_pic = '" . $_FILES[$rowReg["nombre_obj"]]['size'] . "', 
											type_pic = '" . $_FILES[$rowReg["nombre_obj"]]['type'] . "'
										WHERE
											id_pic = '$id_foto'";
						}
						mysql_query($qryFile);
						if($id_foto == "") $id_foto = mysql_insert_id();
					} else {
						echo "<script>alert('Error al cargar el achivo');</script>";
					}
					if($id_foto != "") $auxValues .= $coma . "$id_foto";
				}
			}else{
				$auxValues .= $coma . "'" . $_POST[$rowReg["nombre_obj"]] . "'";
			}
			$coma = ",";
			$qry = "INSERT INTO " . $nombre_tabla . "(" . $auxColumns . ") VALUES(" . $auxValues . ")";
			break;
		case "edit":
		if($rowReg["id_tipo_obj"] == 5 and $_POST[$rowReg["nombre_obj"]] == ""){
				$auxSet .= $coma . $rowReg["nombre_obj"] . "=0";
			}elseif($rowReg["id_tipo_obj"] == 4 and $_POST[$rowReg["nombre_obj"]] == ""){
				$auxSet .= $coma . $rowReg["nombre_obj"] . "=null";
			}elseif($rowReg["id_tipo_obj"] == 2){
				//Entrar pero no hacer nada
			}elseif($rowReg["id_tipo_obj"] == 10){
				if(is_uploaded_file($_FILES[$rowReg["nombre_obj"]]['tmp_name'])) {
					//Si ya se habia subido una foto, eliminar foto
					$id_foto = getValue($conexion, "alumnos", "id_foto_alum", "id_alum='$id_reg'");
					if($id_foto != ""){ //si existe, traerse datos para eliminar foto
						$arrFoto = getValues($conexion, "fotos", "path_pic, nombre_pic", "id_pic='$id_foto'");
						//Eliminar foto
						unlink($arrFoto["path_pic"] . "/" . $arrFoto["nombre_pic"]);
					}

					$nombre_esc = getValue($conexion, "escuelas", "nombre_esc", "id_esc=" . $_SESSION["esc_id_usr"]);
					//guardar todas las fotos de la escuela en la misma carpeta
					$upload_folder = "files/fotos_alumnos/" . utf8_encode($nombre_esc);
					//Generar un nombre aleatorio
					$upload_name = RandomString(5) . "_" . utf8_encode($_FILES[$rowReg["nombre_obj"]]['name']);
					$upload_file = $upload_folder . "/". $upload_name;

					//Crear folder si no existe
					if (!file_exists($upload_folder)) {
						mkdir($upload_folder, 0777, true);
					}

					if (move_uploaded_file($_FILES[$rowReg["nombre_obj"]]['tmp_name'], $upload_file)) { // falta probar si con esta instruccion, reemplaza el archiv
						if($id_foto == ""){//Agregar o editar datos de la foto
							$qryFile = "INSERT INTO fotos(nombre_pic, original_name_pic, 
														path_pic, size_pic, type_pic)
										VALUES('$upload_name', '" . utf8_encode($_FILES[$rowReg["nombre_obj"]]['name']) . "',
										'$upload_folder', '" . $_FILES[$rowReg["nombre_obj"]]['size'] . "',
										'" . $_FILES[$rowReg["nombre_obj"]]['type'] . "')";
						}else{
							$qryFile = "UPDATE fotos SET 
											nombre_pic = '$upload_name', 
											original_name_pic = '" . utf8_encode($_FILES[$rowReg["nombre_obj"]]['name']) . "', 
											path_pic = '$upload_folder', 
											size_pic = '" . $_FILES[$rowReg["nombre_obj"]]['size'] . "', 
											type_pic = '" . $_FILES[$rowReg["nombre_obj"]]['type'] . "'
										WHERE
											id_pic = '$id_foto'";
						}
						mysql_query($qryFile);
						if($id_foto == "") $id_foto = mysql_insert_id();
					} else {
						echo "<script>alert('Error al cargar el achivo');</script>";
					}

					if($id_foto != "") $auxSet .= $coma . $rowReg["nombre_obj"] . "=$id_foto";
				}
			}else{
				$auxSet .= $coma . $rowReg["nombre_obj"] . "='" . $_POST[$rowReg["nombre_obj"]] . "'";
			}
			$coma = ",";
			$qry = "UPDATE " . $nombre_tabla . " SET " . $auxSet . " WHERE " . $pk_tabla . "='" . $id_reg . "'";
			break;
	}
	//Al guardar un proceso prender variable para saber que es de tipo tabla
	if($rowReg["id_tabla_obj"]==2 && $rowReg["nombre_obj"]=="id_tipo_proc"){
		if($_POST[$rowReg["nombre_obj"]] == 1) $auxTipoTabla = true;
	}
}
$res = mysql_query($qry);
if($res){
	echo "<script>alert('Registro guardado correctamente');</script>";
}else{
	if(mysql_errno()==1062){
		echo "<script>alert('Error al guardar el registro');";
	}else{
		echo "<script>alert('Hubo un error al dar de alta');";
	}	
	echo "location.href='index.php'</script>";
	exit();
}

//if(!$auxTipoTabla && $id_proc!=4) $id_proc=0;
switch($id_proc){
	//3 -> Procesos, al guardar un registro configurar los objetos para mostrar en los formularios
	case 3:
		if($a == "add") {
			$id_reg = mysql_insert_id();
		}
		echo "<script>location.href='index.php?id_proc=7&id_reg=$id_reg'</script>";
		break;
	//4 -> Tablas, al guardar un registro dar de alta las columnas para mostrar, enviar al formulario para hacer el registro
	case 4:
		if($a == "add") {
			$id_reg = mysql_insert_id();
		}
		echo "<script>location.href='index.php?id_proc=5&id_reg=$id_reg'</script>";
		break;
	//14 -> Usuarios, al crear un usuario, enviar correo con contraseña
	case 14:
		$auxPwdUsr = getValue($conexion, "users", "pwd_usr", "id_usr='" . $id_reg . "'");
		if($a == "add") $auxPwdUsr = $_POST["pwd_usr"];
		sendPwdUser($_POST["user_usr"], $_POST["correo_usr"], $auxPwdUsr);
		echo "<script>location.href='index.php'</script>";
		break;
	case 20:
		if($_POST["compartir"]=="1"){
			$lenguaje_cog = getValue($conexion, "lenguajes", "nombre_leng", "id_leng='" . $_POST["id_leng"] . "'");
			sendCodigo($_SESSION["nombre_usr"], $_POST["correos_compartir"], htmlspecialchars($_POST["codigo"]), $_POST["nombre_cod"], $lenguaje_cog);
		}
		echo "<script>location.href='index.php'</script>";
	default:
		echo "<script>location.href='index.php'</script>";
		break;
}
include "../cerrar_conexion.php";
?>